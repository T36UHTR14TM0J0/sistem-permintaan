<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Permintaan_model extends CI_Model
{

    public function get_permintaan()
    {
        $this->db->select('permintaan.*, departemen.departemen, barang.nama_barang');
        $this->db->from('permintaan');
        $this->db->join('departemen', 'departemen.departemen_id = permintaan.departemen_id');
        $this->db->join('barang', 'barang.id_barang = permintaan.id_barang');
        return $this->db->get()->result();
    }

    public function get_kategori_dan_barang()
    {
        $this->db->select('kategori_barang');
        $this->db->from('barang');
        $this->db->group_by('kategori_barang');
        $query_kategori = $this->db->get()->result();

        foreach ($query_kategori as $kategori) {
            $this->db->select('id_barang, nama_barang, stok,satuan');
            $this->db->from('barang');
            $this->db->where('kategori_barang', $kategori->kategori_barang);
            $kategori->barang = $this->db->get()->result();
        }

        return $query_kategori;
    }

    public function simpan_permintaan($data) {
        $this->db->insert('permintaan', $data);
        return $this->db->insert_id();
    }

    public function simpan_detail_permintaan($data) {
        $this->db->insert('detail_permintaan', $data);
    }

    public function get_all_daftar_permintaan($filter_type, $filter_value, $tanggal_awal, $tanggal_akhir, $limit, $offset)
    {
        $this->db->select('
            user.id_user, 
            user.nama_user, 
            permintaan.id_permintaan,  
            permintaan.deskripsi, 
            permintaan.tanggal_permintaan, 
            permintaan.tanggal_diterima,
            departement.nama_departement,
            permintaan.status,
            penjadwalan.tanggal AS tanggal_jadwal
        ');
        $this->db->from('permintaan');
        $this->db->join('user', 'permintaan.id_user = user.id_user', 'inner');
        $this->db->join('departement', 'user.id_departement = departement.id_departement', 'left');
        $this->db->join('penjadwalan', 'permintaan.id_permintaan = penjadwalan.id_permintaan', 'left');

        // Apply filter by filter_type and filter_value
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
            }
        }

        // Apply filter for date range (tanggal_awal and tanggal_akhir)
        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $this->db->where('permintaan.tanggal_permintaan >=', $tanggal_awal);
            $this->db->where('permintaan.tanggal_permintaan <=', $tanggal_akhir);
        }

        // Apply filter for logged-in user
        $this->db->where('user.id_user', $this->session->userdata('id_user'));

        // Apply limit and offset for pagination
        $this->db->limit($limit, $offset);
        
        // Order by tanggal_permintaan in descending order
        $this->db->order_by('permintaan.tanggal_permintaan', 'DESC');
        
        // Group by columns to avoid SQL errors
        $this->db->group_by('
            user.id_user, 
            user.nama_user, 
            permintaan.id_permintaan, 
            permintaan.deskripsi, 
            permintaan.tanggal_permintaan, 
            permintaan.tanggal_diterima, 
            departement.nama_departement, 
            permintaan.status,
            penjadwalan.tanggal
        ');

        return $this->db->get()->result();
    }


    public function count_all_daftar_permintaan($filter_type, $filter_value, $tanggal_awal, $tanggal_akhir)
    {
        $this->db->from('permintaan');
        $this->db->join('user', 'permintaan.id_user = user.id_user', 'inner');
        $this->db->join('departement', 'user.id_departement = departement.id_departement', 'left');
        $this->db->join('penjadwalan', 'permintaan.id_permintaan = penjadwalan.id_permintaan', 'left');

        // Apply filter by filter_type and filter_value
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
            }
        }

        // Apply filter for date range (tanggal_awal and tanggal_akhir)
        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $this->db->where('permintaan.tanggal_permintaan >=', $tanggal_awal);
            $this->db->where('permintaan.tanggal_permintaan <=', $tanggal_akhir);
        }

        // Apply filter for the logged-in user
        $this->db->where('user.id_user', $this->session->userdata('id_user'));

        // Count the results
        return $this->db->count_all_results();
    }


    public function get_daftar_barang_permintaan($id)
    {
        $this->db->select('*');
        $this->db->from('detail_permintaan');
        $this->db->join('barang','detail_permintaan.id_barang = barang.id_barang');
        $this->db->where('detail_permintaan.id_permintaan', $id);
        return $this->db->get()->result();
    }

    public function get_permintaan_by_id_user($id, $id_user)
{
    $this->db->select('permintaan.*, departement.*, user.*, penjadwalan.tanggal as tanggal_jadwal'); // Memilih semua kolom dari permintaan, departement, dan user, serta kolom tanggal dari penjadwalan dengan alias
    $this->db->from('permintaan');
    $this->db->join('user', 'permintaan.id_user = user.id_user');
    $this->db->join('departement', 'user.id_departement = departement.id_departement');
    $this->db->join('penjadwalan', 'permintaan.id_permintaan = penjadwalan.id_permintaan', 'left'); // left join penjadwalan
    $this->db->where('permintaan.id_permintaan', $id);
    $this->db->where('permintaan.id_user', $id_user);
    return $this->db->get()->row();
}


    public function get_log_permintaan($id)
    {
        $this->db->select('*');
        $this->db->from('log_permintaan');
        $this->db->join('user','log_permintaan.id_user = user.id_user');
        $this->db->where('id_permintaan', $id);
        return $this->db->get()->result();
    }

    public function update_permintaan($id, $data)
    {
        $this->db->where('id_permintaan', $id);
        $this->db->update('permintaan', $data);
        return true;
    }

    public function get_all_permintaan($query = null, $limit = 10, $offset = 0)
    {
        $this->db->select('*');
        $this->db->from('permintaan');
        $this->db->join('user', 'permintaan.id_user = user.id_user');
        $this->db->join('departement', 'user.id_departement = departement.id_departement');
    
        if ($query) {
            $this->db->group_start();  
            $this->db->like('nama_user', $query); 
            $this->db->or_like('deskripsi', $query); 
            $this->db->or_like('nama_departement', $query); 
            $this->db->group_end();
        }
        
        $this->db->limit($limit, $offset);  
        $this->db->order_by('tanggal_permintaan', 'DESC');
        
        return $this->db->get()->result(); 
    }
    
    public function count_all_permintaan($query = null)
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from('permintaan');
        $this->db->join('user', 'permintaan.id_user = user.id_user');
        $this->db->join('departement', 'user.id_departement = departement.id_departement');
    
        if ($query) {
            $this->db->group_start();
            $this->db->like('nama_user', $query);  
            $this->db->or_like('deskripsi', $query); 
            $this->db->or_like('nama_departement', $query); 
            $this->db->group_end();
        }
        
        return $this->db->get()->row()->count;
    }

	public function get_permintaan_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('permintaan');
        $this->db->join('user','permintaan.id_user = user.id_user');
        $this->db->join('departement','user.id_departement = departement.id_departement');
        $this->db->where('permintaan.id_permintaan', $id);
        return $this->db->get()->row();
    }
    




























    // Fungsi untuk menyimpan data permintaan
    public function simpan_pengajuan($data) {
        $this->db->insert('pengajuan', $data); // Tabel permintaan
        return $this->db->insert_id(); // Mengembalikan ID permintaan yang baru disimpan
    }
    


    // Fungsi untuk menyimpan detail permintaan
    


//     public function get_all_penjadwalan()
// {
//     // Menentukan kolom yang ingin diambil
//     $this->db->select('
//         pengajuan.id_pengajuan, 
//         user.id_user, 
//         user.nama_user, 
//         permintaan.id_permintaan, 
//         permintaan.id_departement, 
//         permintaan.deskripsi, 
//         permintaan.tanggal_permintaan, 
//         departement.nama_departement,
//         permintaan.status,
//         IFNULL(penjadwalan.tanggal, "-") AS tanggal_penjadwalan'
//     );
    
//     // Tabel utama
//     $this->db->from('pengajuan');
    
//     // Join dengan tabel lain
//     $this->db->join('user', 'pengajuan.id_user = user.id_user', 'inner');
//     $this->db->join('permintaan', 'pengajuan.id_permintaan = permintaan.id_permintaan', 'left');
//     $this->db->join('departement', 'permintaan.id_departement = departement.id_departement', 'left');
//     $this->db->join('penjadwalan', 'penjadwalan.id_permintaan = permintaan.id_permintaan', 'left');
    
//     // Tambahkan semua kolom ke dalam GROUP BY
//     $this->db->group_by('
//         pengajuan.id_pengajuan, 
//         user.id_user, 
//         user.nama_user, 
//         permintaan.id_permintaan, 
//         permintaan.id_departement, 
//         permintaan.deskripsi, 
//         permintaan.tanggal_permintaan, 
//         departement.nama_departement,
//         permintaan.status,
//         penjadwalan.tanggal
//     ');
    
//     // Urutkan berdasarkan tanggal permintaan terbaru
//     $this->db->order_by('permintaan.tanggal_permintaan', 'DESC');
    
//     // Eksekusi query dan ambil hasilnya
//     return $this->db->get()->result();
// }


    

    










    

    






    // Ambil tanggal penjadwalan berdasarkan id_permintaan
    public function get_tanggal_penjadwalan($id_permintaan) {
        $this->db->select('tanggal');
        $this->db->from('penjadwalan');
        $this->db->where('id_permintaan', $id_permintaan);
        $query = $this->db->get();
        
        // Mengembalikan hasil query, atau NULL jika tidak ditemukan
        return $query->row();
    }


    
    

    public function get_all_pengajuan($query, $limit, $offset)
{
    // Pilih kolom yang diperlukan
    $this->db->select('*');
    
    // Filter status sesuai dengan kondisi yang diinginkan
    $this->db->where_in('status', ['Menunggu Diterima', 'Diterima HRGA']);
    
    // Jika ada query pencarian, kita gunakan LIKE untuk pencarian berdasarkan nama user atau deskripsi
    if (!empty($query)) {
        // Pencarian berdasarkan nama_user
        $this->db->like('user.nama_user', $query);  
        // Pencarian berdasarkan deskripsi
        $this->db->or_like('permintaan.deskripsi', $query);  
        $this->db->or_like('departement.nama_departement', $query);  
    }
    
    // Gabungkan tabel permintaan, user, dan departement
    $this->db->from('permintaan');
    $this->db->join('user', 'permintaan.id_user = user.id_user');
    $this->db->join('departement', 'user.id_departement = departement.id_departement');
    
    // Tentukan limit dan offset untuk pagination
    $this->db->limit($limit, $offset);
    
    // Urutkan data berdasarkan tanggal permintaan terbaru
    $this->db->order_by('tanggal_permintaan', 'DESC');
    
    // Ambil data dan kembalikan hasilnya
    return $this->db->get()->result();
}

public function count_all_pengajuan($query)
{
    // Filter status yang diinginkan
    $this->db->where_in('status', ['Menunggu Diterima', 'Diterima HRGA']);
    
    // Jika ada query pencarian, kita gunakan LIKE untuk pencarian berdasarkan nama user atau deskripsi
    if (!empty($query)) {
        // Pencarian berdasarkan nama_user
        $this->db->like('user.nama_user', $query);  
        // Pencarian berdasarkan deskripsi
        $this->db->or_like('permintaan.deskripsi', $query);  
        $this->db->or_like('departement.nama_departement', $query);  
    }
    
    // Hitung jumlah total data yang sesuai dengan filter dan pencarian
    $this->db->from('permintaan');
    $this->db->join('user', 'permintaan.id_user = user.id_user');
    $this->db->join('departement', 'user.id_departement = departement.id_departement');
    
    // Hitung jumlah data yang sesuai
    return $this->db->count_all_results();
}

    
    



    
    
    

    

    public function update_status_permintaan($id, $status)
    {
        $this->db->where('id_permintaan', $id);
        $this->db->update('permintaan', ['status' => $status]);
    }

    public function get_filtered_permintaan($tanggal_mulai = null, $tanggal_akhir = null, $status = null)
    {
        $this->db->select('permintaan.*, barang.nama_barang, departemen.departemen, dept_head.email');
        $this->db->from('permintaan');
        $this->db->join('barang', 'permintaan.id_barang = barang.id_barang');
        $this->db->join('departemen', 'permintaan.departemen_id = departemen.departemen_id');
        $this->db->join('dept_head', 'permintaan.dept_head_id = dept_head.id');

        // Filter tanggal
        if (!empty($tanggal_mulai)) {
            $this->db->where('permintaan.created_at >=', $tanggal_mulai);
        }
        if (!empty($tanggal_akhir)) {
            $this->db->where('permintaan.created_at <=', $tanggal_akhir);
        }

        // Filter status
        if (!empty($status)) {
            $this->db->where('permintaan.status', $status);
        }

        $this->db->order_by('permintaan.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_daftar_permintaan()
    {
        $this->db->select('*');
        $this->db->from('permintaan');
        $this->db->join('departement', 'departement.id_departement = permintaan.id_departement');
        return $this->db->get()->result();
    }
}
