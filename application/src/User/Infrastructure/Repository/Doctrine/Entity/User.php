<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Doctrine\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping;
use Symfony\Component\Validator\Constraints;

/**
 * @Mapping\Entity(repositoryClass="XIP\User\Infrastructure\Repository\Doctrine\Repository\UserRepository")
 */
class User
{
    /**
     * @Mapping\Id()
     * @Mapping\Column(type="integer", nullable=false)
     * @Mapping\GeneratedValue(strategy="AUTO")
     */
    private int $id = 0;

    /**
     * @Mapping\Column(type="string", nullable=false)
     */
    private string $name = '';

    /**
     * @Mapping\Column(type="string", unique=true)
     *
     * @Constraints\NotBlank()
     */
    private string $email = '';

    /**
     * @Mapping\Column(type="string", nullable=true)
     */
    private ?string $password = null;

    /**
     * @Mapping\ManyToMany(targetEntity="Role", inversedBy="users")
     * @var Collection<int, Role>
     */
    private Collection $roles;

    /**
     * @Mapping\Column(name="created_at", type="datetime")
     */
    private ?DateTimeInterface $createdAt = null;

    /**
     * @Mapping\Column(name="updated_at", type="datetime")
     */
    private ?DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        
        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param Collection<int, Role> $roles
     */
    public function setRoles(Collection $roles): self
    {
        $this->roles = $roles;
        
        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
