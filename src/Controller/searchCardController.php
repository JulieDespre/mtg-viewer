<?php

namespace App\Controller;

use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class searchCardController extends AbstractController
{
    public function __construct(
        private CardRepository $cardRepository
    ) {
    }

    #[Route('/api/cards/search', name: 'search_cards', methods: ['GET'])]
    public function search(Request $request, CardRepository $cardRepository): JsonResponse
    {
        $query = $request->query->get('q');
        $results = [];

        if (!empty($query)) {
            $results = $cardRepository->findByName($query, 20);
        }

        return $this->json($results);
    }
}
