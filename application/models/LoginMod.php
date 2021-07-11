<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginMod extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function Validate_username($username)
    {
        $this->db->where('username',$username);
        return $this->db->get('users');
    }
    function Validate_Login($username,$Password)
    {
        $this->db->where('id',$username);
        $this->db->where('password',$Password);
        return $this->db->get('users');
    }
    function GetUser($id)
    {
        $sql = "
            SELECT 
                a.id,a.username,a.nama,a.email,c.rolename
            FROM users a
            LEFT JOIN userrole b on a.id = b.userid
            LEFT JOIN roles c on c.id = b.roleid
        ";
        if ($id != "") {
            $sql .= "where a.id = $id";
        }
        return $this->db->query($sql);
    }
}
