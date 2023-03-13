<?php
declare (strict_types = 1);


namespace App\Http\Validator;

use App\Http\Error\ApiError;

class BooleanValidator extends PatternValidator implements ValidatorInterface {
	private const ALLOWED_DATA = ['TRUE', 'true', 'FALSE', 'false', '1', '0'];

	public static function validate($data, $field = null): bool {
		if (!is_bool($data) && !in_array($data, self::ALLOWED_DATA)) {
			static::$internalCode = ApiError::CODE_INVALID_BOOL_VALUE;
			static::$reasonFailed = ApiError::$descriptions[ApiError::CODE_INVALID_BOOL_VALUE];
			return false;
		}
		return true;
	}
}