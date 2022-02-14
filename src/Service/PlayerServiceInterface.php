<?php

namespace App\Service;

use App\Entity\Player;


interface PlayerServiceInterface
{
    public function create();
    public function modify(Player $character);
    /**
    * Deletes the Player
    */
    public function delete(Player $character);
}
