<?php
declare(strict_types=1);

namespace MyBudget\Domain\Entity;

use MyBudget\Domain\Value\Price;
use Ramsey\Uuid\UuidInterface;

class Expense
{
    /** @var UuidInterface */
    private $id;

    /** @var UuidInterface */
    private $categoryId;

    /** @var Price */
    private $price;

    /** @var UuidInterface */
    private $purchasedBy;

    /** @var \DateTime */
    private $purchasedAt;

    public function __construct(
        UuidInterface $id,
        UuidInterface $categoryId,
        Price $price, UuidInterface
        $purchasedBy,
        \DateTime $purchasedAt)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->purchasedBy = $purchasedBy;
        $this->purchasedAt = $purchasedAt;
    }

    public function id() : UuidInterface
    {
        return $this->id;
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
}