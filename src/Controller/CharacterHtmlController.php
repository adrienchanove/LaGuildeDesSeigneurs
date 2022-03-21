<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterHtmlType;
use App\Service\Character\CharacterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/character/html')]
class CharacterHtmlController extends AbstractController
{
    public function __construct(private CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    #[Route('/', name: 'character_html_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('character_html/index.html.twig', [
            'characters' => $this->characterService->getAll(),
        ]);
    }

    #[Route('/AllByIntelligenceLevel/{lvl}', requirements: ["lvl" => "^([0-9]{1,4})$"], name: 'character_html_index_intelligence_gt', methods: ['GET'])]
    public function getAllByIntelligenceLevel(Int $lvl): Response
    {
        return $this->render('character_html/index.html.twig', [
            'characters' => $this->characterService->getAllByIntelligenceLevel($lvl),
        ]);
    }

    #[Route('/AllByLife/{life}', requirements: ["life" => "^([0-9]{1,4})$"], name: 'character_html_index_life_gt', methods: ['GET'])]
    public function getAllByLife(Int $life): Response
    {
        return $this->render('character_html/index.html.twig', [
            'characters' => $this->characterService->getAllByIntelligenceLevel($life),
        ]);
    }

    #[Route('/AllByCaste/{caste}', requirements: ["caste" => "^([\w\sa-zA-Z]{1,16})$"], name: 'character_html_index_caste_gt', methods: ['GET'])]
    public function getAllByCaste(String $caste): Response
    {
        return $this->render('character_html/index.html.twig', [
            'characters' => $this->characterService->getAllByCaste($caste),
        ]);
    }

    #[Route('/new', name: 'character_html_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterHtmlType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->characterService->createFromHtml($character);
            return $this->redirectToRoute('character_html_show', array('id' => $character->getId()));
        }

        return $this->renderForm('character_html/new.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'character_html_show', methods: ['GET'])]
    public function show(Character $character): Response
    {
        return $this->render('character_html/show.html.twig', [
            'character' => $character,
        ]);
    }

    #[Route('/{id}/edit', name: 'character_html_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CharacterHtmlType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterService->modifyFromHtml($character);
            return $this->redirectToRoute('character_html_show', array('id' => $character->getId()));
        }

        return $this->renderForm('character_html/edit.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'character_html_delete', methods: ['POST'])]
    public function delete(Request $request, Character $character, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $character->getId(), $request->request->get('_token'))) {
            $entityManager->remove($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('character_html_index', [], Response::HTTP_SEE_OTHER);
    }
}
