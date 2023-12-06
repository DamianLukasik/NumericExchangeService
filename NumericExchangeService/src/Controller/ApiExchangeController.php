<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiExchangeController extends AbstractController
{
    private $logger;

    // Wstrzykiwanie loggera do kontrolera poprzez konstruktor
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/api/exchange', name: 'api_exchange', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {        
        try {
            $data = json_decode($request->getContent(), true);
            $message = 'request completed correctly';
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
            ], 500);
        }
    }
}

