<?php
declare(strict_types=1);

namespace MyBudget\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class PersonNotRegisteredInBudgetException extends \Exception
{
    public function __construct(UuidInterface $personId)
    {
        parent::__construct(
            sprintf(
                'Person with id "%s" has not been registered to the budget.',
                $personId->toString()
            )
        );
    }
}