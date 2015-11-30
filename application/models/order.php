<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file order.php
* @brief This class stores details for a customer order
* @details Functions to interact with database for get/insert/delete
******************************************************************************/
class Order extends CI_Model {
	// Return all finalized orders
	function getAll() {  
		$query = $this->db->get('order');
		return $query->result('Order');
	}
	// Return single order
	function get($id){
		$query = $this->db->get_where('order',array('id' => $id));
		return $query->row(0,'Order');
	}
	// Delete an order
	function delete($id) {
		return $this->db->delete("order",array('id' => $id ));
	}
	// Delete all orders
    function deleteAll(){
    	return $this->db->truncate('order'); 
    }
	// Insert a new order, return the newly generated order id
	function insert($order) {
		$this->db->insert("order", array(
							'customer_id' => $order['customer_id'],
							'order_date' => $order['order_date'],
							'order_time' => $order['order_time'],
							'total' => $order['total'],
							'creditcard_number' => $order['creditcard_number'],
							'creditcard_month' => $order['creditcard_month'],
							'creditcard_year' => $order['creditcard_year']));
		return $this->db->insert_id();
	}
}
?>
