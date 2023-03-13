<?php
declare (strict_types = 1);

namespace App\Http\Validator;

interface ValidatorInterface {

	public static function validate($data): bool;
}