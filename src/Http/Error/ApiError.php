<?php

namespace App\Http\Error;

use Symfony\Component\HttpFoundation\Response;

class ApiError
{
	// INVALID VALUES
	public const CODE_INVALID_STRING = 'invalid_string';
	public const CODE_INVALID_BOOL_VALUE = 'invalid_bool_value';

	// MISSING FIELDS
	public const CODE_FIRST_NAME_MISSING = 'missing_first_name';
	public const CODE_LAST_NAME_MISSING = 'missing_last_name';
	public const CODE_EMAIL_MISSING = 'missing_email';

	// NOT FOUND FIELDS
	public const CODE_USER_NOT_FOUND = 'user_not_found';

	// PERMISSIONS FIELDS
	public const CODE_FORBIDEN_EMPTY_REQUEST = 'forbiden_empty_request';

	// LOGIC ERRORS

	// EXCEPTIONS
	public const CODE_ENTITY_EXISTS = 'entity_already_exits';
	public const CODE_UNRECOGNIZED_PARAMETER = 'unrecognized_parameter';
	public const CODE_INTERNAL_ERROR = 'internal_error';
	/**
	 * @var array
	 */
	public static array $descriptions = [
		self::CODE_INVALID_STRING => 'The string is invalid.',
		self::CODE_INVALID_BOOL_VALUE => 'The bool value is invalid.',
		self::CODE_INTERNAL_ERROR => 'Opps something went wrong.',
		self::CODE_USER_NOT_FOUND => 'User not found.',
		self::CODE_ENTITY_EXISTS => 'The entity already exists.',
		self::CODE_FORBIDEN_EMPTY_REQUEST => 'Empty request is not allowed.',
		self::CODE_UNRECOGNIZED_PARAMETER => 'Parameter not recognized.',
		Response::HTTP_INTERNAL_SERVER_ERROR => 'Unexpected error.',
	];
}
