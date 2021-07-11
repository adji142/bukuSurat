<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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
		$this->load->model('LoginMod');
	}
	public function index()
	{
		$this->load->view('index');
	}
	function Log_Pro()
	{
		$data = array('success' => false ,'message'=>array(),'username'=>array(),'unique_id'=>array());
        $usr = $this->input->post('username');
		$pwd =$this->input->post('password');
		// var_dump($usr.' '.$pwd);
		$Validate_username = $this->LoginMod->Validate_username($usr);
		if($Validate_username->num_rows()>0){
			$userid = $Validate_username->row()->id;
			$pwd_decript =$Validate_username->row()->password;
			// var_dump($this->encryption->decrypt($pwd_decript));
			$pass_valid = $this->encryption->decrypt($Validate_username->row()->password);
			// var_dump($this->encryption->decrypt($Validate_username->row()->password));
			// $get_Validation = $this->LoginMod->Validate_Login($userid,$this->encryption->encrypt($pwd));
			if($pass_valid == $pwd){
				$sess_data['userid']=$userid;
				$sess_data['NamaUser'] = $Validate_username->row()->nama;
				$this->session->set_userdata($sess_data);
				$data['success'] = true;
				$data['username'] = $Validate_username->row()->username;
				$data['unique_id'] = $Validate_username->row()->id;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'L-01'; // User password doesn't match
			}
		}
		else{
			$data['message'] = 'L-02'; // Username not found
		}
		// var_dump($data);
		echo json_encode($data);
	}
	function logout()
	{
		delete_cookie('ci_session');
        $this->session->sess_destroy();
        redirect('Welcome');
	}
	public function RegisterUser()
	{
		$data = array('success' => false ,'message'=>array(),'id' =>'');

		// parameter kode:kode,nama:nama,alamat:alamat,tlp:tlp,mail:mail,pj:pj,tgl:tgl,ket:ket}

		$uname = $this->input->post('uname');
		$nama = $this->input->post('nama');
		$mail = $this->input->post('mail');
		$pass = $this->input->post('pass');
		$role = $this->input->post('role');
		$md_pass = $this->encryption->encrypt($pass);

		// 
		$insert = array(
			'username' 	=> $uname,
			'nama'		=> $nama,
			'email'		=> $mail,
			'password'	=> $md_pass,
		);

		$call = $this->ModelsExecuteMaster->ExecInsert($insert,'users');

		if ($call) {
			$xuser = $this->ModelsExecuteMaster->FindData(array('username'=>$uname),'users');
			if ($xuser->num_rows() > 0) {
				$insert = array(
					'userid' 	=> $xuser->row()->id,
					'roleid'	=> $role,
				);
				$call_x = $this->ModelsExecuteMaster->ExecInsert($insert,'userrole');
				if ($call_x) {
					$data['success'] = true;
				}
			}
		}
		else{
			$data['message'] = 'Data Gagal di input';
		}
		echo json_encode($data);
	}
	public function changepass()
	{
		$data = array('success' => false ,'message'=>array(),'id' =>'');

		$uname = $this->input->post('user');
		$newpass = $this->encryption->encrypt($this->input->post('pass'));
		$id = $this->input->post('id');
		$call =$this->ModelsExecuteMaster->ExecUpdate(array('password'=>$newpass),array('id'=>$id),'users');
		if ($call) {
			$data['success'] = true;
		}
		else{
			$data['message'] = 'Gagal Update password';
		}
		echo json_encode($data);
	}
	public function GetSidebar()
	{
		$data = array('success' => false ,'message'=>array(),'data' =>array());

		$id = $this->input->post('id');
		$call =$this->GlobalVar->GetSideBar($id,1,1);
		if ($call) {
			$data['success'] = true;
			$data['data'] = $call->result();
		}
		else{
			$data['message'] = 'Gagal Update password';
		}
		echo json_encode($data);
	}
}
