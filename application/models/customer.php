<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file customer.php
* @brief This class stores details about a registered customer
* @details Functions to interact with database for comparisons (login), insert,
  delete, and get.
******************************************************************************/
class Customer extends CI_Model {
	// Validate login credentials (also return the whole lot)
	function login($login, $password){
    	$this->db->select('*');
		$this->db->from('customer');
		$this->db->where('login', $login);
		$this->db->where('password', $password);
		$this->db->limit(1);
		$result = $this->db->get()->result();
		if (is_array($result) && count($result)==1)
			return $result[0];
		else
			return false;
    }
    // Register a new user account. Return the newly generated userid (integer)
    function register($customer){
    	$this->db->insert("customer", array(
								'first' => $customer['first'],
								'last' => $customer['last'],
								'login' => $customer['login'],
								'password' => $customer['password'],
								'email' => $customer['email']));
    	return $this->db->insert_id();
    }
    // Return all registered customers
    function getAll() {  
		$query = $this->db->get('customer');
		return $query->result('Customer');
	}
	// Delete a customer
	function delete($id) {
		return $this->db->delete("customer",array('id' => $id ));
	}
	// Delete all registered customers
    function deleteAll(){
    	return $this->db->truncate('customer'); 
    }
	// Return single customer 
	function get($id){
		$query = $this->db->get_where('customer',array('id' => $id));
		return $query->row(0,'Customer');
	}
}
?>
