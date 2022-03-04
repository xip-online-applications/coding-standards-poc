<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\Role;

class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }
}