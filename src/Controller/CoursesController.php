<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CoursesController extends AbstractController
{
    private CourseRepository $courseRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CourseRepository $courseRepository, EntityManagerInterface $entityManager)
    {
        $this->courseRepository = $courseRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/courses', name: 'courses', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $courses = $this->courseRepository->findActiveCourses();
        return $this->json($courses, Response::HTTP_OK);
    }

    #[Route('/api/courses', name: 'store_course', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $this->getRequestData($request);

        if ($this->validateCourseData($data)) {
            return new JsonResponse(['error' => 'Campos obrigatórios estão ausentes'], Response::HTTP_BAD_REQUEST);
        }

        $course = new Course();
        $course->setName($data['name']);
        $course->setDescription($data['description'] ?? null);
        $course->setStatus($data['status']);

        $this->entityManager->persist($course);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'success', 'course_id' => $course->getId()], Response::HTTP_CREATED);
    }

    #[Route('/api/courses/{id}', name: 'update_course', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $course = $this->courseRepository->find($id);
        if (!$course) {
            return new JsonResponse(['error' => 'Curso não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $data = $this->getRequestData($request);

        $course->setName($data['name'] ?? $course->getName());
        $course->setDescription($data['description'] ?? $course->getDescription());
        $course->setStatus($data['status'] ?? $course->getStatus());

        $this->entityManager->flush();

        return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
    }

    private function getRequestData(Request $request): array
    {
        return json_decode($request->getContent(), true) ?? [];
    }

    private function validateCourseData(array $data): bool
    {
        return empty($data['name']) || !isset($data['status']);
    }
}

