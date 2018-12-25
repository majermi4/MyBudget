<?php
declare(strict_types=1);

namespace MyBudget\Domain\Repository;

use MyBudget\Domain\Aggregate\Budget;

interface BudgetRepository
{
    public function getBudget() : Budget;

    public function storeBudget(Budget $budget) : void;
}