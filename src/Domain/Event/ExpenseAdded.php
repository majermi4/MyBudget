<?php
declare(strict_types=1);

namespace MyBudget\Domain\Event;

use MyBudget\Domain\Value\Price;
use MyBudget\Infrastructure\Event\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ExpenseAdded implements Event
{
    /** @var UuidInterface */
    private $expenseId;

    /** @var UuidInterface */
    private $categoryId;

    /** @var Price */
    private $price;

    /** @var UuidInterface */
    private $purchasedBy;

    /** @var \DateTime */
    private $purchasedAt;

    public function __construct(
        UuidInterface $expenseId,
        UuidInterface $categoryId,
        Price $price,
        UuidInterface $purchasedBy,
        \DateTime $purchasedAt)
    {
        $this->expenseId = $expenseId;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->purchasedBy = $purchasedBy;
        $this->purchasedAt = $purchasedAt;
    }

    public function expenseId() : UuidInterface
    {
        return $this->expenseId;
    }

    public function categoryId() : UuidInterface
    {
        return $this->categoryId;
    }

    public function price() : Price
    {
        return $this->price;
    }

    public function purchasedBy() : UuidInterface
    {
        return $this->purchasedBy;
    }

    public function purchasedAt() : \DateTime
    {
        return $this->purchasedAt;
    }

    public function toArray() : array
    {
        return [
            'expense_id' => $this->expenseId->toString(),
            'category_id' => $this->categoryId->toString(),
            'price' => $this->price->toString(),
            'purchased_by' => $this->purchasedBy->toString(),
            'purchased_at' => json_encode($this->purchasedAt)
        ];
    }

    public static function fromArray(array $eventData) : Event
    {
        return new self(
            Uuid::fromString($eventData['expense_id']),
            Uuid::fromString($eventData['category_id']),
            Price::fromString($eventData['price']),
            Uuid::fromString($eventData['purchased_by']),
            new \DateTime(json_decode($eventData['purchased_at'], true)['date'])
        );
    }
}