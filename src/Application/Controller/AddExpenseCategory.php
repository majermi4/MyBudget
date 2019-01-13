<?php
declare(strict_types=1);

namespace MyBudget\Application\Controller;

use MyBudget\Domain\Exception\CategoryAlreadyExistsException;
use MyBudget\Domain\Repository\BudgetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddExpenseCategory
{
    public function __invoke(Request $request, BudgetRepository $budgetRepository) : Response
    {
        $requestJson = json_decode((string) $request->getContent(), true);
        $categoryName = $requestJson['categoryName'] ?? '';

        if (strlen($categoryName) < 2) {
            return new Response(
                json_encode(
                    [
                        'error' => true,
                        'msg' => 'Category name must be longer than 2 characters.'
                    ]
                ),
                422
            );
        }

        $budget = $budgetRepository->getBudget();

        try {
            $budget->addExpenseCategory($categoryName);
            $budgetRepository->storeBudget($budget);
        } catch (CategoryAlreadyExistsException $e) {
            return new Response(
                json_encode(
                    [
                        'error' => true,
                        'msg' => 'Category already exists.'
                    ]
                ),
                409
            );
        }

        return new Response(
            json_encode(
                [
                    'error' => false,
                    'msg' => 'OK'
                ]
            ),
            201
        );
    }
}