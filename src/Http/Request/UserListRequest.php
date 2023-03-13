<?php

namespace App\Http\Request;



use App\Http\Validator\StringValidator;

class UserListRequest extends ApiRequest
{
	protected array $requiredKeys = [];

	protected array $validators = [
		'first_name' => StringValidator::class,
		'last_name' => StringValidator::class,
		'email' => StringValidator::class
	];

	public function __construct(array $params)
	{
		$this->allowEmpty = true;
		parent::__construct($params);
	}
}
