<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends CI_Controller
{
	public function index()
	{
		$telp = post('telepon', 'required');
		$data = Auth::login('zero', "telp = '$telp' OR email = '$telp'", post('password', 'required'));
		if ($data->role !== 'agent')
			error("you aren't zerolim's agent.");
		if ($data->status == 'activated') {
			$data->token = AUTHORIZATION::generateToken($data);
			success("Welcome to zerolim's system", $data);
		} else
			error("sorry, this account isn't activate");
	}
}
