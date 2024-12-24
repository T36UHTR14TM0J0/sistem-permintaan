<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjadwalan_model extends CI_Model {

    public function get_all_penjadwalan($filter_type, $filter_value, $bulan_penjadwalan, $tahun_penjadwalan, $limit, $offset)
    {
        // Select kolom yang diperlukan
        $this->db->select('
            pengajuan.id_pengajuan, 
            user.id_user, 
            user.nama_user, 
            permintaan.id_permintaan, 
            user.id_departement, 
            permintaan.deskripsi, 
            permintaan.tanggal_permintaan, 
            departement.nama_departement,
            permintaan.status,
            penjadwalan.tanggal AS tanggal_penjadwalan'
        );
        $this->db->from('pengajuan');
        $this->db->join('permintaan', 'pengajuan.id_permintaan = permintaan.id_permintaan', 'left');
        $this->db->join('user', 'permintaan.id_user = user.id_user', 'left');
        $this->db->join('departement', 'user.id_departement = departement.id_departement', 'left');
        $this->db->join('penjadwalan', 'penjadwalan.id_permintaan = permintaan.id_permintaan', 'left');

        // Apply filters for 'filter_type' and 'filter_value'
        if (!empty($filter_value)) {
            if ($filter_type == 'all') {
                $this->db->group_start();
                $this->db->like('user.nama_user', $filter_value);
                $this->db->or_like('departement.nama_departement', $filter_value);
                $this->db->or_like('permintaan.deskripsi', $filter_value);
                $this->db->group_end();
            } elseif ($filter_type == 'nama_user') {
                $this->db->like('user.nama_user', $filter_value);
            } elseif ($filter_type == 'nama_departement') {
                $this->db->like('departement.nama_departement', $filter_value);
            } elseif ($filter_type == 'deskripsi') {
                $this->db->like('permintaan.deskripsi', $filter_value);
            }
        }

        // Apply optional filters for bulan and tahun penjadwalan
        if (!empty($bulan_penjadwalan)) {
            $this->db->where('MONTH(penjadwalan.tanggal)', $bulan_penjadwalan);
        }

        if (!empty($tahun_penjadwalan)) {
            $this->db->where('YEAR(penjadwalan.tanggal)', $tahun_penjadwalan);
        }

        // Apply limit and offset for pagination
        $this->db->limit($limit, $offset);
        $this->db->order_by('permintaan.tanggal_permintaan', 'DESC');
        
        // Add columns to GROUP BY to avoid SQL error
        $this->db->group_by('
            pengajuan.id_pengajuan, 
            user.id_user, 
            user.nama_user, 
            permintaan.id_permintaan, 
            user.id_departement, 
            permintaan.deskripsi, 
            permintaan.tanggal_permintaan, 
            departement.nama_departement, 
            permintaan.status, 
            penjadwalan.tanggal
        ');

        // Return the result
        return $this->db->get()->result();
    }

    public function count_all_penjadwalan($filter_type, $filter_value, $bulan_penjadwalan, $tahun_penjadwalan)
    {
        $this->db->from('pengajuan');
        $this->db->join('permintaan', 'pengajuan.id_permintaan = permintaan.id_permintaan', 'left');
        $this->db->join('user', 'permintaan.id_user = user.id_user', 'left');
        $this->db->join('departement', 'user.id_departement = departement.id_departement', 'left');
        $this->db->join('penjadwalan', 'penjadwalan.id_permintaan = permintaan.id_permintaan', 'left');

        // Apply filters for 'filter_type' and 'filter_value'
        if (!empty($filter_value)) {
            if ($filter_type == 'all') {
                $this->db->group_start();
                $this->db->like('user.nama_user', $filter_value);
                $this->db->or_like('departement.nama_departement', $filter_value);
                $this->db->or_like('permintaan.deskripsi', $filter_value);
                $this->db->group_end();
            } elseif ($filter_type == 'nama_user') {
                $this->db->like('user.nama_user', $filter_value);
            } elseif ($filter_type == 'nama_departement') {
                $this->db->like('departement.nama_departement', $filter_value);
            } elseif ($filter_type == 'deskripsi') {
                $this->db->like('permintaan.deskripsi', $filter_value);
            }
        }

        // Apply optional filters for bulan and tahun penjadwalan
        if (!empty($bulan_penjadwalan)) {
            $this->db->where('MONTH(penjadwalan.tanggal)', $bulan_penjadwalan);
        }

        if (!empty($tahun_penjadwalan)) {
            $this->db->where('YEAR(penjadwalan.tanggal)', $tahun_penjadwalan);
        }

        // Count the total results
        return $this->db->count_all_results();
    }

    public function insert_penjadwalan($data) {
        $this->db->insert('penjadwalan', $data); // Tabel permintaan
        return $this->db->insert_id(); // Mengembalikan ID permintaan yang baru disimpan
    }
}