<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\User as UserEntity;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }
    
    public function create(): UserEntity
    {
        return new UserEntity();
    }
}