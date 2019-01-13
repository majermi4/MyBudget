<?php
declare(strict_types=1);

namespace MyBudget\Application\Controller;

use MyBudget\Domain\Repository\BudgetRepository;
use Symfony\Component\HttpFoundation\Response;

class ShowExpenses
{
    public function __invoke(BudgetRepository $budgetRepository) : Response
    {
        $budget = $budgetRepository->getBudget();
        $expenses = $budget->getExpenses();
        $expenseCategories = $budget->getExpenseCategories();

        $expenseCategoriesArr = [];

        foreach ($expenseCategories as $expenseCategory) {
            $expenseCategoriesArr[$expenseCategory->id()->toString()] = [
                'id' => $expenseCategory->id(),
                'name' => $expenseCategory->name(),
            ];
        }

        $expensesArr = [];

        foreach ($expenses as $expense) {
            $expensesArr[] = [
                'id' => $expense->id(),
                'text' => $expense->text(),
                'categoryId' => $expense->categoryId(),
                'purchasedAt' => $expense->purchasedAt()->format('H:i j.n. Y'),
                'price' => $expense->price()->toString(),
                'purcasedBy' => $expense->purchasedBy(),
            ];
        }

        return new Response(json_encode(
            [
                'expenses' => $expensesArr
            ]
        ));
    }
}