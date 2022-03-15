<?php

namespace App\Security\Voter;

use App\Entity\Player;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PlayerVoter extends Voter
{
    public const PLAYER_DISPLAY = 'playerDisplay';
    public const PLAYER_CREATE = 'playerCreate';
    public const PLAYER_INDEX = 'playerIndex';
    public const PLAYER_MODIFY = 'playerModify';
    public const PLAYER_DELETE = 'playerDelete';

    private const ATTRIBUTES = array(
        self::PLAYER_DISPLAY,
        self::PLAYER_CREATE,
        self::PLAYER_INDEX,
        self::PLAYER_MODIFY,
        self::PLAYER_DELETE
    );

    protected function supports(string $attribute, $subject): bool
    {
        if (null !== $subject) {
            return $subject instanceof Player && in_array($attribute, self::ATTRIBUTES);
        }

        return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Defines access rights
        return match ($attribute) {
            self::PLAYER_DISPLAY, self::PLAYER_INDEX => $this->canDisplay(),
            self::PLAYER_CREATE => $this->canCreate(),
            self::PLAYER_MODIFY => $this->canModify(),
            self::PLAYER_DELETE => $this->canDelete(),
            default => throw new \LogicException('Invalid attribute: ' . $attribute),
        };
    }

    /**
     * Checks if is allowed to display
     */
    private function canDisplay()
    {
        return true;
    }

    /**
     * Checks if is allowed to create
     */
    private function canCreate()
    {
        return true;
    }

    /**
     * Checks if is allowed to modify
     */
    private function canModify()
    {
        return true;
    }

    /**
     * Checks if is allowed to delete
     */
    private function canDelete()
    {
        return true;
    }
}
