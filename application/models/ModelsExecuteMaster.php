<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class ModelsExecuteMaster extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}
	function ExecUpdate($data,$where,$table)
	{
		$this->db->trans_begin();
        $this->db->where($where);
        if ($this->db->trans_status() === FALSE)
		{
		        $this->db->trans_rollback();
		}
		else
		{
		        $this->db->trans_commit();
		        return $this->db->update($table,$data);
		}
	}
	function ExecInsert($data,$table)
	{
		$this->db->trans_begin();
		
		if ($this->db->trans_status() === FALSE)
		{
		        $this->db->trans_rollback();
		}
		else
		{
		        $this->db->trans_commit();
		        return $this->db->insert($table,$data);
		}
	}
	function FindData($where,$table){
		$this->db->where($where);
		return $this->db->get($table);
	}
	function FindDataWithLike($where,$table){
		$this->db->like($where,'both');
		return $this->db->get($table);
	}
	function GetData($table)
	{
		return $this->db->get($table);
	}
	function GetMax($table,$field)
	{
		// 1 : alredy, 0 : first input
		$this->db->select_max($field);
		return $this->db->get($table);
	}
	function DeleteData($where,$table)
	{
		return $this->db->delete($table,$where);
	}
	function GetSaldoStock($kodestock){
		$data = '
				SELECT a.kodestok,SUM(COALESCE(msd.qty,0)) - SUM(COALESCE(pp.qty,0)) - SUM(COALESCE(pj.qty,0)) saldo FROM masterstok a
				LEFT JOIN mutasistokdetail msd on a.id = msd.stokid
				LEFT JOIN post_product pp on a.id = pp.stockid
				LEFT JOIN penjualan pj on pp.id = pj.productid AND pj.statustransaksi = 1
				WHERE msd.canceleddate is null AND a.id = '.$kodestock.'
				GROUP BY a.kodestok
			';
		return $this->db->query($data);
	}
	function ClearImage()
	{
		$data = '
				DELETE FROM imagetable
				where used = 0
			';
		return $this->db->query($data);
	}
	public function CallSP($namasp,$param1)
	{
		$data = 'CALL '.$namasp.'('.$param1.')';
		return $this->db->query($data);
	}
}