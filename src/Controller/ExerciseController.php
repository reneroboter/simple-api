<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\User;
use App\Repository\ExerciseRepository;
use App\Service\ExerciseService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @method User getUser()
 * @Rest\Version({"", "v1"})
 */
class ExerciseController extends AbstractFOSRestController
{
    /**
     * @var ExerciseRepository $exerciseRepository
     */
    protected $exerciseRepository;

    /**
     * @var ExerciseService $exerciseService
     */
    protected $exerciseService;

    /**
     * ExerciseController constructor.
     * @param ExerciseRepository $exerciseRepository
     * @param ExerciseService $exerciseService
     */
    public function __construct(ExerciseRepository $exerciseRepository, ExerciseService $exerciseService)
    {
        $this->exerciseRepository = $exerciseRepository;
        $this->exerciseService = $exerciseService;
    }

    /**
     * @return array
     */
    public function getExercisesAction(): array
    {
        return [
            'exercises' => $this->getUser()->getExercises()
        ];
    }

    /**
     * @param Exercise $exercise
     * @return array
     */
    public function getExerciseAction(Exercise $exercise): array
    {
        $this->denyAccessUnlessGranted('view', $exercise);
        return [
            'exercise' => $exercise
        ];
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function postExerciseAction(Request $request): Response
    {
        $exercise = $this->exerciseService->handleRequest($request, (new Exercise())->setUser($this->getUser()));

        $view = $this->view($exercise, Response::HTTP_CREATED);
        return $this->handleView($view);
    }

    /**
     * @param Exercise $exercise
     * @return Response
     */
    public function deleteExerciseAction(Exercise $exercise): Response
    {
        $this->denyAccessUnlessGranted('delete', $exercise);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($exercise);
        $entityManager->flush();

        $view = $this->view($exercise, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param Exercise $exercise
     * @return Response
     */
    public function putExerciseAction(Request $request, Exercise $exercise): Response
    {
        $this->denyAccessUnlessGranted('edit', $exercise);
        $exercise = $this->exerciseService->handleRequest($request, $exercise, ['method' => 'PUT']);

        $view = $this->view($exercise, Response::HTTP_OK);
        return $this->handleView($view);
    }
}
