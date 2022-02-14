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

    public function __construct(private EntityManagerInterface $em)
    {
    }

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
            ->setModification(new DateTime())
            ->setIdentifier(hash('sha1', uniqid()));

        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }

    /*** {@inheritdoc}*/ public function modify(Character $character)
    {
        $character->setKind('Seigneur')
            ->setName('Gorthol')
            ->setSurname('Haume de terreur')
            ->setCaste('Chevalier')
            ->setKnowledge('Diplomatie')
            ->setIntelligence(110)
            ->setLife(13)
            ->setImage('/images/gorthol.jpg')
            ->setModification(new DateTime())
            ;
        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }

    /**
    * {@inheritdoc}
    */ 
    public function delete(Character $character)
    {
        $this->em->remove($character);
        $this->em->flush();
        return true;
    }
}
