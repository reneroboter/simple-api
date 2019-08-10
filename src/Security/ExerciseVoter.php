<?php

namespace App\Security;

use App\Entity\Exercise;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ExerciseVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';


    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if(!$subject instanceof Exercise) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token) : bool
    {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }
        /**
         * @var Exercise $exercise
         */
        $exercise = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($exercise, $user);
            case self::EDIT:
                return $this->canEdit($exercise, $user);
            case self::DELETE:
                return $this->canDelete($exercise, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Exercise $exercise, User $user) : bool
    {
        if ($this->canEdit($exercise, $user)) {
            return true;
        }

        return $exercise->getUser() === $user;
    }

    private function canDelete(Exercise $exercise, User $user) : bool
    {
        if ($this->canEdit($exercise, $user)) {
            return true;
        }

        return $exercise->getUser() === $user;
    }

    private function canEdit(Exercise $exercise, User $user) : bool
    {
        return $exercise->getUser() === $user;
    }
}
