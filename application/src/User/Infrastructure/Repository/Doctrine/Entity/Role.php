<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Doctrine\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @Mapping\Entity(repositoryClass="XIP\User\Infrastructure\Repository\Doctrine\Repository\UserRepository")
 *
 * @UniqueEntity(fields={"name"})
 */
class Role
{
    /**
     * @Mapping\Id()
     * @Mapping\Column(type="integer", nullable=false)
     * @Mapping\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @Mapping\Column(type="string", unique=true)
     *
     * @Constraints\NotBlank()
     */
    protected string $name;

    /**
     * @Mapping\ManyToMany(targetEntity="User")
     * @Mapping\JoinTable(
     *     name="role_user",
     *     joinColumns={@Mapping\JoinColumn(name="role_id", referencedColumnName="id")},
     *     inverseJoinColumns={@Mapping\JoinColumn(name="user_id", referencedColumnName="id")}
     *     )
     * @var array<int, User>
     */
    protected array $users;

    /**
     * @Mapping\Column(type="datetime")
     */
    protected DateTimeInterface $createdAt;

    /**
     * @Mapping\Column(type="datetime")
     */
    protected DateTimeInterface $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array<int, User>
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array<int, User> $users
     */
    public function setUsers(array $users): void
    {
        $this->users = $users;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}