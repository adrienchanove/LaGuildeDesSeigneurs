<?php

namespace App\Service;

use DateTime;
use LogicException;
use App\Form\CharacterType;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Event\CharacterEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
//use App\Repository

class CharacterService implements CharacterServiceInterface
{
    /**
     * {@inheritdoc}
     */
    private $formFactory;
    public function __construct(
        private EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ValidatorInterface $validator,
        private CharacterRepository $characterRepository
    ) {
        $this->formFactory = $formFactory;
        $this->validator = $validator;
    }

    public function getImages(int $number)
    {
        $folder = __DIR__ . '/../../public/images/';
        $finder = new Finder();
        $finder->files()->in($folder)->notPath('/cartes/')->sortByName();
        $images = array();
        foreach ($finder as $file) {
            $images[] = '/images/' . $file->getPathname();
        }
        shuffle($images);
        return array_slice($images, 0, $number, true);
    }

    public function create(string $data)
    {
        $character = new Character();
        $character
            ->setCreation(new DateTime())
            ->setModification(new DateTime())
            ->setIdentifier(hash('sha1', uniqid()));

        $this->submit($character, CharacterType::class, $data);
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
            throw new UnprocessableEntityHttpException((string) $errors . ' Missing data for Entity -> ' . json_encode($character->toArray()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submit(Character $character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

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
            throw new LogicException('Error ' . get_class($error->getCause()) . ' --> ' . $error->getMessageTemplate() . ' ' . json_encode($error->getMessageParameters()));
        }
    }

    /*** {@inheritdoc}*/ public function modify(Character $character,  string $data)
    {
        $this->submit($character, CharacterType::class, $data);
        $this->isEntityFilled($character);
        $character
            ->setModification(new DateTime());
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
    public function getImagesKind(string $kind, int $number)
    {
        return "";
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {      
        return $this->characterRepository->findAll();
    }

    public function serializeJson($data)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($data) {
                return $data->getIdentifier();
            },
        ];
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizers], [$encoders]);

        return $serializer->serialize($data, 'json');
    }



}
