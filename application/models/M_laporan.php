<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_laporan extends CI_Model
{
    public function get_all_lap_permintaan($tanggal_awal = null, $tanggal_akhir = null, $departemen = null, $limit, $offset)
    {
        $this->_lap_permintaan_query($tanggal_awal, $tanggal_akhir, $departemen);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_all_lap_permintaan($tanggal_awal = null, $tanggal_akhir = null, $departemen = null)
    {
        $this->_lap_permintaan_query($tanggal_awal, $tanggal_akhir, $departemen);
        return $this->db->count_all_results();
    }

    private function _lap_permintaan_query($tanggal_awal, $tanggal_akhir, $departemen = null)
    {
        $this->db->select('
            user.id_user, 
            user.nama_user, 
            permintaan.id_permintaan, 
            user.id_departement, 
            permintaan.deskripsi, 
            permintaan.tanggal_permintaan, 
            permintaan.tanggal_diterima, 
            penjadwalan.tanggal as tanggal_penjadwalan, 
            departement.nama_departement,
            permintaan.status
        ');
        $this->db->from('permintaan');
        $this->db->join('user', 'permintaan.id_user = user.id_user', 'left');
        $this->db->join('departement', 'user.id_departement = departement.id_departement', 'left');
        $this->db->join('penjadwalan', 'permintaan.id_permintaan = penjadwalan.id_permintaan', 'left');

        if ($tanggal_awal && $tanggal_akhir) {
            $this->db->where("permintaan.tanggal_permintaan BETWEEN '$tanggal_awal' AND '$tanggal_akhir 23:59:59'");
        }

        if ($departemen) {
            $this->db->where('user.id_departement', $departemen);
        }

        $this->db->order_by('permintaan.tanggal_permintaan', 'DESC');
    }

    public function get_all_lap_penjadwalan($tanggal_awal = null, $tanggal_akhir = null, $departemen = null, $limit, $offset)
    {
        $this->_lap_penjadwalan_query($tanggal_awal, $tanggal_akhir, $departemen);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_all_lap_penjadwalan($tanggal_awal = null, $tanggal_akhir = null, $departemen = null)
    {
        $this->_lap_penjadwalan_query($tanggal_awal, $tanggal_akhir, $departemen);
        return $this->db->count_all_results();
    }

    private function _lap_penjadwalan_query($tanggal_awal, $tanggal_akhir, $departemen = null)
    {
        $this->db->select('
            penjadwalan.*, 
            permintaan.*, 
            user.nama_user as nama_user,
            departement.*,
            penjadwalan.tanggal as tanggal_penjadwalan
        ');
        $this->db->from('penjadwalan');
        $this->db->join('permintaan', 'penjadwalan.id_permintaan = permintaan.id_permintaan', 'left');
        $this->db->join('user', 'user.id_user = permintaan.id_user', 'left');
        $this->db->join('departement', 'user.id_departement = departement.id_departement', 'left');

        if ($tanggal_awal && $tanggal_akhir) {
            $this->db->where("penjadwalan.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir 23:59:59'");
        }

        if ($departemen) {
            $this->db->where('permintaan.id_departement', $departemen);
        }

        $this->db->where('permintaan.status', 'Dijadwalkan HRGA');
    }

    public function get_all_lap_barang($tanggal_awal = null, $tanggal_akhir = null,$jenis=null, $limit, $offset)
    {
        $this->db->select('
            inventori_barang.*, 
            user.nama_user AS user_name, 
            barang.nama_barang, 
            barang.satuan, 
            barang.kategori_barang as kategori
        ');
        $this->db->from('inventori_barang');
        $this->db->join('user', 'user.id_user = inventori_barang.id_user', 'left');
        $this->db->join('barang', 'barang.id_barang = inventori_barang.id_barang', 'left');
        
        if ($tanggal_awal && $tanggal_akhir) {
            $tanggal_akhir = $tanggal_akhir . ' 23:59:59';
            $this->db->where("inventori_barang.tanggal BETWEEN '{$tanggal_awal}' AND '{$tanggal_akhir}'");
        }

        if($jenis){
            $this->db->where('status',$jenis);
        }

        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_all_lap_barang($tanggal_awal = null, $tanggal_akhir = null,$jenis = null)
    {
        $this->db->from('inventori_barang');
        $this->db->join('user', 'user.id_user = inventori_barang.id_user', 'left');
        $this->db->join('barang', 'barang.id_barang = inventori_barang.id_barang', 'left');
        
        if ($tanggal_awal && $tanggal_akhir) {
            $tanggal_akhir = $tanggal_akhir . ' 23:59:59';
            $this->db->where("inventori_barang.tanggal BETWEEN '{$tanggal_awal}' AND '{$tanggal_akhir}'");
        }

        if($jenis){
            $this->db->where('status',$jenis);
        }

        return $this->db->count_all_results();
    }

    public function get_barang_by_permintaan($id_permintaan) {
        $this->db->select('barang.nama_barang, detail_permintaan.jumlah, barang.satuan');
        $this->db->from('detail_permintaan');
        $this->db->join('barang', 'detail_permintaan.id_barang = barang.id_barang', 'inner'); // Join tabel barang
        $this->db->where('detail_permintaan.id_permintaan', $id_permintaan); // Filter by id_permintaan
        return $this->db->get()->result();
    }
    

}
