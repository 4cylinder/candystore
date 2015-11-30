<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file order_item.php
* @brief This class stores info about a particular item that was ordered
* @details Links to the "order" class by the "order_id" attribute
******************************************************************************/
class Order_item extends CI_Model {
	// Return all order items sharing the order_id
	function getAll($order_id) {  
		$query = $this->db->get_where('order_item',array('order_id'=>$order_id));
		return $query->result('Order_item');
	}
	// Return single order item based on its own id
	function get($id){
		$query = $this->db->get_where('order_item',array('id' => $id));
		return $query->row(0,'Order_item');
	}
	// Delete an order item
	function delete($id) {
		return $this->db->delete("order_item",array('id' => $id ));
	}
	// Delete all order items sharing the order_id
	function deleteAll($order_id) {
		return $this->db->delete("order_item",array('order_id'=>$order_id));
	}
	// Insert a new order, return the newly generated order id
	function insert($order_item) {
		$this->db->insert("order_item", array(
							'order_id' => $order_item['order_id'],
							'product_id' => $order_item['product_id'],
							'quantity' => $order_item['quantity']));
		return $this->db->insert_id();
	}
}
?>
