<?php

namespace App\Controller;

use App\Form\CharacterApiHtmlType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\Character\CharacterServiceInterface;


#[Route("/character/api-html")]
class CharacterApiHtmlController extends AbstractController
{
    private $client;

    public function __construct(
        HttpClientInterface $client
    ) {
        $this->client = $client;
    }


    #[Route("/", name: "character_api_html_index", methods: ["GET"])]
    public function index(): Response
    {
        $response = $this->client->request('GET','http://localhost/character/index');
        return $this->render('character_api_html/index.html.twig', [
            'characters' => $response->toArray(),
        ]);
    }

    #[Route("/AllByIntelligenceLevel/{lvl}", requirements: ["lvl" => "^([0-9]{1,4})$"], name: "character_api_html_intelligence_gt", methods: ["GET"])]
    public function indexIntelligenceGt($lvl): Response
    {
        $response = $this->client->request('GET','http://localhost/character/display/AllByIntelligenceLevel/'.$lvl);
        return $this->render('character_api_html/index.html.twig', [
            'characters' => $response->toArray(),
        ]);
    }

    #[Route("/AllByLife/{life}", requirements: ["life" => "^([0-9]{1,4})$"], name: "character_api_html_life_gt", methods: ["GET"])]
    public function indexLifeGt($life): Response
    {
        $response = $this->client->request('GET','http://localhost/character/display/AllByLife/'.$life);
        
        return $this->render('character_api_html/index.html.twig', [
            'characters' => $response->toArray(),
        ]);
    }

    #[Route("/AllByCaste/{caste}", requirements: ["caste" => "^([\w\sa-zA-Z]{1,16})$"], name: "character_api_html_caste_gt", methods: ["GET"])]
    public function indexCasteGt($caste): Response
    {
        $response = $this->client->request('GET','http://localhost/character/display/AllByCaste/'.$caste);
        
        return $this->render('character_api_html/index.html.twig', [
            'characters' => $response->toArray(),
        ]);
    }


    #[Route("/new", name: "character_api_html_new", methods: ["GET", "POST"])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('characterCreate', null);

        $character = array();
        $form = $this->createForm(CharacterApiHtmlType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->client->request('POST','http://localhost/character/create',[
                'json' => $request->request->all()['character_api_html'],
            ]);

            return $this->redirectToRoute('character_api_html_show', array(
                'identifier' => $response->toArray()['identifier'],
            ));
        }

        return $this->renderForm('character_api_html/new.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }


    #[Route("/{identifier}", name: "character_api_html_show", methods: ["GET"])]
    public function show(string $identifier): Response
    {
        $response = $this->client->request('GET','http://localhost/character/display/' . $identifier);

        return $this->render('character_api_html/show.html.twig', [
            'character' => $response->toArray(),
        ]);
    }


    #[Route("/{identifier}/edit", name: "character_api_html_edit", methods: ["GET", "POST"])]
    public function edit(Request $request, string $identifier): Response
    {
        $response = $this->client->request('GET','http://localhost/character/display/' . $identifier);$character = $response->toArray();

        $form = $this->createForm(CharacterApiHtmlType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->client->request('PUT','http://localhost/character/modify/' . $identifier,[
                'json' => $request->request->all()['character_api_html'],
                
            ]);

            return $this->redirectToRoute('character_api_html_show', array(
                'identifier' => $identifier,
            ));
        }

        return $this->renderForm('character_api_html/edit.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }


    #[Route("/{identifier}", name: "character_api_html_delete", methods: ["POST"])]
    public function delete(Request $request, string $identifier): Response
    {
        if ($this->isCsrfTokenValid('delete' . $identifier, $request->request->get('_token'))) {
            $this->client->request('DELETE','http://localhost/character/delete/' . $identifier,);
        }

        return $this->redirectToRoute('character_api_html_index', [], Response::HTTP_SEE_OTHER);
    }
}
