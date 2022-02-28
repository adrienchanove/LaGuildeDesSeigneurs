<?php

namespace App\Security\Voter;

use App\Entity\Player;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PlayerVoter extends Voter
{
    public const PLAYER_DISPLAY = 'player_display';
    public const PLAYER_MODIFY = 'player_modify';
    public const PLAYER_DELETE = 'player_delete';

    private const ATTRIBUTES = array(
        self::PLAYER_DISPLAY,
        self::PLAYER_MODIFY,
        self::PLAYER_DELETE
    );

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        /*return in_array($attribute, ['POST_EDIT', 'POST_VIEW'])
            && $subject instanceof \App\Entity\Player;*/

        if (null !== $subject) {

            return $subject instanceof Player && in_array($attribute, self::ATTRIBUTES);
        }
        return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        

        
        switch ($attribute) {
            case self::PLAYER_DISPLAY:
                
                return $this->canDisplay();
                break;
            case self::PLAYER_MODIFY:
                return $this->canModify();
                break;
            case self::PLAYER_DELETE:
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
