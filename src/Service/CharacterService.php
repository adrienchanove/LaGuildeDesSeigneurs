<?php

namespace App\Service;

use DateTime;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
//use App\Repository

class CharacterService implements CharacterServiceInterface
{
    /**
     * {@inheritdoc}
     */

    public function __construct(private EntityManagerInterface $em){}

    public function create()
    {
        $character = new Character();
        $character->setKind('Dame')
                  ->setName('Firiel')
                  ->setSurname('Femme Mortelle')
                  ->setCaste('Empoisonneuse')
                  ->setKnowledge('Sciences')
                  ->setIntelligence(110)
                  ->setLife(13)
                  ->setImage('https://static.zerochan.net/Firiel.Dee.full.615662.jpg')
                  ->setCreation(new DateTime())
                  ->setIdentifier(hash('sha1',uniqid()));

        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }
}