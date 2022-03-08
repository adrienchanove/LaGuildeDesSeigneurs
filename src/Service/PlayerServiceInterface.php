<?php

namespace App\Service;

use App\Entity\Player;


interface PlayerServiceInterface
{
    /**
     * Create a player
     */
    public function create(string $data);

    /**
     * Modify the Player
     */
    public function modify(Player $player, string $data);

    /**
     * Deletes the Player
     */
    public function delete(Player $character);

    /**
     * Serialize the object(s)
     */
    public function serializeJson($data);


    /**
     * Checks if the entity has been well filled
     */
    public function isEntityFilled(Player $player);

    /**
     * Submits the data to hydrate the object
     */
    public function submit(Player $player, $formName, $data);
}
