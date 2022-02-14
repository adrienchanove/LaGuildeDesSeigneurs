<?php

namespace App\Service;

use DateTime;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
//use App\Repository

class PlayerService implements PlayerServiceInterface
{
    /**
     * {@inheritdoc}
     */

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function create()
    {
        $player = new Player();
        $player->setFirstname('Adrien')
            ->setLastname('Chanove')
            ->setEmail('adrien.chanove@gmail.com')
            ->setMirian(0)
            ->setCreation(new DateTime())
            ->setIdentifier(hash('sha1', uniqid()))
            ;

        $this->em->persist($player);
        $this->em->flush();
        return $player;
    }

    /*** {@inheritdoc}*/ public function modify(Player $player)
    {
        $player->setFirstname('Adrien')
            ->setLastname('Chanove')
            ->setEmail('adrien.chanove@gmail.com')
            ->setMirian(500)
            ;
        $this->em->persist($player);
        $this->em->flush();
        return $player;
    }

    /**
    * {@inheritdoc}
    */ 
    public function delete(Player $player)
    {
        $this->em->remove($player);
        $this->em->flush();
        return true;
    }
}
