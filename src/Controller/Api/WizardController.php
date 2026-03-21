<?php

namespace App\Controller\Api;

use App\Repository\OccasionRepository;
use App\Repository\BudgetTierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class WizardController extends AbstractController
{
    public function __construct(
        private OccasionRepository $occasionRepository,
        private BudgetTierRepository $budgetTierRepository,
    ) {}

    #[Route('/occasions', methods: ['GET'])]
    public function occasions(): JsonResponse
    {
        $occasions = $this->occasionRepository->findBy([], ['sortOrder' => 'ASC']);

        $data = array_map(fn($o) => [
            'id' => $o->getSlug(),
            'label' => $o->getName(),
            'emoji' => $o->getEmoji(),
        ], $occasions);

        return $this->json($data);
    }

    #[Route('/budget-tiers', methods: ['GET'])]
    public function budgetTiers(): JsonResponse
    {
        $tiers = $this->budgetTierRepository->findBy([], ['sortOrder' => 'ASC']);

        $data = array_map(fn($t) => [
            'id' => $t->getSlug(),
            'label' => $t->getLabel(),
            'desc' => $t->getDescription(),
            'minPrice' => $t->getMinPrice(),
            'maxPrice' => $t->getMaxPrice(),
        ], $tiers);

        return $this->json($data);
    }
}
