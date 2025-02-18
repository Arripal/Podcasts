<?php

namespace App\Services\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\SecurityBundle\Security;

class UserService
{
    public function __construct(private Security $security, private UserRepository $userRepository, private Connection $connection) {}

    public function getAuthenticatedUser(): ?User
    {
        return $this->security->getUser();
    }

    public function findUser(array $identifier)
    {
        return $this->userRepository->findOneBy($identifier) ?? null;
    }

    public function findUserByUsername(string $username)
    {
        //Ajout de la sensibiliter à la casse
        $sql = 'SELECT * FROM public."user" WHERE username ILIKE :username';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('username', $username);
        $result = $stmt->executeQuery()->fetchAssociative();

        return $result ?: null;
    }
}
