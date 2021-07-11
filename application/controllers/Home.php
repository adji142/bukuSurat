<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends CI_Controller {

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
		$this->load->model('LoginMod');
	}
	public function index()
	{
		$this->load->view('Dashboard');
	}
	// --------------------------------------- Master ----------------------------------------------------
	public function klasifikasi()
	{
		$this->load->view('V_DataKlasifikasi');
	}
	public function suratmasuk()
	{
		$this->load->view('V_SuratMasuk');
	}
	public function suratkeluar()
	{
		$this->load->view('V_SuratKeluar');
	}
	public function pemusnahan()
	{
		$this->load->view('V_PemusnahanSurat');
	}
	public function rptSuratMasuk()
	{
		$this->load->view('Rpt_SuratMasuk');
	}
	public function rptSuratKeluar()
	{
		$this->load->view('Rpt_SuratKeluar');
	}
	public function rptPemusnahanSurat()
	{
		$this->load->view('Rpt_PemusnahanSurat');
	}
}
