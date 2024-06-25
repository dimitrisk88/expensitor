<?php

namespace App\Controller;

use App\Document\Expense;
use App\Document\Manager\ValidationAwareDocumentManager;
use App\Repository\ExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private ExpenseRepository $expenseRepository,
        private ValidationAwareDocumentManager $dm,
    ) {
    }

    #[Route('/', name: 'app_default')]
    public function index(): JsonResponse
    {
        $expense = new Expense();
        $expense->setAmount(5.65);
        $this->dm->persist($expense);
        $this->dm->flush();
        $expenses = $this->expenseRepository->findAll();
        foreach ($expenses as $expense) {
            dump($expense->getDescription());
        }
        dump($expenses);
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DefaultController.php',
        ]);
    }
}
