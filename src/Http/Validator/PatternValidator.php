<?php
declare (strict_types = 1);

namespace App\Http\Validator;

class PatternValidator {

	protected static string $pattern = '';
	protected static string $reasonFailed = '';
	protected static string $internalCode = '';


	public static function validate($data, $field): bool {
		if (empty($data)) {
			return false;
		}
		return true;
	}

	public static function getReasonFailed(): string {
		return static::$reasonFailed;
	}

	public static function getFailedInternalCode(): string {
		return static::$internalCode;
	}
}