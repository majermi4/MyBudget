<?php
declare(strict_types=1);

namespace MyBudget\Domain\Event;

use MyBudget\Infrastructure\Event\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ExpenseCategoryAdded implements Event
{
    /** @var UuidInterface */
    private $categoryId;

    /** @var string */
    private $categoryName;

    public function __construct(UuidInterface $categoryId, string $categoryName)
    {
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
    }

    public function categoryId() : UuidInterface
    {
        return $this->categoryId;
    }

    public function categoryName() : string
    {
        return $this->categoryName;
    }

    public function toArray() : array
    {
        return [
            'category_id' => $this->categoryId->toString(),
            'category_name' => $this->categoryName,
        ];
    }

    public static function fromArray(array $eventData) : Event
    {
        return new self(
            Uuid::fromString($eventData['category_id']),
            $eventData['category_name']
        );
    }
}