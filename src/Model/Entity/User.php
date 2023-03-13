<?php

namespace App\Model\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Table(name="user",
 *    indexes={
 *           @ORM\Index(name="", columns={"created_at"}),
 *  })
 * @ORM\Entity
 */
class User
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string", length=36, unique=true)
	 */
	private string $id;

	/**
	 * @var string
	 * @ORM\Column(name="first_name", type="string", length=30, nullable=false)
	 */
	private string $firstName;

	/**
	 * @var string
	 * @ORM\Column(name="last_name", type="string", length=50, nullable=false)
	 */
	private string $lastName;

	/**
	 * @var string
	 * @ORM\Column(name="email",type="string", length=64, nullable=false)
	 */
	private string $email;

	/**
	 * @var bool
	 * @ORM\Column(name="is_active",type="boolean", nullable=false)
	 */
	private bool $isActive;

	/**
	 * @ORM\Column(name="created_at", type="datetime", nullable=false)
	 */
	private DateTime $createdAt;

	public function __construct()
	{
		$this->id = Uuid::v4()->__toString();
		$this->isActive = false;
		$this->createdAt = new DateTime('now');
	}

	public function __toString()
	{
		return "$this->firstName $this->lastName";
	}

	public function getId(): ?string
	{
		return $this->id;
	}

	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): self
	{
		$this->firstName = $firstName;

		return $this;
	}

	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): self
	{
		$this->lastName = $lastName;

		return $this;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	public function isIsActive(): ?bool
	{
		return $this->isActive;
	}

	public function setIsActive(bool $isActive): self
	{
		$this->isActive = $isActive;

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
