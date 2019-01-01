<?php
declare(strict_types=1);

namespace MyBudget\Domain\Exception;

class CategoryAlreadyExistsException extends \Exception
{
    /** @var string */
    private $categoryName;

    public function __construct(string $categoryName)
    {
        $this->categoryName = $categoryName;

        parent::__construct();
    }

    public function getCategoryName() : string
    {
        return $this->categoryName;
    }
}