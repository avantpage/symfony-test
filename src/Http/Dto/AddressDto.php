<?php

namespace App\Http\Dto;

class AddressDto
{
	public function __construct(
		public string $street,
		public int $number,
		public string $city,
		public string $country,
		public int $zip_number
	) {
	}
}
