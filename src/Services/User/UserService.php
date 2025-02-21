<?php

namespace App\Services\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserService
{
    public function __construct(private Security $security, private UserRepository $userRepository, private Connection $connection, private EntityManagerInterface $entityManagerInterface) {}

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

    public function subscription(string $username)
    {
        $currentUser = $this->getAuthenticatedUser();

        $targetUser = $this->findUser(['username' => $username]);

        if (!$targetUser) {
            return null;
        }

        if ($currentUser->getSubscriptions()->contains($targetUser)) {
            throw new BadRequestException("Vous suivez déjà cet utilisateur.");
        }

        $targetUser->addSubscriber($currentUser);
        $currentUser->addSubscription($targetUser);
    }

    public function removeSubscription(string $username)
    {
        $currentUser = $this->getAuthenticatedUser();
        $targetUser = $this->findUser(['username' => $username]);

        if (!$targetUser) {
            return null;
        }

        if (!$currentUser->getSubscriptions()->contains($targetUser)) {
            throw new BadRequestException("Impossible de se désabonner de cet utilisateur, vous ne le suivez pas.");
        }

        $currentUser->removeSubscription($targetUser);
        $targetUser->removeSubscriber($currentUser);
    }
}
