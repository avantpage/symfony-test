<?php

namespace App\Http\Dto;

use App\Http\Dto\AddressDto;
use App\Model\Entity\Address;

class UserDto
{
	public function __construct(
		public string $id,
		public string $firstName,
		public string $lastName,
		public string $email,
		public bool $isActive,
		public ?string $createdAt,
		public ?AddressDto $address
	) {
	}
}
