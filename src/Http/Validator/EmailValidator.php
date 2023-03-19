<?php

declare(strict_types=1);

namespace App\Http\Validator;


use App\Http\Error\ApiError;
use Doctrine\ORM\EntityManagerInterface;

class EmailValidator extends PatternValidator implements ValidatorInterface
{
	public static string $pattern = '/^\w+@gmail\.com$/i';

	public static function validate($data, $field = null): bool
	{
		if (!filter_var($data, FILTER_VALIDATE_EMAIL) || !preg_match(self::$pattern, $data) > 0) {
			static::$internalCode = ApiError::CODE_INVALID_EMAIL;
			static::$reasonFailed = ApiError::$descriptions[ApiError::CODE_INVALID_EMAIL] . ": $field";
			return false;
		}
		
		return true;
	}
}
