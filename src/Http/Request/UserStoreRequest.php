<?php

namespace App\Http\Request;

use App\Http\Error\ApiError;
use App\Http\Validator\EmailValidator;
use App\Http\Validator\StringValidator;

class UserStoreRequest extends ApiRequest
{
	protected array $requiredKeys = [];

	protected array $validators = [
		'first_name' => StringValidator::class,
		'last_name' => StringValidator::class,
		'email' => EmailValidator::class,
		'street' => StringValidator::class,
		'number' => StringValidator::class,
		'city' => StringValidator::class,
		'coutry' => StringValidator::class,
		'zip_number' => StringValidator::class,
	];

	public function __construct(array $params)
	{
		$this->allowEmpty = false;
		parent::__construct($params);
	}
}
