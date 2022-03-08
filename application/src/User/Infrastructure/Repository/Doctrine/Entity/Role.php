<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping;
use Symfony\Component\Validator\Constraints;

/**
 * @Mapping\Entity(repositoryClass="XIP\User\Infrastructure\Repository\Doctrine\Repository\UserRepository")
 */
class Role
{
    /**
     * @Mapping\Id()
     * @Mapping\Column(type="integer", nullable=false)
     * @Mapping\GeneratedValue(strategy="AUTO")
     */
    protected int $id = 0;

    /**
     * @Mapping\Column(type="string", unique=true)
     *
     * @Constraints\NotBlank()
     */
    protected string $name = '';

    /**
     * @Mapping\ManyToMany(targetEntity="User", mappedBy="Roles")
     * @var Collection<int, User>
     */
    protected Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }
}