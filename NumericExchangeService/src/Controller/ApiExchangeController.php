<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Entity\History;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiExchangeController extends AbstractController
{
    private $logger;
    private $entityManager;

    // Wstrzykiwanie loggera do kontrolera poprzez konstruktor
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    #[Route('/exchange/values', name: 'api_exchange', methods: ['POST'])]
    public function exchangeValues(Request $request): JsonResponse
    {        
        try {
            $data = json_decode($request->getContent(), true);
            $message = 'request completed correctly.';

            $first = isset($data['first']) ? (int)$data['first'] : null;
            $second = isset($data['second']) ? (int)$data['second'] : null;

            if (!isset($first) || !isset($second)) {
                $message = 'Both "first" and "second" parameters are required.';
                $this->logger->error($message);
                return $this->json([
                    'status' => 'error',
                    'message' => $message
                ], Response::HTTP_BAD_REQUEST);
            }            

            $now = new \DateTime();

            $history = new History();
            $history->setFirstIn($first);
            $history->setSecondIn($second);
            $history->setCreatedAt($now);
            $history->setUpdatedAt($now);

            $this->entityManager->persist($history);
            $this->entityManager->flush();

            $this->swap($first, $second);

            $history->setFirstOut($first);
            $history->setSecondOut($second);
            $history->setUpdatedAt($now);
            
            $this->entityManager->flush();

            $data['first'] = (int)$first;
            $data['second'] = (int)$second;

            return $this->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            $this->logger->error('An error occurred: ' . $e->getMessage());
            return $this->json([
                'status' => 'error',
                'message' => 'An error occurred. Please try again later.',
                'details' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/exchange/history/showAllRecords', name: 'api_exchange_history_show_all_records', methods: ['GET', 'POST'])]
    public function getShowAllRecordsFromHistory(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $page = isset($data['page']) ? (int)$data['page'] : 1;
            $limit = isset($data['limit']) ? (int)$data['limit'] : 10;
            $sort = isset($data['sort']) ? (string)$data['sort'] : 'id';
            $order = isset($data['order']) ? (string)$data['order'] : 'asc';

            $totalCount = count($this->entityManager->getRepository(History::class)->findAll());
            $totalPages = ceil($totalCount/$limit);

            $historyRepository = $this->entityManager->getRepository(History::class);
            $page = ($totalPages < $page) ? $totalPages : $page;
            $history = $historyRepository->findBy([], [$sort => $order], $limit, ($page - 1) * $limit);
            $itemsCount = count($history);

            $data = [];
            foreach ($history as $record) {
                $data[] = [
                    'first_in' => $record->getFirstIn(),
                    'second_in' => $record->getSecondIn(),
                    'first_out' => $record->getFirstOut(),
                    'second_out' => $record->getSecondOut(),
                    'created_at' => $record->getCreatedAt()->format('Y-m-d H:i:s'),
                    'updated_at' => $record->getUpdatedAt()->format('Y-m-d H:i:s'),
                ];
            }

            return $this->json([
                'status' => 'success',
                'message' => 'Request completed correctly.',
                'data' => $data,
                'pagination' => [
                    'total_items' => $totalCount,
                    'items_per_page' => $itemsCount,
                    'current_page' => $page,
                    'total_pages' => $totalPages
                ],
            ]);
        } catch (\Exception $e) {
            $this->logger->error('An error occurred: ' . $e->getMessage());
            return $this->json([
                'status' => 'error',
                'message' => 'An error occurred. Please try again later.',
                'details' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //helper functions
    private function swap(int &$first, int &$second): void {
        $first = $first + $second;
        $second = $first - $second;
        $first = $first - $second;
    }
}

