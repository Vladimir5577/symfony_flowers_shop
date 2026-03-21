<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {}

    #[Route('/categories', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $categories = $this->categoryRepository->findBy([], ['sortOrder' => 'ASC']);

        $data = array_map(fn($cat) => [
            'slug' => $cat->getSlug(),
            'name' => $cat->getName(),
            'type' => $cat->getType()?->value,
        ], $categories);

        return $this->json($data);
    }
}
