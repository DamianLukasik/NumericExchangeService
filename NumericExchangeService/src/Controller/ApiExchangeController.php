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

    private function swap(int &$first, int &$second): void {
        $first = $first + $second;
        $second = $first - $second;
        $first = $first - $second;
    }
}

