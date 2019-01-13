<?php
declare(strict_types=1);

namespace MyBudget\Application\Controller;

use MyBudget\Domain\Repository\BudgetRepository;
use Symfony\Component\HttpFoundation\Response;

class RetrieveClientData
{
    public function __invoke(BudgetRepository $budgetRepository) : Response
    {
        $budget = $budgetRepository->getBudget();
        $expenseCategories = $budget->getExpenseCategories();
        $expenses = $budget->getExpenses();

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
                'categoryName' => $expenseCategoriesArr[$expense->categoryId()->toString()],
                'purchasedAt' => $expense->purchasedAt()->format('H:i j.n. Y'),
                'price' => $expense->price()->toString(),
                'purcasedBy' => $expense->purchasedBy(),
            ];
        }

        return new Response(
            json_encode(
                [
                    'expenseCategories' => $expenseCategoriesArr,
                    'expenses' => $expensesArr
                ]
            )
        );
    }
}