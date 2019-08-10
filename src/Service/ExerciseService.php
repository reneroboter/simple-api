<?php


namespace App\Service;


use App\Entity\Exercise;
use App\Form\Type\ExerciseType;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ExerciseService
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    /**
     * @param string $type
     * @param null|object $data
     * @param array $options
     * @return FormInterface
     *
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    /**
     * @param Request $request
     * @param Exercise $exercise
     * @param array $options
     * @return Exercise
     */
    public function handleRequest(Request $request, Exercise $exercise, $options = []): Exercise
    {
        $form = $this->createForm(ExerciseType::class, $exercise, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && !$form->isValid()) {
            throw new InvalidArgumentException((string)$form->getErrors(true, false));
        }
        /**
         * @var Exercise $exercise
         */
        $exercise = $form->getData();
        $this->entityManager->persist($exercise);
        $this->entityManager->flush();
        return $exercise;
    }
}
