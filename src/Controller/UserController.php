<?php

namespace App\Controller;

use App\Handler\UserHandler;
use App\Http\Request\ApiRequest;
use App\Http\Request\UserCreateRequest;
use App\Http\Request\UserListRequest;
use App\Http\Response\ApiResponse;
use App\Http\Response\ErrorResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
	private UserHandler $userHandler;

	public function __construct(UserHandler $userHandler)
	{
		$this->userHandler = $userHandler;
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
		}

		return $response;
	}
}