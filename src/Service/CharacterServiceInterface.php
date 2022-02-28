<?php

namespace App\Service;

use App\Entity\Character;


interface CharacterServiceInterface
{
    
    public function modify(Character $character, string $data);
    /**
    * Deletes the character
    */
    public function delete(Character $character);
    
    //IMAGES
    public function getImages(int $number);
    public function getImagesKind(string $kind, int $number);

    public function create(string $data);
    /**
    * Checks if the entity has been well filled
    */
    public function isEntityFilled(Character $character);
    /**
    * Submits the data to hydrate the object
    */
    public function submit(Character $character, $formName, $data);
}
