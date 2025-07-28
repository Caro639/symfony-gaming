<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;

class AuthController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'message' => 'Login successful'
        ]);
    }

    #[Route('/api/login_check', name: 'api_login_check', methods: ['POST'])]
    public function loginCheck(): JsonResponse
    {
        // Cette méthode ne sera jamais appelée directement
        // Elle est interceptée par le système d'authentification JWT
        throw new \LogicException('This method can be blank - it will be intercepted by the security system.');
    }
}
