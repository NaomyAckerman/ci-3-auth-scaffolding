<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model
{

	protected $table 	= 'users';
	protected $primary 	= 'id';

	public function get($id = null, $limit = null, $offset = null)
	{
		if ($id) {
			return $this->db->get_where($this->table, [
				$this->primary => $id
			], $limit, $offset);
		}
		return $this->db->get($this->table);
	}

	public function find_where($where_data = [], $join_data = [], $limit = null, $offset = null)
	{
		if ($join_data) {
			foreach ($join_data as $key => $data) {
				$this->db->join($key, $data);
			}
		}
		return $this->db->get_where($this->table, $where_data, $limit, $offset);
	}

	public function update($data, $id)
	{
		$this->db->update($this->table, $data, [$this->primary => $id]);
		return $this->db->affected_rows();
	}

	public function replace($data)
	{
		$this->db->replace($this->table, $data);
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function delete($where_data)
	{
		$this->db->delete($this->table, $where_data);
		return $this->db->affected_rows();
	}
}
