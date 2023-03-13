<?php

declare(strict_types=1);

namespace App\Http\Response;

use App\Http\Dto\UserDto;
use App\Model\Entity\User;

class UserResponse extends ApiResponse
{
	public function __construct($data = null)
	{
		parent::__construct();
		$this->updateData($data);
	}

	public function marshall($data = null): array
	{
		$list = $data['entities'] ?? [];
		$result = [];

		/** @var User $entity */
		foreach ($list as $entity) {
			$result[] = $this->createDto($entity);
		}
		$this->raw = $result;

		return $result;
	}

	private function createDto(User $entity): UserDto
	{
		return new UserDto(
			$entity->getId(),
			$entity->getFirstName(),
			$entity->getLastName(),
			$entity->getEmail(),
			$entity->isIsActive(),
			$entity->getCreatedAt()->format('Y-m-d'),
		);
	}
}
