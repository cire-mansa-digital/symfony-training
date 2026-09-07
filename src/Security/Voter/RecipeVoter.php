<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Recipe;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class RecipeVoter extends Voter
{
    public const EDIT = 'RECIPE_EDIT';
    public const VIEW = 'RECIPE_VIEW';
    public const CREATE = 'RECIPE_CREATE';
    public const LIST = 'RECIPE_LIST';
    public const LIST_ALL = 'RECIPE_LIST_ALL';
    public const DELETE = 'RECIPE_DELETE';

    public function __construct(private readonly Security $security) {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return  in_array($attribute, [self::CREATE, self::LIST]) ||
        (
            in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Recipe
        );
    }

    /**
     * Method voteOnAttribute
     *
     * @param string $attribute [explicite description]
     * @param Recipe|null $subject [explicite description]
     * @param TokenInterface $token [explicite description]
     * @param ?Vote $vote [explicite description]
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            $vote?->addReason('The user must be logged in to access this resource.');

            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $subject->getRuser()->getId() == $user->getId();

            case self::CREATE:

                return $this->security->isGranted('ROLE_USER') ;
            case self::DELETE:
            case self::LIST:
                // return true;
            // case self::LIST_ALL:
            //     return $this->security->isGranted('ROLE_ADMIN');
            case self::VIEW:
                return true;

        }

        return false;
    }
}
