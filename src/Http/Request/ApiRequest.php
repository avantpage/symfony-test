<?php

	declare(strict_types=1);

namespace App\Http\Request;

use App\Http\Error\ApiError;
use App\Http\Response\ErrorResponse;
use App\Http\Validator\PatternValidator;
use Symfony\Component\HttpFoundation\Response;

	class ApiRequest
	{
		protected array $requiredKeys = [];
		protected array $validators = [];
		protected bool $isValid;
		protected bool $allowEmpty = true;
		protected ErrorResponse $error;
		protected array $params = [];

		public function __construct(array $params = [])
		{
			$this->params = $params;
			$this->isValid = true;
			$this->validate();
		}

		protected function validate(): bool
		{
			$this->error = new ErrorResponse();
			$dataSent = array_keys($this->params);
			$requiredKeys = array_keys($this->requiredKeys);

			if (!count($this->params) && !$this->allowEmpty) {
				$this->isValid = false;
				$this->error->setStatusCode(Response::HTTP_BAD_REQUEST);
				$this->error->updateInternalCode(ApiError::CODE_FORBIDEN_EMPTY_REQUEST);
				$this->error->updateMessage(ApiError::$descriptions[ApiError::CODE_FORBIDEN_EMPTY_REQUEST]);

				return false;
			}

			foreach ($requiredKeys as $requiredKey) {
				if (!in_array($requiredKey, $dataSent)) {
					$this->isValid = false;
					$errorCode = $this->requiredKeys[$requiredKey];
					$this->error->setStatusCode(Response::HTTP_BAD_REQUEST);
					$this->error->updateInternalCode($errorCode);
					$this->error->updateMessage(ApiError::$descriptions[$errorCode]);

					return false;
				}
			}

			foreach ($this->params as $field => $value) {
				if (!empty($this->validators) && !isset($this->validators[$field])) {
					$this->isValid = false;
					$this->error->setStatusCode(Response::HTTP_BAD_REQUEST);
					$this->error->updateInternalCode(ApiError::CODE_UNRECOGNIZED_PARAMETER);
					$this->error->updateMessage(ApiError::$descriptions[ApiError::CODE_UNRECOGNIZED_PARAMETER]);
					$this->error->updateInvalidParam($field);

					return false;
				}

				if (!empty($this->validators) && !empty($this->validators[$field])) {
					/** @var PatternValidator $validator */
					$validator = $this->validators[$field];
					if (!$validator::validate($value, $field)) {
						$this->isValid = false;
						$this->error->setStatusCode(Response::HTTP_BAD_REQUEST);
						$this->error->updateInternalCode($validator::getFailedInternalCode());
						$this->error->updateMessage($validator::getReasonFailed());

						return false;
					}
				}
			}

			return true;
		}

		public function getError(): ErrorResponse
		{
			return $this->error;
		}

		public function isValid(): bool
		{
			return $this->isValid;
		}

		public function getParams(): array
		{
			return $this->params;
		}
	}
