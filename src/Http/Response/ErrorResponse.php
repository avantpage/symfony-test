<?php
declare (strict_types=1);

namespace App\Http\Response;

use App\Http\Error\ApiError;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponse extends ApiResponse
{

	public function __construct(string $message = null, int $code = null, string $internalCode = null)
	{
		$code = $code ?: Response::HTTP_INTERNAL_SERVER_ERROR;
		$internalCode = $internalCode ?: ApiError::CODE_INTERNAL_ERROR;
		$message = $message ?: ApiError::$descriptions[ApiError::CODE_INTERNAL_ERROR];
		parent::__construct($code, $internalCode, $message);
	}
}