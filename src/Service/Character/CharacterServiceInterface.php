<?php

namespace App\Service\Character;

use App\Entity\Character;

interface CharacterServiceInterface
{
    /**
     * Gets all the characters
     */
    public function getAll();

    /**
     * Gets all the characters whith intelligence greater than parameter or equals
     */
    public function getAllByIntelligenceLevel($lvl);

    /**
     * Gets all the characters whith life greater than parameter or equals
     */
    public function getAllByLife($life);

    /**
     * Gets all the characters whith thes caste in parameter
     */
    public function getAllByCaste($caste);

    /**
     * Creates the character
     */
    public function create(string $data);

    /**
     * Checks if the entity has been well filled
     */
    public function isEntityFilled(Character $character);

    /**
     * Submits the data to hydrate the
     */
    public function submit(Character $character, $formName, $data);

    /**
     * Modifies the character
     */
    public function modify(string $data, Character $character);

    /**
     * Deletes the character
     */
    public function delete(Character $character);

    /**
     * Gets image randomly
     */
    public function getImages(int $number, ?string $kind = null);

    /**
     * Serialize the object(s)
     */
    public function serializeJson($data);

    /**
     *  Creates the character from html form
     */
    public function createFromHtml(Character $character);

    /**
     *  Modifies the character from html form
     */
    public function modifyFromHtml(Character $character);
}
