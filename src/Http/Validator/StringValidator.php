<?php

declare(strict_types=1);

namespace App\Http\Validator;


use App\Http\Error\ApiError;

class StringValidator extends PatternValidator implements ValidatorInterface
{
	public static string $pattern = '/^[\p{L}\p{N}\s\.\-\_\+\:\&\(\)]+$/i';


	public static function validate($data, $field = null): bool
	{
		if (empty($data) || !preg_match(self::$pattern, $data)) {
			static::$internalCode = ApiError::CODE_INVALID_STRING;
			static::$reasonFailed = ApiError::$descriptions[ApiError::CODE_INVALID_STRING] . ": $field";

			return false;
		}

		return true;
	}
}
