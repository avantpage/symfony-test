<?php

namespace App\Http\Dto;

class UserDto
{
	public function __construct(
		public string $id,
		public string $firstName,
		public string $lastName,
		public string $email,
		public bool $isActive,
		public ?string $createdAt
	) {
	}
}
