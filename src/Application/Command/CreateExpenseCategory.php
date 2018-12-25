<?php
declare(strict_types=1);

namespace MyBudget\Application\Command;

use MyBudget\Domain\Exception\CategoryAlreadyExistsException;
use MyBudget\Domain\Repository\BudgetRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateExpenseCategory extends Command
{
    /** @var BudgetRepository */
    private $budgetRepository;

    public function __construct(BudgetRepository $budgetRepository)
    {
        $this->budgetRepository = $budgetRepository;

        parent::__construct('my_budget.create_expense_category');
    }

    public function configure()
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $budget = $this->budgetRepository->getBudget();

        $categoryName = $input->getArgument('name');

        try {
            $budget->addExpenseCategory($categoryName);
        } catch (CategoryAlreadyExistsException $e) {
            $output->writeln('<error>Category "'.$e->getCategoryName().'" already exists.</error>');

            return;
        }

        $this->budgetRepository->storeBudget($budget);

        $output->writeln('<info>Expnese category "'.$categoryName.'" added.</info>');
    }
}