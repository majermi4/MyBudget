<?php
declare(strict_types=1);

namespace MyBudget\Domain\Event;

use MyBudget\Domain\Value\Money;
use MyBudget\Infrastructure\Event\Event;

class MonthlyContributionChanged implements Event
{
    /** @var Money */
    private $monthlyContribution;

    public function __construct(Money $monthlyContribution)
    {
        $this->monthlyContribution = $monthlyContribution;
    }

    public function getMonthlyContribution() : Money
    {
        return $this->monthlyContribution;
    }

    public function toArray() : array
    {
        return [
            'monthlyContribution' => $this->monthlyContribution->toString(),
        ];
    }

    public static function fromArray(array $eventData) : Event
    {
        return new self(
            Money::fromString($eventData['monthlyContribution'])
        );
    }
}