<?php
declare(strict_types=1);

namespace MyBudget\Application\Command;

use MyBudget\Domain\Aggregate\Person;
use MyBudget\Domain\Exception\CategoryAlreadyExistsException;
use MyBudget\Domain\Exception\ExpenseCategoryNotFoundException;
use MyBudget\Domain\Repository\BudgetRepository;
use MyBudget\Domain\Value\Price;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateExpense extends Command
{
    /** @var BudgetRepository */
    private $budgetRepository;

    public function __construct(BudgetRepository $budgetRepository)
    {
        $this->budgetRepository = $budgetRepository;

        parent::__construct('my_budget.create_expense');
    }

    public function configure()
    {
        $this
            ->addArgument('categoryId', InputArgument::REQUIRED)
            ->addArgument('price', InputArgument::REQUIRED)
            ->addArgument('person_id', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $categoryId = Uuid::fromString($input->getArgument('categoryId'));
        $price = Price::fromString($input->getArgument('price'));
        $person = new Person(Uuid::uuid4());

        $budget = $this->budgetRepository->getBudget();

        try {
            $budget->addExpense(
                $categoryId,
                $price,
                $person,
                new \DateTime()
            );
        } catch (ExpenseCategoryNotFoundException $e) {
            $output->writeln('<error>Expense category "'.$categoryId->toString().'" does not exists inside budget.</error>');

            return;
        }

        $this->budgetRepository->storeBudget($budget);

        $output->writeln('<info>Expnese added.</info>');
    }
}