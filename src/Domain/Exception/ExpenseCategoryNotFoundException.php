<?php
declare(strict_types=1);

namespace MyBudget\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class ExpenseCategoryNotFoundException extends \Exception
{
    /** @var UuidInterface */
    private $categoryId;

    private function __construct()
    {
        parent::__construct();
    }

    public static function fromCategoryId(UuidInterface $categoryId) : self
    {
        $self = new self();
        $self->categoryId = $categoryId;

        return $self;
    }

    public function getCategoryId() : UuidInterface
    {
        return $this->categoryId;
    }
}