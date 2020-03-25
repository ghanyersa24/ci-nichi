<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{
	protected $table = "customer";
	public function get($id = null)
	{
		if (is_null($id)) {
			$do = DB_MODEL::where($this->table, array('status' => 'activated'));
		} else {
			$do = DB_MODEL::find($this->table, array("id" => $id));
		}

		if (!$do->error) {
			success("data berhasil ditemukan", $do->data);
		} else {
			error("data gagal ditemukan");
		}
	}

	public function delete()
	{
		$where = array(
			"id" => post('id', 'required')
		);

		$do = DB_MODEL::update($this->table, $where, array('status' => 'deactivated'));
		if (!$do->error) {
			success("data berhasil dihapus", $do->data);
		} else {
			error("datat gagal dihapus");
		}
	}
}
