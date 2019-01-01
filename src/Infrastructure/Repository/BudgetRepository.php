<?php
declare(strict_types=1);

namespace MyBudget\Infrastructure\Repository;

use MyBudget\Domain\Aggregate\Budget;
use MyBudget\Infrastructure\Event\Event;
use Symfony\Component\HttpKernel\KernelInterface;

class BudgetRepository implements \MyBudget\Domain\Repository\BudgetRepository
{
    /** @var string */
    private $eventStoreFilePath;

    public function __construct(KernelInterface $kernel)
    {
        $this->eventStoreFilePath = $kernel->getProjectDir().'/EventStore/budget.json';
    }

    public function getBudget() : Budget
    {
        $storedEventsData = $this->getStoredEventsData();

        /** @var Budget $budget */
        $storedEvents =
            array_map(
                function(array $eventData) {
                    $eventClass = $eventData['event_class'];

                    if (!is_subclass_of($eventClass, Event::class)) {
                        throw new \LogicException(
                            sprintf(
                                'Class "%s" read from the event store must implement "%s".',
                                $eventClass,
                                Event::class
                            )
                        );
                    }

                    return $eventClass::fromArray($eventData);
                },
                $storedEventsData
            );

        /** @var Budget $budget */
        $budget = Budget::fromEvents($storedEvents);

        return $budget;
    }

    public function storeBudget(Budget $budget) : void
    {
        $extractedEvents = $budget->extractEvents();
        $storedEvents = $this->getStoredEventsData();

        foreach ($extractedEvents as $event) {
            $eventData = $event->toArray();
            $eventData['event_class'] = get_class($event);

            $storedEvents[] = $eventData;
        }

        file_put_contents($this->eventStoreFilePath, json_encode($storedEvents));
    }

    private function getStoredEventsData() : array
    {
        if (!file_exists($this->eventStoreFilePath)) {
            return [];
        }

        return json_decode((string) file_get_contents($this->eventStoreFilePath), true);
    }
}