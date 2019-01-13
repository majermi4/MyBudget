<?php
declare(strict_types=1);

namespace MyBudget\Application\Controller;

use MyBudget\Domain\Repository\BudgetRepository;
use Symfony\Component\HttpFoundation\Response;

class ShowExpenseCategories
{
    public function __invoke(BudgetRepository $budgetRepository) : Response
    {
        $budget = $budgetRepository->getBudget();
        $expenses = $budget->getExpenseCategories();

        $expenseCategoriesArr = [];

        foreach ($expenses as $expenseCategory) {
            $expenseCategoriesArr[] = [
                'id' => $expenseCategory->id(),
                'name' => $expenseCategory->name(),
            ];
        }

        return new Response(
            json_encode(
                [
                    'expenseCategories' => $expenseCategoriesArr
                ]
            )
        );
    }
}