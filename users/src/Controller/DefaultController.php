<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }
    
    #[Route('/default', name: 'app_default')]
    public function index(): JsonResponse
    {
        $user = $this->userRepository->findOneById(1);
        
        return $this->json([
            $user->getName(),
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DefaultController.php',
        ]);
    }
}
