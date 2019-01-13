<?php
declare(strict_types=1);

namespace MyBudget\Domain\Entity;

use MyBudget\Domain\Value\Money;
use Ramsey\Uuid\UuidInterface;

class Expense
{
    /** @var UuidInterface */
    private $id;

    /** @var string */
    private $text;

    /** @var UuidInterface */
    private $categoryId;

    /** @var Money */
    private $price;

    /** @var UuidInterface */
    private $purchasedBy;

    /** @var \DateTime */
    private $purchasedAt;

    public function __construct(
        UuidInterface $id,
        string $text,
        UuidInterface $categoryId,
        Money $price,
        UuidInterface $purchasedBy,
        \DateTime $purchasedAt)
    {
        $this->id = $id;
        $this->text = $text;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->purchasedBy = $purchasedBy;
        $this->purchasedAt = $purchasedAt;
    }

    public function id() : UuidInterface
    {
        return $this->id;
    }

    public function text() : string
    {
        return $this->text;
    }

    public function categoryId() : UuidInterface
    {
        return $this->categoryId;
    }

    public function price() : Money
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
}