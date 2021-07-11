<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apps extends CI_Controller {

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
		$this->load->library('session');
	}
	public function GetCount()
	{
		$data = array('success' => false ,'message'=>array(),'count'=>0);

		$table = $this->input->post('table');

		$rs = $this->ModelsExecuteMaster->GetData($table);
		$data['success'] = true;
		if ($rs->num_rows() > 0) {
			$data['count'] = $rs->num_rows();
		}
		else{
			$data['count'] = 0;	
		}

		echo json_encode($data);
	}

	//  ============================ Klasifikasi ============================
	public function GetKlasifikasi()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$id = $this->input->post('id');

		$rs = null;
		if ($id == '') {
			$rs = $this->ModelsExecuteMaster->GetData('tklasifikasi');
		}
		else{
			$rs = $this->ModelsExecuteMaster->FindData(array('id'=>$id),'tklasifikasi');
		}
		if ($rs) {
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Gagal Mendapatkan Data';
		}
		echo json_encode($data);
	}
	public function CRUD_Klasifikasi()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$id = $this->input->post('id');
		$NamaKlasifikasi = $this->input->post('NamaKlasifikasi');
		$formtype = $this->input->post('formtype');

		$param = array(
			'id'				=> $id,
			'NamaKlasifikasi'	=> $NamaKlasifikasi
		);

		if ($formtype=='add') {
			$rs = $this->ModelsExecuteMaster->ExecInsert($param,'tklasifikasi');
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Insert Data';
			}
		}
		elseif ($formtype == 'edit') {
			$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('id'=>$id),'tklasifikasi');
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Update Data';
			}
		}
		elseif ($formtype=='delete') {
			$SQL = 'DELETE FROM tklasifikasi WHERE id = '.$id;

			$rs = $this->db->query($SQL);
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Delete Data';
			}
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Invalid Form Type';
		}

		echo json_encode($data);
	}
	//  ============================ Klasifikasi ============================


	//  ============================ Surat Masuk ============================
	public function GetSuratMasuk()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$NoAgenda = $this->input->post('NoAgenda');

		$rs = null;
		$SQL = "SELECT * FROM datasurat a 
					LEFT JOIN tklasifikasi b on a.KodeKlasifikasiSurat = b.id
					where 1 = 1 AND a.JenisSurat = 'Masuk'
				";
		if ($NoAgenda == '') {
			$rs = $this->db->query($SQL);
		}
		else{
			$SQL .= " AND a.NoAgenda='".$NoAgenda."'";
			$rs = $this->db->query($SQL);
		}
		if ($rs) {
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Gagal Mendapatkan Data';
		}
		echo json_encode($data);
	}
	public function CRUD_SuratMasuk()
	{
		ini_set('memory_limit', '1000M');
		ini_set('upload_max_filesize', '1000M');
		ini_set('post_max_size', '1000M');

		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$NoAgenda = $this->input->post('NoAgenda');
		$KodeKlasifikasiSurat = $this->input->post('KodeKlasifikasiSurat');
		$AsalSurat = $this->input->post('AsalSurat');
		$TanggalSurat = $this->input->post('TanggalSurat');
		$TglPelaksanaanSurat = $this->input->post('TglPelaksanaanSurat');
		$NomorSurat = $this->input->post('NomorSurat');
		$IsiSurat = $this->input->post('IsiSurat');
		// $LinkFile = $this->input->post('LinkFile');
		$base64File = $this->input->post('picture_base64');


		$formtype = $this->input->post('formtype');


		$picture_ext = '';
		try {
			unset($config); 
			$date = date("ymd");
	        $config['upload_path'] = './localData';
	        $config['max_size'] = '60000';
	        $config['allowed_types'] = 'png|jpg|jpeg|doc|docx|pdf';
	        $config['overwrite'] = TRUE;
	        $config['remove_spaces'] = TRUE;
	        $config['file_ext_tolower'] = TRUE;
	        $config['file_name'] = strtolower(str_replace(' ', '', $NoAgenda));

	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);

	        if(!$this->upload->do_upload('Attachment')) {
	        	if ($formtype == 'edit' || $formtype == 'delete' || $formtype == 'Publish') {
	        		$x='';
	        	}
	        	else{
	        		$x = $this->upload->data();
		        	// var_dump($x);
		        	$data['success'] = false;
		            $data['message'] = $this->upload->display_errors();
		            goto jumpx;
	        	}
	        }else{
	            $dataDetails = $this->upload->data();
	            $picture_ext = $dataDetails['file_ext'];
	            if ($picture_ext == '.jpeg') {
	            	$picture_ext = '.jpg';
	            }
	        }	
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = $e->getMessage();
			goto jumpx;
		}

		if ($base64File!='') {
			$extension = '';
			if ($formtype == 'add' || $formtype == 'edit') {
				$pos  = strpos($base64File, ';');
				$type = explode(':', substr($base64File, 0, $pos))[1];
				// var_dump($type);
				$extension = explode('/', $type)[1];
				$picture_ext = '.'.$extension;
			}
			if ($extension == 'jpeg') {
				$picture_ext = '.jpg';
			}
		}

		$param = array(
			'NoAgenda' => $NoAgenda,
			'KodeKlasifikasiSurat' => $KodeKlasifikasiSurat,
			'AsalSurat' => $AsalSurat,
			'TanggalSurat' => $TanggalSurat,
			'NomorSurat' => $NomorSurat,
			'IsiSurat' => $IsiSurat,
			'LinkFile' => base_url().'localData/'.strtolower(str_replace(' ', '', $NoAgenda)).''.strtolower($picture_ext),
			'base64File' => $base64File,
			'TglPelaksanaanSurat'=> $TglPelaksanaanSurat,
			'CreatedOn' => date("Y-m-d h:i:sa"),
			'JenisSurat'=>'Masuk'
		);

		if ($formtype=='add') {
			$rs = $this->ModelsExecuteMaster->ExecInsert($param,'datasurat');
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Insert Data';
			}
		}
		elseif ($formtype == 'edit') {
			$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('NoAgenda'=>$NoAgenda),'datasurat');
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Update Data';
			}
		}
		elseif ($formtype=='delete') {
			$SQL = "DELETE FROM datasurat WHERE NoAgenda = '".$NoAgend."'";

			$rs = $this->db->query($SQL);
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Delete Data';
			}
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Invalid Form Type';
		}

		jumpx:
		echo json_encode($data);
	}
	//  ============================ Surat Masuk ============================

	//  ============================ Surat Keluar ============================
	public function GetSuratKeluar()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$NoAgenda = $this->input->post('NoAgenda');

		$rs = null;
		$SQL = "SELECT * FROM datasurat a 
					LEFT JOIN tklasifikasi b on a.KodeKlasifikasiSurat = b.id
					where 1 = 1 AND a.JenisSurat = 'Keluar'
				";
		if ($NoAgenda == '') {
			$rs = $this->db->query($SQL);
		}
		else{
			$SQL .= " AND a.NoAgenda='".$NoAgenda."'";
			$rs = $this->db->query($SQL);
		}
		if ($rs) {
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Gagal Mendapatkan Data';
		}
		echo json_encode($data);
	}
	public function CRUD_SuratKeluar()
	{
		ini_set('memory_limit', '1000M');
		ini_set('upload_max_filesize', '1000M');
		ini_set('post_max_size', '1000M');

		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$NoAgenda = $this->input->post('NoAgenda');
		$KodeKlasifikasiSurat = $this->input->post('KodeKlasifikasiSurat');
		$AsalSurat = $this->input->post('AsalSurat');
		$TanggalSurat = $this->input->post('TanggalSurat');
		$TglPelaksanaanSurat = $this->input->post('TglPelaksanaanSurat');
		$NomorSurat = $this->input->post('NomorSurat');
		$IsiSurat = $this->input->post('IsiSurat');
		// $LinkFile = $this->input->post('LinkFile');
		$base64File = $this->input->post('picture_base64');


		$formtype = $this->input->post('formtype');


		$picture_ext = '';
		try {
			unset($config); 
			$date = date("ymd");
	        $config['upload_path'] = './localData';
	        $config['max_size'] = '60000';
	        $config['allowed_types'] = 'png|jpg|jpeg|doc|docx|pdf';
	        $config['overwrite'] = TRUE;
	        $config['remove_spaces'] = TRUE;
	        $config['file_ext_tolower'] = TRUE;
	        $config['file_name'] = strtolower(str_replace(' ', '', $NoAgenda));

	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);

	        if(!$this->upload->do_upload('Attachment')) {
	        	if ($formtype == 'edit' || $formtype == 'delete' || $formtype == 'Publish') {
	        		$x='';
	        	}
	        	else{
	        		$x = $this->upload->data();
		        	// var_dump($x);
		        	$data['success'] = false;
		            $data['message'] = $this->upload->display_errors();
		            goto jumpx;
	        	}
	        }else{
	            $dataDetails = $this->upload->data();
	            $picture_ext = $dataDetails['file_ext'];
	            if ($picture_ext == '.jpeg') {
	            	$picture_ext = '.jpg';
	            }
	        }	
		} catch (Exception $e) {
			$data['success'] = false;
			$data['message'] = $e->getMessage();
			goto jumpx;
		}

		if ($base64File!='') {
			$extension = '';
			if ($formtype == 'add' || $formtype == 'edit') {
				$pos  = strpos($base64File, ';');
				$type = explode(':', substr($base64File, 0, $pos))[1];
				// var_dump($type);
				$extension = explode('/', $type)[1];
				$picture_ext = '.'.$extension;
			}
			if ($extension == 'jpeg') {
				$picture_ext = '.jpg';
			}
		}

		$param = array(
			'NoAgenda' => $NoAgenda,
			'KodeKlasifikasiSurat' => $KodeKlasifikasiSurat,
			'AsalSurat' => $AsalSurat,
			'TanggalSurat' => $TanggalSurat,
			'NomorSurat' => $NomorSurat,
			'IsiSurat' => $IsiSurat,
			'LinkFile' => base_url().'localData/'.strtolower(str_replace(' ', '', $NoAgenda)).''.strtolower($picture_ext),
			'base64File' => $base64File,
			'CreatedOn' => date("Y-m-d h:i:sa"),
			'JenisSurat'=>'Keluar'
		);

		if ($formtype=='add') {
			$rs = $this->ModelsExecuteMaster->ExecInsert($param,'datasurat');
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Insert Data';
			}
		}
		elseif ($formtype == 'edit') {
			$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('NoAgenda'=>$NoAgenda),'datasurat');
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Update Data';
			}
		}
		elseif ($formtype=='delete') {
			$SQL = "DELETE FROM datasurat WHERE NoAgenda = '".$NoAgend."'";

			$rs = $this->db->query($SQL);
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Delete Data';
			}
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Invalid Form Type';
		}

		jumpx:
		echo json_encode($data);
	}
	//  ============================ Surat Keluar ============================


	//  ============================ Pemusnahan Surat ============================
	public function GetWrapOutLookup()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$SQL = '
			SELECT 
				a.NoAgenda, 
				a.NomorSurat, 
				a.AsalSurat, 
				a.TanggalSurat, 
				a.TglPelaksanaanSurat, 
				a.JenisSurat,
				b.WrapOutDate,
				b.Keterangan,
				b.CreatedBy
			FROM datasurat a
			LEFT JOIN pemusnahansurat b on a.NoAgenda = b.BaseRef
			WHERE b.id IS NULL 
		';

		$rs = $this->db->query($SQL);
		if ($rs) {
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		echo json_encode($data);
	}

	public function GetPemusnahanSurat()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());

		$TglAwal = $this->input->post('TglAwal');
		$TglAkhir = $this->input->post('TglAkhir');

		$SQL = "
			SELECT 
				a.id,
				a.WrapOutDate,
				a.CreatedBy,
				b.NoAgenda,
				b.TanggalSurat,
				b.TglPelaksanaanSurat,
				b.JenisSurat
			FROM pemusnahansurat a
			LEFT JOIN datasurat b on a.BaseRef = b.NoAgenda
			WHERE 1 =1 
		";

		if ($TglAwal != '') {
			$SQL .= " AND a.WrapOutDate BETWEEN '$TglAwal' AND '$TglAkhir' ";
		}
		// var_dump($SQL);
		$rs = $this->db->query($SQL);

		if ($rs) {
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		echo json_encode($data);
	}
	public function CRUD_Pemusnahan()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());
	
		// Add parameter hire
		$id = $this->input->post('id');
		$WrapOutDate = $this->input->post('WrapOutDate');
		$BaseRef = $this->input->post('BaseRef');
		$Keterangan = $this->input->post('Keterangan');
		$CreatedOn = date("Y-m-d h:i:sa");
		$CreatedBy = $this->session->userdata('NamaUser');
		$formtype= $this->input->post('formtype');
		// Add parameter hire
	
		$param = array(
			'WrapOutDate' => $WrapOutDate,
			'BaseRef' => $BaseRef,
			'Keterangan' => $Keterangan,
			'CreatedOn' => $CreatedOn,
			'CreatedBy' => $CreatedBy
		);
	
		if ($formtype=='add') {
			$rs = $this->ModelsExecuteMaster->ExecInsert($param,'pemusnahansurat');
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Insert Data';
			}
		}
		elseif ($formtype == 'edit') {
			$rs = $this->ModelsExecuteMaster->ExecUpdate($param,array('id'=>$id),'pemusnahansurat');
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Update Data';
			}
		}
		elseif ($formtype=='delete') {
			$SQL = 'DELETE FROM pemusnahansurat WHERE id = '.$id;
	
			$rs = $this->db->query($SQL);
			if ($rs) {
				$data['success'] = true;
			}
			else{
				$data['success'] = false;
				$data['message'] = 'Gagal Melakukan Delete Data';
			}
		}
		else{
			$data['success'] = false;
			$data['message'] = 'Invalid Form Type';
		}
	
		echo json_encode($data);
	}
	//  ============================ Pemusnahan surat ============================



	//  ============================ Laporan section ============================

	public function LaporanSuratmasuk()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());
		
		$TglAwal = $this->input->post('TglAwal');
		$TglAkhir = $this->input->post('TglAkhir');

		$SQL = "
			SELECT 
				a.NoAgenda, 
				a.NomorSurat, 
				a.AsalSurat, 
				a.TanggalSurat, 
				a.TglPelaksanaanSurat, 
				a.JenisSurat,
				c.NamaKlasifikasi,
				a.IsiSurat,
				b.WrapOutDate,
				b.Keterangan,
				b.CreatedBy,
				CASE WHEN b.WrapOutDate IS NOT NULL THEN 'Y' ELSE 'N' END WO
			FROM datasurat a
			LEFT JOIN pemusnahansurat b on a.NoAgenda = b.BaseRef
			LEFT JOIN tklasifikasi c on a.KodeKlasifikasiSurat = c.id
			WHERE a.JenisSurat='Masuk' AND a.TanggalSurat BETWEEN '$TglAwal' AND '$TglAkhir'
		";
	
		$rs = $this->db->query($SQL);
		if($rs){
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		echo json_encode($data);
	}

	public function LaporanSuratkeluar()
	{
		$data = array('success' => false ,'message'=>array(),'data'=>array());
		
		$TglAwal = $this->input->post('TglAwal');
		$TglAkhir = $this->input->post('TglAkhir');

		$SQL = "
			SELECT 
				a.NoAgenda, 
				a.NomorSurat, 
				a.AsalSurat, 
				a.TanggalSurat, 
				a.TglPelaksanaanSurat, 
				a.JenisSurat,
				c.NamaKlasifikasi,
				a.IsiSurat,
				b.WrapOutDate,
				b.Keterangan,
				b.CreatedBy,
				CASE WHEN b.WrapOutDate IS NOT NULL THEN 'Y' ELSE 'N' END WO
			FROM datasurat a
			LEFT JOIN pemusnahansurat b on a.NoAgenda = b.BaseRef
			LEFT JOIN tklasifikasi c on a.KodeKlasifikasiSurat = c.id
			WHERE a.JenisSurat='Keluar' AND a.TanggalSurat BETWEEN '$TglAwal' AND '$TglAkhir'
		";
	
		$rs = $this->db->query($SQL);
		if($rs){
			$data['success'] = true;
			$data['data'] = $rs->result();
		}
		echo json_encode($data);
	}

	//  ============================ Laporan section ============================


	//  ============================ GENERAL ============================
	public function Getindex()
	{
		$data = array('success' => false ,'message'=>array(),'Nomor' => '');

		$Kolom = $this->input->post('Kolom');
		$Table = $this->input->post('Table');
		$Prefix = $this->input->post('Prefix');

		$SQL = "SELECT RIGHT(MAX(".$Kolom."),5)  AS Total FROM " . $Table . " WHERE LEFT(" . $Kolom . ", LENGTH('".$Prefix."')) = '".$Prefix."'";

		// var_dump($SQL);
		$rs = $this->db->query($SQL);

		$temp = $rs->row()->Total + 1;

		$nomor = $Prefix.str_pad($temp, 5,"0",STR_PAD_LEFT);
		if ($nomor != '') {
			$data['success'] = true;
			$data['nomor'] = $nomor;
		}
		echo json_encode($data);
	}
	//  ============================ GENERAL ============================
}