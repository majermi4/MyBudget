<?php
declare(strict_types=1);

namespace MyBudget\Domain\Event;

use MyBudget\Infrastructure\Event\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PersonAdded implements Event
{
    /** @var UuidInterface */
    private $personId;

    /** @var string */
    private $personName;

    public function __construct(
        UuidInterface $personId,
        string $personName)
    {
        $this->personId = $personId;
        $this->personName = $personName;
    }

    public function getPersonId() : UuidInterface
    {
        return $this->personId;
    }

    public function getPersonName() : string
    {
        return $this->personName;
    }

    public function toArray() : array
    {
        return [
            'person_id' => $this->personId->toString(),
            'person_name' => $this->personName,
        ];
    }

    public static function fromArray(array $eventData) : Event
    {
        return new self(
            Uuid::fromString($eventData['person_id']),
            $eventData['person_name']
        );
    }
}