<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lookup extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('ModelsExecuteMaster');
		$this->load->model('GlobalVar');
		$this->load->model('Apps_mod');
	}
	public function LookupPelanggan()
	{
		$data = array('success' => false ,'message'=>array(),'count'=>0,'data'=>array());

		$param = $this->input->post('param');

		$query = "SELECT * FROM mstr_pelanggan where NmCustomer like '%".$param."%'";

		$rs = $this->db->query($query);

		$data['success'] = true;
		$data['count'] = $rs->num_rows();
		$data['data'] = $rs->result();

		echo json_encode($data);
	}
}