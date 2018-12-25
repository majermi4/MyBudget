<?php
declare(strict_types=1);

namespace MyBudget\Application\Command;

use MyBudget\Domain\Repository\BudgetRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowExpenses extends Command
{
    /** @var BudgetRepository */
    private $budgetRepository;

    public function __construct(BudgetRepository $budgetRepository)
    {
        $this->budgetRepository = $budgetRepository;

        parent::__construct('my_budget.show_expenses');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $budget = $this->budgetRepository->getBudget();

        $expenseCategories = $budget->getExpenseCategories();
        $expenses = $budget->getExpenses();

        $tableRows = [];
        foreach ($expenses as $expense) {

            $tableRows[] = [
                $expense->id(),
                $expense->categoryId(),
                $expense->price()->toString(),
                $expense->purchasedAt()->format('j.n. Y (H:i)'),
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['ID', 'Category', 'Price', 'Added on'])
            ->setRows($tableRows)
        ;
        $table->render();
    }
}