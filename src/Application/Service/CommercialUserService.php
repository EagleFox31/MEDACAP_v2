<?php

namespace Application\Service;

use Domain\Repository\CommercialUserRepositoryInterface;
use Domain\Model\CommercialUser;
use Application\DTO\CommercialUserDTO;

class CommercialUserService
{
    private $repo;

    public function __construct(CommercialUserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function listUsers(): array
    {
        return $this->repo->findAll();
    }

    public function createUser(CommercialUserDTO $dto): string
    {
        $user = new CommercialUser(
            null,
            $dto->firstName,
            $dto->lastName,
            $dto->filiale,
            $dto->agence,
            $dto->level,
            $dto->department,
            $dto->brandsByLevel,
            $dto->managerId,
            $dto->passwordHash,
            $dto->visiblePassword
        );
        $this->repo->save($user);
        return $user->getId();
    }

    public function updateUser(CommercialUserDTO $dto): void
    {
        $user = new CommercialUser(
            $dto->id,
            $dto->firstName,
            $dto->lastName,
            $dto->filiale,
            $dto->agence,
            $dto->level,
            $dto->department,
            $dto->brandsByLevel,
            $dto->managerId,
            $dto->passwordHash,
            $dto->visiblePassword
        );
        $this->repo->save($user);
    }

    public function deleteUser(string $id): void
    {
        $this->repo->delete($id);
    }

    public function generatePassword(int $length = 8): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $pass = '';
        for ($i = 0; $i < $length; $i++) {
            $pass .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $pass;
    }
}
