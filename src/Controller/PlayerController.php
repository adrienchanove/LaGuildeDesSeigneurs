<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Player;

use App\Service\PlayerServiceInterface;

class PlayerController extends AbstractController
{

    public function __construct(PlayerServiceInterface $playerService)
    {
        $this->playerService = $playerService;
    }


    #[Route('/player', name: 'player')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PlayerController.php',
        ]);
    }

    #[Route('/player/create', name: 'player_creation', methods: ['POST', 'HEAD'])]
    public function create(): Response
    {
        $player = $this->playerService->create();
        return new JsonResponse($player->toArray());
    }

    #[Route(
        '/player/display/{identifier}',
        name: 'player_display_identifier',
        requirements: ["identifier" => "^([a-z0-9]{40})$"],
        methods: ['GET', 'HEAD']
    )]
    public function display(Player $player): Response
    {
        $this->denyAccessUnlessGranted('player_display', $player);
        
        return new JsonResponse($player->toArray());
    }

    #[Route(
        '/player/modify/{identifier}',
        requirements: ["identifier" => "^([a-z0-9]{40})$"],
        name: 'player_modify',
        methods: ['PUT', 'HEAD', 'GET']
    )]
    public function modify(Player $player)
    {
        $this->denyAccessUnlessGranted('player_modify', $player);
        $player = $this->playerService->modify($player);
        return new JsonResponse($player->toArray());
    }

    #[Route(
        '/player/delete/{identifier}',
        requirements: ["identifier" => "^([a-z0-9]{40})$"],
        name: 'player_delete',
        methods: ['DELETE', 'HEAD', 'GET']
    )]
    public function delete(Player $player)
    {
        $this->denyAccessUnlessGranted('player_delete', $player);
        $response = $this->playerService->delete($player);
        return new JsonResponse(array('delete' => $response));
    }
}
