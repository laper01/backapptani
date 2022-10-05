<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter
{
	protected static $response = [
		'meta' => [
			'code' => 200,
			'status' => 'success',
			'message' => null,
		],
		'data' => null,
	];

	public static function response($status = true, $data = null, $code = null)
	{
		self::$response['meta']['status'] = $status ? 'success' : 'error';
		self::$response['meta']['code'] = $code;
		self::$response['meta']['message'] = Response::$statusTexts[$code];
		self::$response['data'] = $data;

		return response()->json(self::$response, self::$response['meta']['code']);
	}
}
