<?php

namespace App\Handler;

use App\Http\Error\ApiError;
use App\Http\Response\ApiResponse;
use App\Http\Response\ErrorResponse;
use App\Http\Response\UserResponse;
use App\Model\Entity\Address;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserHandler
{

	private EntityManagerInterface $em;
	private UserRepository $userRepo;

	public function __construct(
		UserRepository         $userRepo,
		EntityManagerInterface $em,
	)
	{
		$this->em = $em;
		$this->userRepo = $userRepo;
	}

	public function processList(array $dataRequest): ApiResponse
	{
		return new UserResponse(
			[
				'entities' => $this->userRepo->getList($dataRequest),
			]
		);
	}

	public function processCreate(array $dataRequest): ApiResponse
	{
		$user = $this->userRepo->findByEmail($dataRequest['email']);
		if ($user) {
			return new ErrorResponse(ApiError::$descriptions[ApiError::CODE_EXISTS_EMAIL], Response::HTTP_NOT_FOUND, ApiError::CODE_EXISTS_EMAIL);
		}

		$address = new Address();
		$address
			->setStreet(strip_tags($dataRequest['street']))
			->setNumber(strip_tags($dataRequest['number']))
			->setCity(strip_tags($dataRequest['city']))
			->setCountry(strip_tags($dataRequest['coutry']))
			->setZipNumber(strip_tags($dataRequest['zip_number']));
		
		$user = new User();
		$user
			->setFirstName(strip_tags($dataRequest['first_name']))
			->setLastName(strip_tags($dataRequest['last_name']))
			->setEmail(strip_tags($dataRequest['email']))
			->setAddress($address);
			
		$this->em->persist($user);
		$this->em->flush();

		$this->em->persist($address);
		$this->em->flush();
		

		return new UserResponse(
			[
				'entities' => [$user]
			]
		);
	}

	public function processRetrieve(string $id): ApiResponse
	{
		$user = $this->userRepo->find($id);
		if (!$user) {
			return new ErrorResponse(ApiError::$descriptions[ApiError::CODE_USER_NOT_FOUND], Response::HTTP_NOT_FOUND, ApiError::CODE_USER_NOT_FOUND);
		}
		return new UserResponse(
			[
				'entities' => [$user],
			]
		);
	}

	public function proccessStore(array $dataRequest, string $id): ApiResponse
	{
		$user = $this->userRepo->find($id);
		
		if (!$user) {
			return new ErrorResponse(ApiError::$descriptions[ApiError::CODE_USER_NOT_FOUND], Response::HTTP_NOT_FOUND, ApiError::CODE_USER_NOT_FOUND);
		}

		if ($user->getEmail() === $dataRequest['email']) {
			return new ErrorResponse(ApiError::$descriptions[ApiError::CODE_EXISTS_EMAIL], Response::HTTP_NOT_FOUND, ApiError::CODE_EXISTS_EMAIL);
		}

		$address = $user->getAddress()
			->setStreet(strip_tags($dataRequest['street']))
			->setNumber(strip_tags($dataRequest['number']))
			->setCity(strip_tags($dataRequest['city']))
			->setCountry(strip_tags($dataRequest['coutry']))
			->setZipNumber(strip_tags($dataRequest['zip_number']));

		$user
			->setFirstName(strip_tags($dataRequest['first_name']))
			->setLastName(strip_tags($dataRequest['last_name']))
			->setEmail(strip_tags($dataRequest['email']))
			->setAddress($address);
			
		$this->em->persist($user);
		$this->em->flush();
		
		return new UserResponse(
			[
				'entities' => [$user]
			]
		);
	}

	public function proccessDelete(string $id): ApiResponse
	{
		$user = $this->userRepo->find($id);
		if (!$user) {
			return new ErrorResponse(ApiError::$descriptions[ApiError::CODE_USER_NOT_FOUND], Response::HTTP_NOT_FOUND, ApiError::CODE_USER_NOT_FOUND);
		}

		$this->em->remove($user);
		$this->em->flush();
		
		return new ApiResponse(Response::HTTP_OK, Response::HTTP_OK, 'User deleted');
	}
}
