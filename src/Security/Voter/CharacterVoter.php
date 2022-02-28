<?php

namespace App\Security\Voter;

use App\Entity\Character;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterVoter extends Voter
{
    public const CHARACTER_DISPLAY = 'character_display';
    public const CHARACTER_MODIFY = 'character_modify';
    public const CHARACTER_DELETE = 'character_delete';

    private const ATTRIBUTES = array(
        self::CHARACTER_DISPLAY,
        self::CHARACTER_MODIFY,
        self::CHARACTER_DELETE
    );

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        /*return in_array($attribute, ['POST_EDIT', 'POST_VIEW'])
            && $subject instanceof \App\Entity\Character;*/

        if (null !== $subject) {

            return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);
        }
        return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        //$user = $token->getUser();
        // if the user is anonymous, do not grant access
        /*if (!$user instanceof UserInterface) {
            return false;
        }*/

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CHARACTER_DISPLAY:
                // logic to determine if the user can EDIT
                // return true or false
                return $this->canDisplay();
                break;
            case self::CHARACTER_MODIFY:
                return $this->canModify();
                break;
            case self::CHARACTER_DELETE:
                return $this->canDelete();
                break;
        }

        return false;
    }

    function canDisplay()
    {
        return true;
    }
    private function canModify()
    {
        return true;
    }
    private function canDelete()
    {
        return true;
    }
}
