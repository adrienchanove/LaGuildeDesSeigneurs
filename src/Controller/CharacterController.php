<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Character;
use App\Service\CharacterServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CharacterController extends AbstractController
{
    public function __construct(CharacterServiceInterface $characterService ){
        $this->characterService = $characterService;
    }

    #[Route('/character', name: 'character', methods: ['GET','HEAD'])]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CharacterController.php',
        ]);
    }

    /*#[Route('/character/display', name: 'character_display', methods: ['GET','HEAD'])]
    public function display(): Response
    {
        $character = new Character();
        //dump($character);
        //dd($character->toArray());
        return $this->json([
            'message' => $character->toArray(),
            
        ]);
    }*/

    #[Route('/character/create', name: 'character_creation', methods: ['POST','HEAD'])]
    public function create(): Response
    {
        $character = $this->characterService->create();
        return new JsonResponse($character->toArray());
        


        
        
    }

    #[Route(
        '/character/display/{identifier}', 
        name: 'character_display_identifier',
        requirements:["identifier"=> "^([a-z0-9]{40})$"], 
        methods: ['GET','HEAD']
    )]
    public function display(Character $character): Response
    {
        $this->denyAccessUnlessGranted('character_display',$character);
        // A MODIFIER
        //dump($character);
        //dd($character->toArray());
        return new JsonResponse($character->toArray());
    }
}
