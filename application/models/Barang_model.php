<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model {

    public function get_all_barang($limit, $offset)
	{
		$this->db->select('*');
		$this->db->from('barang');
		$this->db->limit($limit, $offset); 
		return $this->db->get()->result();
	}

	public function count_all_barang()
	{
		return $this->db->count_all('barang');
	}

	public function search_barang($filter, $query, $limit, $offset)
	{
		$this->db->like($filter, $query); // Pencarian berdasarkan filter
		
		// Menambahkan limit dan offset untuk pagination
		$this->db->limit($limit, $offset);
		
		$query = $this->db->get('barang');
		return $query->result();
	}


	public function count_search_barang($filter, $query)
	{
		$this->db->like($filter, $query); // Pencarian berdasarkan filter
		$this->db->from('barang');
		return $this->db->count_all_results();
	}

    // Menambah Barang
    public function insert_barang($data)
    {
        $this->db->insert('barang', $data);
        return $this->db->insert_id();
    }

    public function update_barang($id, $data)
    {
        $this->db->update('barang', $data, ['id_barang' => $id]);
    }

    public function delete_barang($id)
    {
        $this->db->delete('barang', ['id_barang' => $id]);
    }

    public function get_barang_by_id($id)
    {
        return $this->db->get_where('barang', ['id_barang' => $id])->row();
    }

    public function update_stok($id, $stok_baru)
    {
        $this->db->where('id_barang', $id);
        $this->db->update('barang', ['stok' => $stok_baru]);
    }

    public function insert_inventori($data)
    {
        $this->db->insert('inventori_barang', $data); 
            return $this->db->insert_id();
    }

    public function update_stok_barang($id, $stok) {
        $this->db->set('stok', 'stok + ' . (int)$stok, FALSE); // Menambahkan stok yang baru
        $this->db->where('id_barang', $id);
        return $this->db->update('barang');
    }



}