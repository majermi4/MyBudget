<?php
declare(strict_types=1);

namespace MyBudget\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

/**
 * Persons associated with a budget contribute to paying its expenses.
 */
class Person
{
    /** @var UuidInterface */
    private $id;

    /** @var string - For the purposes of this app, it's ok to have a single name field. */
    private $name;

    public function __construct(
        UuidInterface $id,
        string $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public function id() : UuidInterface
    {
        return $this->id;
    }

    public function name() : string
    {

    }
}