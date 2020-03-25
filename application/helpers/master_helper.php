<?php
defined('BASEPATH') or exit('No direct script access allowed');

function r($value)
{
	$search = array("'", '"');
	$replace = array(" ");
	if (is_null($value)) {
		return "";
	}
	return str_replace($search, $replace, $value);
}

// --------------- helper tanggal
function tgl_indo($tanggal)
{
	$bulan = array(
		1 =>
		'Jan',
		'Feb',
		'Mar',
		'Apr',
		'Mei',
		'Jun',
		'Jul',
		'Agt',
		'Sep',
		'Okt',
		'Nov',
		'Des'
	);
	$tgl = date('d-m-Y', strtotime($tanggal));
	$pecahkan = explode('-', $tgl);
	$waktu = array(
		'tanggal' => $pecahkan[2],
		'bulan' => $bulan[(int) $pecahkan[1]],
		'tahun' => $pecahkan[0],
		'jam' => date('H:i', strtotime($tanggal))
	);
	return $waktu;
}
function date_valid($params, $value)
{
	$params = underline($params);
	$check = strtotime($value);
	if ($check != false) {
		return date("Y-m-d H:i:s", strtotime($value));
	} else
		error("$params tidak sesuai dengan ketentuan YYYY-mm-dd");
}

function date_now($params, $value)
{
	$check = strtotime($value);
	$params = underline($params);
	if ($check != false) {
		if ($check > strtotime(date("Y-m-d")))
			return date("Y-m-d H:i:s", $check);
		else {
			error("waktu $params tidak bisa menggunakan waktu lampau");
		}
	} else
		error("$params tidak sesuai dengan ketentuan YYYY-mm-dd");
}

// --------------- helper inputan
function post($params, $constrains = null)
{
	if (isset($_POST[$params])) {
		$value = $_POST[$params];
		if (!is_null($constrains) && !empty($constrains)) {
			if (strpos($constrains, 'allow_html') !== 0)
				$value = strip_tags(r($value));
			$constrains = explode('|', $constrains);
			foreach ($constrains as $method) {
				if (strpos($method, ':')) {
					$tmp = explode(':', $method);
					$value = $tmp[0]($params, $value, $tmp[1]);
				} else
					$value = $method($params, $value);
			}
			return $value;
		} else
			return strip_tags(r($value));
	} else {
		$params = underline($params);
		error("data $params tidak ditemukan.");
	}
}

// --------------- constrains inputan
function enum($params, $value, $check)
{
	$check = explode("&", $check);
	if (in_array($value, $check))
		return $value;
	else
		error("data $params tidak sesuai ketentuan");
}

function same($params, $value, $check)
{
	if (post($check) !== $value) {
		$params = underline($params);
		$check = underline($check);
		error("$params tidak cocok dengan $check");
	}
	return $value;
}
function greater($params, $value, $check)
{
	$a = post($check);
	$b = $value;
	if (strtotime($a) != false || strtotime($b) != false) {
		$a = strtotime($a);
		$b = strtotime($b);
	}
	if ($b <= $a) {
		$params = underline($params);
		$check = underline($check);
		error("$params harus lebih besar dari $check");
	}
	return $value;
}

function max_char($params, $value, $check)
{
	if (strlen($value) > $check)
		error("$params tidak boleh lebih dari $check karakter");
	return $value;
}

function min_char($params, $value, $check)
{
	if (strlen($value) < $check)
		error("$params tidak boleh kurang dari $check karakter");
	return $value;
}

function max_value($params, $value, $check)
{
	if (is_numeric($value) && is_numeric($check)) {
		if ($value > $check)
			error("$params tidak boleh lebih dari $check");
	} else
		error("$params harus berisi angka");
	return $value;
}

function min_value($params, $value, $check)
{
	if (is_numeric($value) && is_numeric($check)) {
		if ($value < $check)
			error("$params tidak boleh kurang dari $check");
	} else
		error("$params harus berisi angka");
	return $value;
}

function unique($params, $value, $table)
{
	$found = DB_MODEL::find($table, array($params => $value));
	if (!$found->error)
		error("$params sudah digunakan");
	return $value;
}

function email($params, $value)
{
	if (!filter_var($value, FILTER_VALIDATE_EMAIL))
		error("$params tidak valid sebagai email");
	return $value;
}

function required($params, $value)
{
	if (empty(post($params))) {
		$params = underline($params);
		error("data input $params tidak boleh kosong");
	}
	return $value;
}

function numberic($params, $value)
{
	if (!is_numeric($value)) {
		error("$params harus berupa angka");
	}
	return $value;
}
function allow_html($params, $value)
{
	return $value;
}

function underline($params)
{
	return str_replace("_", " ", $params);
}

// --------------- helper message json
function success($msg, $data)
{
	header('Content-Type: application/json');
	echo json_encode(
		array(
			"error" => false,
			"message" => $msg,
			"data" => $data
		)
	);
	exit;
}

function error($msg)
{
	header('Content-Type: application/json');
	echo json_encode(
		array(
			"error" => true,
			"message" => $msg,
			"data" => []
		)
	);
	exit;
}

// --------------- helper model
function true($data)
{
	return (object) array(
		"error" => false,
		"data" => $data
	);
}

function false()
{
	return (object) array(
		"error" => true
	);
}
