<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
	public function index()
	{
		$data = array(
			"username" => post('username', 'required|min_char:12|unique:customer'),
			"password" => password_hash(post('password', 'required'), PASSWORD_DEFAULT, array('cost' => 10)),
		);
		post('password_confirmation', 'same:password');
		$do = DB_MODEL::insert('customer', $data);
		if (!$do->error) {
			success("data berhasil ditambahkan", $do->data);
		} else {
			error("data gagal ditambahkan");
		}
	}
}
