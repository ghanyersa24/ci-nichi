<?php

class AUTHORIZATION
{
	public static function validateTimestamp($token)
	{
		$CI = &get_instance();
		$token = self::validateToken($token);
		if ($token != false && (now() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
			return $token;
		}
		return false;
	}

	public static function validateToken($token)
	{
		$CI = &get_instance();
		return JWT::decode($token, $CI->config->item('jwt_key'));
	}

	public static function generateToken($data)
	{
		$CI = &get_instance();
		$data->timestamp = now();
		return JWT::encode($data, $CI->config->item('jwt_key'));
	}

	// ------------------------Ghany -------------------------
	public static function User()
	{
		$CI = &get_instance();
		$headers = $CI->input->request_headers();
		if (array_key_exists('zero-token', $headers) && !empty($headers['zero-token'])) {
			$decodedToken = AUTHORIZATION::validateToken($headers['zero-token']);
			if ($decodedToken != false) {
				return $decodedToken;
			} else
				error("Unauthorize.");
		} else
			error("Unauthorize.");
	}

	public static function Customer()
	{
		$CI = &get_instance();
		$headers = $CI->input->request_headers();
		if (array_key_exists('zero-token', $headers) && !empty($headers['zero-token'])) {
			$decodedToken = self::validateTimestamp($headers['zero-token']);
			if ($decodedToken != false) {
				return $decodedToken;
			} else
				error("Unauthorize.");
		} else
			error("Unauthorize.");
	}
}
