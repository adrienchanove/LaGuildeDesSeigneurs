<?php

namespace App\Service\Character;

use DateTime;
use App\Entity\Character;
use App\Form\CharacterType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CharacterRepository;
use LogicException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Event\CharacterEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CharacterService implements CharacterServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private CharacterRepository $characterRepository,
        private FormFactoryInterface $formFactory,
        private ValidatorInterface $validator,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * {inheritdoc}
     */
    public function getAll()
    {
        $charactersFinal = array();
        $characters = $this->characterRepository->findAll();
        foreach ($characters as $character) {
            $charactersFinal[] = $character->toArray();
        }

        return $charactersFinal;
    }

    /**
     * {inheritdoc}
     */
    public function create(string $data)
    {
        //Use with {"kind":"Dame","name":"Eldalótë","surname":"Fleur elfique","caste":"Elfe","knowledge":"Arts","intelligence":120,"life":12,"image":"/images/eldalote.jpg"}
        $character = new Character();

        $this->submit($character, CharacterType::class, $data);

        return $this->createFromHtml($character);
    }

    public function createFromHtml(Character $character)
    {
        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreation(new DateTime())
            ->setModification(new DateTime());

        $event = new CharacterEvent($character);
        $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_CREATED);

        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    /**
     * {@inheritdoc}
     */
    public function isEntityFilled(Character $character)
    {
        $errors = $this->validator->validate($character);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors . ' Missing data for Entity -> ' . $this->serializeJson($character));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submit(Character $character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        //Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }

        //Submits form
        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
        $form->submit($dataArray, false); //With false, only submitted fields are validated

        //Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            throw new LogicException('Error ' . $error->getCause()::class . ' --> ' . $error->getMessageTemplate() . ' ' . json_encode($error->getMessageParameters(), JSON_THROW_ON_ERROR));
        }
    }

    public function modify(string $data, Character $character)
    {
        $this->submit($character, CharacterType::class, $data);

        return $this->modifyFromHtml($character);
    }

    public function modifyFromHtml(Character $character)
    {
        $this->isEntityFilled($character);
        $character
            ->setModification(new DateTime());

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    public function delete(Character $character)
    {
        $this->em->remove($character);

        $this->em->flush();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getImages(int $number, ?string $kind = null)
    {
        $folder = __DIR__ . '/../../../public/images/';

        $finder = new Finder();
        $finder
            ->files()
            ->in($folder)
            ->notPath('/cartes/')
            ->sortByName();

        if (null !== $kind) {
            $finder->path('/' . $kind . '/');
        }

        $images = array();

        foreach ($finder as $file) {
            $images[] = '/images/' . $file->getPathname();
        }

        shuffle($images);
        return array_slice($images, 0, $number, true);
    }

    public function getImagesKind(int $number, string $kind)
    {
        return $this->getImages($number, $kind);
    }

    /**
     * {@inheritdoc}
     */
    public function serializeJson($data)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn ($data) => $data->getIdentifier(),];
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizers], [$encoders]);
        return $serializer->serialize($data, 'json');
    }
}
