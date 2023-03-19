<?php

namespace App\Model\Entity;

use DateTime;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="address",
 *    indexes={
 *           @ORM\Index(name="", columns={"created_at"}),
 *  })
 * @ORM\Entity
 */
class Address
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string", length=36, unique=true)
	 */
	private string $id;

	/**
	 * @var string
	 * @ORM\Column(name="street", type="string", length=100, nullable=false)
	 */
	private string $street;

	/**
	 * @var string
	 * @ORM\Column(name="number", type="integer", length=10, nullable=false)
	 */
	private string $number;

	/**
	 * @var string
	 * @ORM\Column(name="city",type="string", length=64, nullable=false)
	 */
	private string $city;

	/**
	 * @var bool
	 * @ORM\Column(name="country",type="string", length=50, nullable=false)
	 */
	private bool $country;

	/**
	 * @var bool
	 * @ORM\Column(name="zip_number", type="integer", nullable=false)
	 */
	private bool $zip_number;

	/**
	 * @ORM\Column(name="created_at", type="datetime", nullable=false)
	 */
	private DateTime $createdAt;

	public function __construct()
	{
		$this->id = Uuid::v4()->__toString();
		$this->createdAt = new DateTime('now');
	}


	public function getId(): ?int
	{
		return $this->id;
	}

	public function getStreet(): ?string
	{
		return $this->street;
	}

	public function setStreet(string $street): self
	{
		$this->street = $street;

		return $this;
	}

	public function getNumber(): ?int
	{
		return $this->number;
	}

	public function setNumber(int $number): self
	{
		$this->number = $number;

		return $this;
	}

	public function getCity(): ?string
	{
		return $this->city;
	}

	public function setCity(string $city): self
	{
		$this->city = $city;

		return $this;
	}

	public function getCountry(): ?string
	{
		return $this->country;
	}

	public function setCountry(string $country): self
	{
		$this->country = $country;

		return $this;
	}

	public function getZipNumber(): ?int
	{
		return $this->zip_number;
	}

	public function setZipNumber(int $zip_number): self
	{
		$this->zip_number = $zip_number;

		return $this;
	}
	
	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTimeInterface $createdAt): self
	{
		$this->createdAt = $createdAt;

		return $this;
	}

}
