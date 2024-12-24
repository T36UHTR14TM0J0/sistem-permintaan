<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Permintaan_model');
    }

    public function index() {
        $data['body']               = 'frontend';
        $this->load->view('index', $data);
    }
    
}
