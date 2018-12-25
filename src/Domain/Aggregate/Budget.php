<?php
declare(strict_types=1);

namespace MyBudget\Domain\Aggregate;

use MyBudget\Domain\Entity\Expense;
use MyBudget\Domain\Event\ExpenseAdded;
use MyBudget\Domain\Entity\ExpenseCategory;
use MyBudget\Domain\Event\ExpenseCategoryAdded;
use MyBudget\Domain\Exception\CategoryAlreadyExistsException;
use MyBudget\Domain\Exception\ExpenseCategoryNotFoundException;
use MyBudget\Domain\Value\Price;
use MyBudget\Infrastructure\Aggregate\AbstractAggregate;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Budget extends AbstractAggregate
{
    /** @var array|Expense[] */
    private $expenses = [];

    /** @var array|ExpenseCategory[] */
    private $expenseCategories = [];

    /**
     * @param UuidInterface $categoryId
     * @param Price         $price
     * @param Person        $purchasedBy
     * @param \DateTime     $purchasedAt
     *
     * @throws ExpenseCategoryNotFoundException
     */
    public function addExpense(
        UuidInterface $categoryId,
        Price $price,
        Person $purchasedBy,
        \DateTime $purchasedAt
    ) : void
    {
        if ($this->categoryOfIdExists($categoryId) === false) {
            throw ExpenseCategoryNotFoundException::fromCategoryId($categoryId);
        }

        $this->recordThat(
            new ExpenseAdded(
                Uuid::uuid4(),
                $categoryId,
                $price,
                $purchasedBy->id(),
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
        if ($this->categoryOfNameExists($categoryName) === true) {
            throw new CategoryAlreadyExistsException($categoryName);
        }

        $this->recordThat(
            new ExpenseCategoryAdded(Uuid::uuid4(), $categoryName)
        );
    }

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

    protected function onExpenseAdded(ExpenseAdded $event) : void
    {
        $this->expenses[] = new Expense(
            $event->expenseId(),
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
}