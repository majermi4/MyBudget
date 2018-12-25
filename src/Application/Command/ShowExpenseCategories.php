<?php
declare(strict_types=1);

namespace MyBudget\Application\Command;

use MyBudget\Domain\Repository\BudgetRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowExpenseCategories extends Command
{
    /** @var BudgetRepository */
    private $budgetRepository;

    public function __construct(BudgetRepository $budgetRepository)
    {
        $this->budgetRepository = $budgetRepository;

        parent::__construct('my_budget.show_expense_categories');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $budget = $this->budgetRepository->getBudget();
        $expenseCategories = $budget->getExpenseCategories();

        $tableRows = [];
        foreach ($expenseCategories as $expenseCategory) {
            $tableRows[] = [
                $expenseCategory->id(),
                $expenseCategory->name(),
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['ID', 'Name'])
            ->setRows($tableRows)
        ;

        $table->render();
    }
}