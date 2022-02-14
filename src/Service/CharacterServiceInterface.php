<?php

namespace App\Service;

use App\Entity\Character;


interface CharacterServiceInterface
{
    public function create();
    public function modify(Character $character);
    /**
    * Deletes the character
    */
    public function delete(Character $character);
}
