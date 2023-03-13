<?php

declare(strict_types=1);

namespace App\Http\Response;

use App\Http\Dto\PaginationDto;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends Response
{

	private const KEY_CODE = 'api_code';
	private const KEY_INVALID_PARAM = 'invalid_param';
	private const KEY_MESSAGE = 'message';
	private const KEY_DATA = 'data';
	private const KEY_DATA_PAGINATION = 'pagination';

	protected array $dataResponse = [];
	protected bool $enablePagination = false;
	protected PaginationDto $paginationDto;

	/**
	 * ApiResponse constructor.
	 */
	public function __construct(int $code = null, string $internalCode = null, string $message = null, $data = null)
	{
		$code = $code ?: Response::HTTP_OK;
		$this->statusCode = $code;

		if ($message || $internalCode || null !== $data) {
			if ($internalCode) {
				$this->updateInternalCode($internalCode);
			}
			if ($message) {
				$this->updateMessage($message);
			}

			if (null !== $data) {
				$this->updateData($data);
			}
			$this->content = json_encode($this->dataResponse);
		}
		parent::__construct($this->content, $this->statusCode);
	}

	public function marshall($data = null)
	{
		return $data;
	}

	public function updateInternalCode(string $internalCode): void
	{
		$this->dataResponse[self::KEY_CODE] = $internalCode;
		$this->content = json_encode($this->dataResponse);
	}

	public function updateMessage(string $message): void
	{
		$this->dataResponse[self::KEY_MESSAGE] = $message;
		$this->content = json_encode($this->dataResponse);
	}

	public function updateInvalidParam(string $field): void
	{
		$this->dataResponse[self::KEY_INVALID_PARAM] = $field;
		$this->content = json_encode($this->dataResponse);
	}

	/**
	 * @param $data
	 */
	public function updateData($data): void
	{
		$this->dataResponse[self::KEY_DATA] = $this->marshall($data);
		$this->content = json_encode($this->dataResponse);
	}

	private function preAppendPagination()
	{
		if ($this->enablePagination) {
			$this->dataResponse[self::KEY_DATA_PAGINATION] = $this->paginationDto;
			$this->content = json_encode($this->dataResponse);
		}
	}

	public function getDataResponse(): array
	{
		return $this->dataResponse;
	}

	public function setPaginationDto(PaginationDto $paginationDto): void
	{
		$this->paginationDto = $paginationDto;
		$this->preAppendPagination();
	}
}
