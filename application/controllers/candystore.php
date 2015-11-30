<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file candystore.php
* @brief This is the main controller for our candy store assignment
* @details Major functions like registering users, logging in users, processing 
  orders, creating/deleting products, etc are located in this controller
******************************************************************************/
class CandyStore extends CI_Controller {
    function __construct() {
		// Call the Controller constructor
    	parent::__construct();
    	$config['upload_path'] = './images/product/';
    	$config['allowed_types'] = 'gif|jpg|png';
    	$this->load->library('upload', $config);
	    $this->load->model('product_model');
		$this->load->model('customer');
		$this->load->model('order');
		$this->load->model('order_item');
		$this->load->library('form_validation');
    }
    /***************************************************************************
	* @brief Index page displayed when attempting to access candystore
	***************************************************************************/
	function index() {
		// If the user is logged in, redirect to the admin or store page
		if($this->session->userdata('loggedin')) {
			$customer = $this->session->userdata('loggedin');
			if ($customer['login']=="admin"){
				$this->admin();
			} else {
				$this->catalog();
			}
		} else { // Otherwise load the login form
			$this->load->view('customer/login');
		}
	}
	/***************************************************************************
	* @brief If the user logs in as "admin", index redirects to the admin page
	***************************************************************************/
    function admin() {
		// Grab a list of existing products
		$products = $this->product_model->getAll();
		$data['products']=$products;
		$this->load->view('product/list',$data);
		// Grab a list of registered customers
		$customers = $this->customer->getAll();
		$data['customers']=$customers;
		$this->load->view('customer/list',$data);
		// Grab a list of finalized orders
		$orders = $this->order->getAll();
		$data['orders']=$orders;
		$this->load->view('order/list',$data);
		echo anchor('candystore/logout', 'Logout')."<br/>\n";
    }
    /***************************************************************************
	* @brief If the user is just a customer, index redirects to the store page
	***************************************************************************/
    function catalog(){
		// Validate the "total" variable from the catalog
		$this->form_validation->set_rules('total','Total','callback_checkTotal');
    	$data['products']=$this->product_model->getAll();
		$data['customer'] = $this->session->userdata('loggedin');
		$data['submit'] = $this->input->post('submit');
		$data['orders'] = array();
		if($this->session->userdata('orders'))
			$data['orders'] = $this->session->userdata('orders');
		// No need to validate input or update orders if simply viewing the cart
		if ($this->input->post('submit')=="View/Edit Cart")
			$this->cart($data);
		// Otherwise, validate input and update session orders
		else if ($this->form_validation->run() == true){
    		// In the POST data, a numeric key is a Product ID and the
    		// corresponding value is the quantity selected.
			foreach($this->input->post() as $key=>$value){
				if (is_numeric($key)){
					// If the session data already has a few of this product
					// ordered, then increment the quantity ordered.
					// Only increment if adding to cart, not if saving edited cart
					if (array_key_exists($key, $data['orders']) &&
						$this->input->post('submit')=="Add to Cart")
						$data['orders'][$key] += $value;
					else
						$data['orders'][$key] = $value;
				}
			}
			// Update the session data with the new quantities
    		$this->session->set_userdata('orders',$data['orders']);
    		if ($this->input->post('submit')=="Add to Cart")
    			$this->cart($data);
    		else if ($this->input->post('submit')=="Save and Continue Shopping")
    			$this->load->view('customer/catalog',$data);
    		else if ($this->input->post('submit')=="Checkout")
    			$this->checkout($data);
	    }
	    else {
	    	$this->load->view('customer/catalog',$data);
	    }
		echo anchor('candystore/logout', 'Logout')."<br/>\n";
    }
    /***************************************************************************
	* @brief Helper function to load the cart page
	* @param $data POST form data containing order details
	***************************************************************************/
    function cart($data){
    	$this->load->view('order/cart',$data);
    }
    /***************************************************************************
	* @brief Process order details when user checks out his cart
	* @param $data POST data containing user's order details
	***************************************************************************/
    function checkout($data=array()){
    	$data['customer'] = $this->session->userdata('loggedin');
		$data['orders'] = $this->session->userdata('orders');
		$this->form_validation->set_rules('email','Email', 'required|email');
		$this->form_validation->set_rules('creditcard_number','Credit card #', 
										'required|exact_length[16]|numeric');
		$this->form_validation->set_rules('creditcard_month','Expiry month', 
										'required|exact_length[2]|numeric');
		$this->form_validation->set_rules('creditcard_year','Expiry year', 
						'required|exact_length[2]|numeric|callback_checkExpiry');
		// If the form data is invalid, redirect back to the checkout page
		if ($this->form_validation->run() == false)	{
    		$this->load->view('order/checkout',$data);
    	} else { // Otherwise add this order to the database
    		$order['customer'] = $data['customer'];
    		$order['orders'] = $data['orders'];
    		$order['customer_id'] = $data['customer']['id'];
    		$order['order_date'] = date('Y-m-d');
    		$order['order_time'] = date('H:i:s');
    		$order['total'] = $this->input->post('total');
    		$order['creditcard_number'] = $this->input->post('creditcard_number');
    		$order['creditcard_month'] = $this->input->post('creditcard_month');
    		$order['creditcard_year'] = $this->input->post('creditcard_year');
    		$orderid = $this->order->insert($order);
    		foreach ($order['orders'] as $key=>$value){
    			if ($value>0){
					$order_item = array();
					$order_item['order_id']=$orderid;
					$order_item['product_id']=$key;
					$order_item['quantity']=$value;
					$this->order_item->insert($order_item);
    			}
    		}
    		// Load receipt after adding order
    		$this->receipt($order);
    	}
    }
    /***************************************************************************
	* @brief Check credit card expiration date when user places order
	* @param $year Expiry year of credit card
	* @return True if credit card hasn't expired yet, false otherwise
	***************************************************************************/
    function checkExpiry($year){
    	// get expiry month
    	$month = $this->input->post('creditcard_month');
    	// get current month and year
    	$m = date('m');
		$y = date('y');
		if ($month<1 || $month > 12){
			$this->form_validation->set_message('checkExpiry', 'Not a valid month!');
			return false;
		}
		if ($year>$y || ($year==$y && $month>=$m))
    		return true;
    	else
    		$this->form_validation->set_message('checkExpiry', 'The card has expired');
    	return false;
    }
    /***************************************************************************
	* @brief After the user has successfully ordered, show the receipt
	* @param POST data containing order details
	***************************************************************************/
    function receipt($data){
    	$this->load->view('order/receipt',$data);
    	// prevent accidental re-ordering (e.g. when clicking Back)
    	// by unsetting the session data
    	$this->session->unset_userdata('orders');
    }
    /***************************************************************************
	* @brief If no one is logged in when accessing index, redirect to this form
	***************************************************************************/
    function login(){
    	$this->form_validation->set_rules('login','Username','required');
    	$this->form_validation->set_rules('password','Password','required|callback_verify');
    	// Redirect back to login form if it doesn't match
    	if ($this->form_validation->run() == false){
    		$this->load->view('customer/login');
	    }
	    else { // Otherwise go back to index (which will go to admin/catalog
	    	redirect('','refresh');
	    }
    }
    /***************************************************************************
	* @brief callback function to check if user submits an order > $0.00
	* @param $total Total value of order when user clicks checkout.
	* @return True if $total > 0, false otherwise
	***************************************************************************/
    function checkTotal($total){
    	// if simply saving the cart, it's ok to be 0 (i.e. to cancel order)
    	if ($this->input->post('submit')=="Save and Continue Shopping")
    		return true;
    	$this->form_validation->set_message('checkTotal', 
    							'You need to add at least 1 item to your cart');
    	return (floatval($total)>0);
    }
    /***************************************************************************
	* @brief Callback function to check login credentials
	* @param $password Password that the user entered in the login form
	* @return True if the credentials match, false otherwise
	***************************************************************************/
    function verify($password){
    	$login = $this->input->post('login');
    	$result = $this->customer->login($login, $password);
    	if ($result) {
    		$customer = array();
			$customer['login'] = $result->login;
			$customer['id'] = $result->id;
			$customer['name'] = $result->first." ".$result->last;
			$customer['email'] = $result->email;
			$this->session->set_userdata('loggedin',$customer);
    		return true;
    	}
    	$this->form_validation->set_message('verify', 
    		'Username does not exist or password is incorrect');
    	return false;
    }
    /***************************************************************************
	* @brief Logout function that unsets all session data (for security)
	***************************************************************************/
    function logout(){
    	$this->session->unset_userdata('loggedin');
    	$this->session->unset_userdata('orders');
    	// Go back to index (which will redirect again to the login form)
    	redirect('', 'refresh');
    }
    /***************************************************************************
	* @brief Handle registration of a new user
	***************************************************************************/
    function register(){
    	$this->form_validation->set_rules('login','Username',
    		'required|is_unique[customer.login]');
    	$this->form_validation->set_message('is_unique[customer.login]', 'Username is taken');
    	$this->form_validation->set_rules('first','First Name','required');
    	$this->form_validation->set_rules('last','Last Name','required');
    	$this->form_validation->set_rules('email','Email Address',
    		'required|is_unique[customer.email]');
    	$this->form_validation->set_message('is_unique[customer.email]', 'Email is taken');
    	$this->form_validation->set_rules('password','Password','required');
    	// if fields are valid, then register new user
    	if ($this->form_validation->run() == true){
    		foreach($this->input->post() as $key=>$value){
    			$customer[$key] = $value;
    		}
			// If new account is successfully created, then log in for the user
			$result = $this->customer->register($customer);
			$customer['id'] = $result;
			$customer['name'] = $customer['first']." ".$customer['last'];
			$this->session->set_userdata('loggedin',$customer);
			redirect('','refresh');
	    }
	    else { // If the validation failed, redirect back to registration form
	    	$this->load->view('customer/register');
	    }
    }
    /***************************************************************************
	* @brief Helper function to open the admin page for creating a new product
	***************************************************************************/
    function newForm() {
	    $this->load->view('product/newForm.php');
    }
    /***************************************************************************
	* @brief Create a new product for customers to buy
	***************************************************************************/
	function create() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Name','required|is_unique[product.name]');
		$this->form_validation->set_rules('description','Description','required');
		$this->form_validation->set_rules('price','Price','required');
		// Attempt to upload pic of product
		$fileUploadSuccess = $this->upload->do_upload();
		// Check form data and that a pic is uploaded properly
		if ($this->form_validation->run() == true && $fileUploadSuccess) {
			$product = new Product();
			$product->name = $this->input->get_post('name');
			$product->description = $this->input->get_post('description');
			$product->price = $this->input->get_post('price');
			$data = $this->upload->data();
			$product->photo_url = $data['file_name'];
			$this->product_model->insert($product);
			//Then we redirect to the index page again
			redirect('', 'refresh');
		}
		else { //If the file failed to upload, show the error
			if (!$fileUploadSuccess) {
				$data['fileerror'] = $this->upload->display_errors();
				$this->load->view('product/newForm.php',$data);
				return;
			}
			$this->load->view('product/newForm.php');
		}	
	}
	/***************************************************************************
	* @brief Admin function to delete an order from the database
	* @param $id The order ID of the order to be deleted
	***************************************************************************/
	function deleteOrder($id){
		// Delete all order_items first then delete the order itself
		if (isset($id)){
			$this->order_item->deleteAll($id);
			$this->order->delete($id);
		}
		//Then we redirect to the index page again
		redirect('', 'refresh');
	}
	/***************************************************************************
	* @brief Admin function to delete ALL orders from the database
	***************************************************************************/
	function deleteAllOrders() {
		$this->order->deleteAll();
		//Then we redirect to the index page again
		redirect('', 'refresh');
	}
	/***************************************************************************
	* @brief Admin function to view specific details of a completed order
	* @param $order_id The ID of the order being viewed
	***************************************************************************/
	function orderDetail($order_id){
    	// get the order ID, then all associated order_items
    	$order = $this->order->get($order_id);
    	$items = $this->order_item->getAll($order_id);
    	// get customer info
    	$customer = $this->customer->get($order->customer_id);
    	$data['customer'] = $customer;
    	$data['order'] = $order;
    	$data['order_items'] = $items;
    	// Show all those details on this new page
    	$this->load->view('order/detail',$data);
	}
	/***************************************************************************
	* @brief Admin function to view specific details of a created product
	* @param $id The product ID of the product being viewed
	***************************************************************************/
	function read($id) {
		$product = $this->product_model->get($id);
		$data['product']=$product;
		$this->load->view('product/read.php',$data);
	}
	/***************************************************************************
	* @brief Admin function to edit specific details of a created product
	* @param $id The ID of the product to be edited
	***************************************************************************/
	function editForm($id) {
		$product = $this->product_model->get($id);
		$data['product']=$product;
		$this->load->view('product/editForm.php',$data);
	}
	/***************************************************************************
	* @brief Admin function to insert new details if a product has been edited
	* @param $id The ID of the product that has been edited
	***************************************************************************/
	function update($id) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('description','Description','required');
		$this->form_validation->set_rules('price','Price','required');
		// If form data is valid, update the database with new details
		if ($this->form_validation->run() == true) {
			$product = new Product();
			$product->id = $id;
			$product->name = $this->input->get_post('name');
			$product->description = $this->input->get_post('description');
			$product->price = $this->input->get_post('price');
			$this->product_model->update($product);
			//Then we redirect to the index page again
			redirect('', 'refresh');
		}
		else { // Otherwise go back to edit page (with old data)
			$product = new Product();
			$product->id = $id;
			$product->name = set_value('name');
			$product->description = set_value('description');
			$product->price = set_value('price');
			$data['product']=$product;
			$this->load->view('product/editForm.php',$data);
		}
	}
   	/***************************************************************************
	* @brief Admin function to delete a created product from the database
	* @param $id The ID of the product to be deleted
	***************************************************************************/
	function deleteProduct($id) {
		if (isset($id)) 
			$this->product_model->delete($id);
		//Then we redirect to the index page again
		redirect('', 'refresh');
	}
	/***************************************************************************
	* @brief Admin function to delete a registered customer from the database
	* @param $id The ID of the customer to be deleted
	***************************************************************************/
	function deleteCustomer($id) {
		if (isset($id))
			$this->customer->delete($id);
		//Then we redirect to the index page again
		redirect('', 'refresh');
	}
	/***************************************************************************
	* @brief Admin function to delete ALL customers from the database
	***************************************************************************/
	function deleteAllCustomers() {
		$this->customer->deleteAll();
		//Then we redirect to the index page again
		redirect('', 'refresh');
	}
}
?>
