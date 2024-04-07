<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/card', name: 'api_card_')]
#[OA\Tag(name: 'Card', description: 'Routes for all about cards')]
class ApiCardController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger
    )
    {

    }

    #[Route('/all/{page<\d+>?1}/{limit<\d+>?100}/{setCode?}', name: 'List all cards', methods: ['GET'])]
    #[OA\Get(description: 'Return all cards in the database')]
    #[OA\Parameter(name: 'page', description: 'Page number', in: 'path', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'limit', description: 'Number of cards per page', in: 'path', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'setCode', description: 'Set code of the card', in: 'path', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'List all cards')]
    public function cardAll(int $page = 1, int $limit = 100, string $setCode = null): Response
    {
        $queryBuilder = $this->entityManager->getRepository(Card::class)->createQueryBuilder('c');

        if (!is_null($setCode)) {
            $queryBuilder->where('c.set_code = :setCode')
                ->setParameter('setCode', $setCode);
        }

        $queryBuilder->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $this->logger->info('Fetching all cards' . is_null($setCode) ? '' : ' with set code: ' . $setCode);
        $cards = $queryBuilder->getQuery()->getResult();

        return $this->json($cards);
    }

    #[Route('/set-codes', name: 'Get set codes', methods: ['GET'])]
    #[OA\Get(description: 'Get all set codes')]
    #[OA\Response(response: 200, description: 'List of set codes')]
    public function getSetCodes(): Response
    {
        $this->logger->info('Fetching all set codes');
        $setCodes = $this->entityManager->getRepository(Card::class)->createQueryBuilder('c')
            ->select('c.set_code')
            ->distinct()
            ->getQuery()
            ->getResult();

        return $this->json($setCodes);
    }

    #[Route('/{uuid}', name: 'Show card', methods: ['GET'])]
    #[OA\Parameter(name: 'uuid', description: 'UUID of the card', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Put(description: 'Get a card by UUID')]
    #[OA\Response(response: 200, description: 'Show card')]
    #[OA\Response(response: 404, description: 'Card not found')]
    public function cardShow(string $uuid): Response
    {
        $this->logger->info('Fetching card with UUID: ' . $uuid);
        $card = $this->entityManager->getRepository(Card::class)->findOneBy(['uuid' => $uuid]);
        if (!$card) {
            $this->logger->error('Card not found with UUID: ' . $uuid);
            return $this->json(['error' => 'Card not found'], 404);
        }
        return $this->json($card);
    }

    #[Route('/search/{name}/{setCode?}', name: 'Search cards', methods: ['GET'])]
    #[OA\Get(description: 'Search cards by name and set code')]
    #[OA\Parameter(name: 'name', description: 'Name of the card', in: 'path', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'setCode', description: 'Set code of the card', in: 'path', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'List of cards')]
    public function cardSearch(string $name, string $setCode = null): Response
    {
        if (strlen($name) < 3) {
            return $this->json(['error' => 'The search term must be at least 3 characters long'], 400);
        }

        $queryBuilder = $this->entityManager->getRepository(Card::class)->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->setMaxResults(20);

        if (!is_null($setCode)) {
            $queryBuilder->andWhere('c.set_code = :setCode')
                ->setParameter('setCode', $setCode);
        }

        $this->logger->info('Searching for cards with name: ' . $name . is_null($setCode) ? '' : ' and set code: ' . $setCode);
        $cards = $queryBuilder->getQuery()->getResult();

        return $this->json($cards);
    }
}
