<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Player;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;

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
        $players = $this->playerService->getAll();
        return JsonResponse::fromJsonString($this->playerService->serializeJson($players));
    }

    #[Route('/player/create', name: 'player_creation', methods: ['POST', 'HEAD'])]
    public function create(Request $request): Response
    {
        $player = $this->playerService->create($request->getContent());
        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
    }

    #[Route(
        '/player/display/{identifier}',
        name: 'player_display_identifier',
        requirements: ["identifier" => "^([a-z0-9]{40})$"],
        methods: ['GET', 'HEAD']
    )]
    #[Entity("player", expr:"repository.findOneByIdentifier(identifier)")]
    public function display(Player $player): Response
    {
        $this->denyAccessUnlessGranted('player_display', $player);
        
        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
    }

    #[Route(
        '/player/modify/{identifier}',
        requirements: ["identifier" => "^([a-z0-9]{40})$"],
        name: 'player_modify',
        methods: ['PUT', 'HEAD', 'POST']
    )]
    public function modify(Request $request, Player $player)
    {
        
        $this->denyAccessUnlessGranted('player_modify', $player);
        $character = $this->playerService->modify($player, $request->getContent());
        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
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
        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
    }
}
