<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Character;
use App\Service\CharacterServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class CharacterController extends AbstractController
{
    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    //IMAGES
    /*** Returns images randomly** @Route("/character/images/{number}",*     name="character_images",*     requirements={"number": "^([0-9]{1,2})$"},*     methods={"GET", "HEAD"}* )*/
    #[Route('/character/images/{kind}/{number}', name: 'character_images', requirements: ["identifier" => "^([0-9]{1,2})$"], methods: ['GET', 'HEAD'])]
    public function images(int $number)
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        return new JsonResponse($this->characterService->getImages($number));
    }

    #[Route('/character/images/{number}', name: 'character_images_kind', requirements: ["identifier" => "^([0-9]{1,2})$"], methods: ['GET', 'HEAD'])]
    public function imagesKind(string $kind, int $number)
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        return new JsonResponse($this->characterService->getImagesKind($kind, $number));
    }



    #[Route('/character', name: 'character', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        $characters = $this->characterService->getAll();
        return JsonResponse::fromJsonString($this->characterService->serializeJson($characters));
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

    #[Route('/character/create', name: 'character_creation', methods: ['POST', 'HEAD'])]
    public function create(Request $request): Response
    {
        //$character = $this->characterService->create();
        $character = $this->characterService->create($request->getContent());
        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route(
        '/character/display/{identifier}',
        name: 'character_display_identifier',
        requirements: ["identifier" => "^([a-z0-9]{40})$"],
        methods: ['GET', 'HEAD']
    )]
    #[Entity("character", expr:"repository.findOneByIdentifier(identifier)")]
    public function display(Character $character): Response
    {
        $this->denyAccessUnlessGranted('character_display', $character);
        // A MODIFIER
        //dump($character);
        //dd($character->toArray());
        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route(
        '/character/modify/{identifier}',
        requirements: ["identifier" => "^([a-z0-9]{40})$"],
        name: 'character_modify',
        methods: ['PUT', 'HEAD', 'POST']
    )]
    public function modify(Request $request,Character $character)
    {
        $this->denyAccessUnlessGranted('character_modify', $character);
        $character = $this->characterService->modify($character, $request->getContent());
        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route(
        '/character/delete/{identifier}',
        requirements: ["identifier" => "^([a-z0-9]{40})$"],
        name: 'character_delete',
        methods: ['DELETE', 'HEAD', 'GET']
    )]
    public function delete(Character $character)
    {
        $this->denyAccessUnlessGranted('character_delete', $character);
        $response = $this->characterService->delete($character);
        return new JsonResponse(array('delete' => $response));
    }
}
