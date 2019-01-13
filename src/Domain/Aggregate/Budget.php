<?php
declare(strict_types=1);

namespace MyBudget\Domain\Aggregate;

use MyBudget\Domain\Entity\Expense;
use MyBudget\Domain\Entity\Person;
use MyBudget\Domain\Event\BudgetCreatedWithMonthlyContribution;
use MyBudget\Domain\Event\MonthlyContributionChanged;
use MyBudget\Domain\Event\ExpenseAdded;
use MyBudget\Domain\Entity\ExpenseCategory;
use MyBudget\Domain\Event\ExpenseCategoryAdded;
use MyBudget\Domain\Event\PersonAdded;
use MyBudget\Domain\Exception\CategoryAlreadyExistsException;
use MyBudget\Domain\Exception\ExpenseCategoryNotFoundException;
use MyBudget\Domain\Exception\MatchingPersonAlreadyExistsException;
use MyBudget\Domain\Exception\PersonNotRegisteredInBudgetException;
use MyBudget\Domain\Value\Money;
use MyBudget\Infrastructure\Aggregate\AbstractAggregate;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert;

class Budget extends AbstractAggregate
{
    /** @var array|Expense[] */
    private $expenses = [];

    /** @var array|ExpenseCategory[] */
    private $expenseCategories = [];

    /** @var Money - how much money does the budget receive each month. */
    private $monthlyContribution;

    /** @var array|Person[] */
    private $persons = [];

    // ############## Domain aggregate operations  ################

    public static function createWithMonthlyContribution(Money $monthlyContribution) : self
    {
        $self = new self();

        $self->recordThat(
            new BudgetCreatedWithMonthlyContribution($monthlyContribution)
        );

        return $self;
    }

    public function changeMonthlyContribution(Money $montlyContribution) : void
    {
        Assert::notNull($this->monthlyContribution, 'Monthly contribution must be set so it can changed.');

        $this->recordThat(
            new MonthlyContributionChanged($montlyContribution)
        );
    }

    /**
     * @param string $personName
     *
     * @throws MatchingPersonAlreadyExistsException
     */
    public function addPerson(string $personName) : void
    {
        if ($this->personWithNameAlreadyExists($personName)) {
            throw new MatchingPersonAlreadyExistsException($personName);
        }

        $this->recordThat(
            new PersonAdded(Uuid::uuid4(), $personName)
        );
    }

    /**
     * @param string        $text
     * @param UuidInterface $categoryId
     * @param Money         $price
     * @param UuidInterface $purchasedBy
     * @param \DateTime     $purchasedAt
     *
     * @throws ExpenseCategoryNotFoundException
     * @throws PersonNotRegisteredInBudgetException
     */
    public function addExpense(
        string $text,
        UuidInterface $categoryId,
        Money $price,
        UuidInterface $purchasedBy,
        \DateTime $purchasedAt
    ) : void
    {
        Assert::notNull($this->monthlyContribution, 'Monthly contribution must be set for budget to receive expenses');

        if ($this->categoryOfIdExists($categoryId) === false) {
            throw ExpenseCategoryNotFoundException::fromCategoryId($categoryId);
        }

        if ($this->personWithIdExists($purchasedBy) === false) {
            throw new PersonNotRegisteredInBudgetException($purchasedBy);
        }

        $this->recordThat(
            new ExpenseAdded(
                Uuid::uuid4(),
                $text,
                $categoryId,
                $price,
                $purchasedBy,
                $purchasedAt
            )
        );
    }

    /**
     * @param string $categoryName
     *
     * @throws CategoryAlreadyExistsException
     */
    public function addExpenseCategory(string $categoryName) : void
    {
        Assert::notNull($this->monthlyContribution, 'Monthly contribution must be set for budget to receive expense categories');

        if ($this->categoryOfNameExists($categoryName) === true) {
            throw new CategoryAlreadyExistsException($categoryName);
        }

        $this->recordThat(
            new ExpenseCategoryAdded(Uuid::uuid4(), $categoryName)
        );
    }

    // ############## Public data accessors ################

    /**
     * @return array|ExpenseCategory[]
     */
    public function getExpenseCategories() : array
    {
        return $this->expenseCategories;
    }

    /**
     * @return array|Expense[]
     */
    public function getExpenses() : array
    {
        return $this->expenses;
    }

    // ############## Domain event handlers  ################

    protected function onBudgetCreatedWithMonthlyContribution(BudgetCreatedWithMonthlyContribution $event) : void
    {
        $this->monthlyContribution = $event->getMonthlyContribution();
    }

    protected function onMonthlyContributionChanged(MonthlyContributionChanged $event) : void
    {
        $this->monthlyContribution = $event->getMonthlyContribution();
    }

    protected function onPersonAddded(PersonAdded $event) : void
    {
        $this->persons[] = new Person(
            $event->getPersonId(),
            $event->getPersonName()
        );
    }

    protected function onExpenseAdded(ExpenseAdded $event) : void
    {
        $this->expenses[] = new Expense(
            $event->expenseId(),
            $event->text(),
            $event->categoryId(),
            $event->price(),
            $event->purchasedBy(),
            $event->purchasedAt()
        );
    }

    protected function onExpenseCategoryAdded(ExpenseCategoryAdded $event) : void
    {
        $this->expenseCategories[] = new ExpenseCategory($event->categoryId(), $event->categoryName());
    }

    // ############## Internal aggregate utility methods ################

    private function categoryOfNameExists(string $categoryName) : bool
    {
        return count(array_filter($this->expenseCategories, function(ExpenseCategory $expenseCategory) use ($categoryName) {
            return $expenseCategory->name() === $categoryName;
        })) > 0;
    }

    private function categoryOfIdExists(UuidInterface $categoryId) : bool
    {
        return count(array_filter($this->expenseCategories, function(ExpenseCategory $expenseCategory) use ($categoryId) {
            return $expenseCategory->id()->equals($categoryId);
        })) > 0;
    }

    private function personWithNameAlreadyExists(string $personName)
    {
        return count(
            array_filter(
                $this->persons,
                function(Person $person) use ($personName) {
                    return $person->name() === $personName;
                }
            )
        ) > 0;
    }

    private function personWithIdExists(UuidInterface $personId)
    {
        return count(
                array_filter(
                    $this->persons,
                    function(Person $person) use ($personId) {
                        return $person->id()->equals($personId);
                    }
                )
            ) > 0;
    }
}