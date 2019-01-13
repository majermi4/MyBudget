<?php
declare(strict_types=1);

namespace MyBudget\Domain\Exception;

class MatchingPersonAlreadyExistsException extends \Exception
{
    public function __construct(string $personName)
    {
        parent::__construct(
            sprintf(
                'Person with matching personName "%s" already exists in budget.',
                $personName
            )
        );
    }
}