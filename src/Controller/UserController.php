<?php

namespace App\Controller;

use Throwable;
use App\Model\Entity\User;
use App\Handler\UserHandler;
use Psr\Log\LoggerInterface;
use App\Http\Filter\UserFilter;
use App\Http\Request\ApiRequest;
use App\Http\Response\ApiResponse;
use App\Http\Response\ErrorResponse;
use App\Http\Request\UserListRequest;
use App\Http\Request\UserStoreRequest;
use App\Http\Request\UserCreateRequest;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
	private UserHandler $userHandler;
	private LoggerInterface $logger;

	public function __construct(
		UserHandler $userHandler,  
		LoggerInterface $logger
	) {
		$this->userHandler = $userHandler;
		$this->logger = $logger;
	}

	/**
	 * @Rest\Get("", name="user_list")
	 */
	public function getList(Request $request): ApiResponse
	{
		try {
			do {				
				$requestObj = new UserListRequest($request->query->all());
				
				if (!$requestObj->isValid()) {
					$response = $requestObj->getError();
					break;
				}
				
				$response = $this->userHandler->processList($requestObj->getParams());
			} while (0);
		} catch (Throwable $thr) {
			$response = new ErrorResponse($thr->getMessage());
			$this->logger->error($response);
		}

		return $response;
	}

	/**
	 * @Rest\Post("", name="user_create")
	 */
	public function create(Request $request): ApiResponse
	{
		try {
			do {
				$requestObj = new UserCreateRequest($request->request->all());				
				if (!$requestObj->isValid()) {
					$response = $requestObj->getError();
					break;
				}
				$response = $this->userHandler->processCreate($requestObj->getParams());
			} while (0);
		} catch (Throwable $thr) {
			$response = new ErrorResponse($thr->getMessage());
			$this->logger->error($response);
		}

		return $response;
	}


	/**
	 * @Rest\Get("/{id}", name="user_retrieve")
	 */
	public function retrieve(Request $request, string $id): ApiResponse
	{
		try {
			do {
				$requestObj = new ApiRequest($request->query->all());
				if (!$requestObj->isValid()) {
					$response = $requestObj->getError();
					break;
				}				
				$response = $this->userHandler->processRetrieve($id);
			} while (0);
		} catch (Throwable $thr) {
			$response = new ErrorResponse($thr->getMessage());
			$this->logger->error($response);
		}

		return $response;
	}

	/**
	 * @Rest\Post("/{id}", name="user_store")
	 */
	public function store(Request $request, string $id): ApiResponse
	{
		try {
			do {
				$requestObj = new UserStoreRequest($request->query->all());
				if (!$requestObj->isValid()) {
					$response = $requestObj->getError();
					break;
				}
				$response = $this->userHandler->proccessStore($requestObj->getParams(), $id);
			} while (0);
		} catch (Throwable $thr) {
			$response = new ErrorResponse($thr->getMessage());
			$this->logger->error($response);
		}

		return $response;
	}


	/**
	 * @Rest\Delete("/{id}", name="user_delete")
	 */
	public function delete(string $id): ApiResponse
	{
		try {
			do {
				$response = $this->userHandler->proccessDelete($id);
			} while (0);
		} catch (Throwable $thr) {
			$response = new ErrorResponse($thr->getMessage());
			$this->logger->error($response);
		}

		return $response;
	}
}