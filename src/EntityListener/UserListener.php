<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


/**
 * UserListener
 *
 * This listener is responsible for handling user-related events, such as prePersist and preUpdate,
 * to ensure that user passwords are properly hashed before being persisted to the database.
 */
class UserListener
{
    private UserPasswordHasherInterface $hasher;

    /**
     * Constructor.
     *
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    //end __construct()

    /**
     * Prepersist
     *
     * * This method is called before a User entity is persisted to the database.
     * It ensures that the user's password is properly hashed.
     * 
     * @param User $user
     * @return void
     */
    public function prePersist(User $user)
    {
        $this->encodePassword($user);
    }

    /**
     * Pre-update event handler.
     *
     * This method is called before a User entity is updated in the database.
     * It ensures that the user's password is properly hashed.
     *
     * @param User $user
     * @return void
     */
    public function preUpdate(User $user)
    {
        $this->encodePassword($user);
    }

    /**
     * Encode password based on plain password.
     *
     * This method hashes the user's plain password and sets the hashed password on the user entity.
     * If no plain password is set, the method returns without making any changes.
     *
     * @param User $user
     * @return void
     */
    public function encodePassword(User $user)
    {
        if ($user->getPlainPassword() === null) {
            return;
        }

        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $user->getPlainPassword()
            )
        );

        $user->setPlainPassword(null);
    }
}
