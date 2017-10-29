<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_m extends CI_Model{
	public function showAllEmployee(){
		//$this->db->order_by('created_at', 'desc');
		$query = $this->db->get('employee');
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return false;
		}
	}

	public function addEmployee(){
		$field = array(
            'indexNo'=>$this->input->post('txtIndex'),
			'fname'=>$this->input->post('txtFirstName'),
			'lname'=>$this->input->post('txtLastName'),
			'telephone'=>$this->input->post('txtTelephone')
			);
		$this->db->insert('employee', $field);
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function editEmployee(){
		$id = $this->input->get('id');
		$this->db->where('id', $id);
		$query = $this->db->get('employee');
		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return false;
		}
	}

	public function updateEmployee(){
		$id = $this->input->post('txtId');
		$field = array(
		    'indexNo'=>$this->input->post('txtIndex'),
		    'fname'=>$this->input->post('txtFirstName'),
		    'lname'=>$this->input->post('txtLastName'),
            'telephone'=>$this->input->post('txtTelephone'),
		    'updated_at'=>date('Y-m-d H:i:s')
		);
		$this->db->where('id', $id);
		$this->db->update('employee', $field);
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	function deleteEmployee(){
		$id = $this->input->get('id');
		$this->db->where('id', $id);
		$this->db->delete('employee');
		if($this->db->affected_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
}