<?php
declare(strict_types=1);

namespace MyBudget\Tests\Behavioral;

use Behat\Behat\Context\Context;
use MyBudget\Domain\Aggregate\Budget;
use MyBudget\Domain\Exception\CategoryAlreadyExistsException;
use MyBudget\Domain\Value\Money;
use Webmozart\Assert\Assert;

class FeatureContext implements Context
{
    /** @var Budget */
    private $budget;

    /** @var bool */
    private $addingCategoryFailed;

    /** @BeforeScenario */
    public function before($event)
    {
        $this->budget = Budget::createWithMonthlyContribution(
            new Money(5000)
        );

        $this->addingCategoryFailed = false;
    }

    /**
     * @When an expense :categoryName category is added
     */
    public function anExpenseCategoryIsAdded($categoryName)
    {
        try {
            $this->budget->addExpenseCategory($categoryName);
        } catch (CategoryAlreadyExistsException $e) {
            $this->addingCategoryFailed = true;
        }
    }

    /**
     * @Then budget should contain category :categoryName
     */
    public function budgetShouldContainCategory($categoryName)
    {
        $expenseCategories = $this->budget->getExpenseCategories();
        Assert::count($expenseCategories, 1);

        $latestExpenseCategory = $expenseCategories[0];
        Assert::same($categoryName, $latestExpenseCategory->name());
    }

    /**
     * @Given budget has no :categoryName expense category
     */
    public function budgetHasNoExpenseCategory($categoryName)
    {
        foreach ($this->budget->getExpenseCategories() as $expenseCategory) {
            Assert::notSame($categoryName, $expenseCategory->name());
        }
    }

    /**
     * @Given budget has :categoryName expense category
     */
    public function budgetHasExpenseCategory($categoryName)
    {
        $this->budget->addExpenseCategory($categoryName);
    }

    /**
     * @Then adding expense category should have failed
     */
    public function addingExpenseCategoryShouldHaveFailed()
    {
        Assert::true($this->addingCategoryFailed);
    }

}
