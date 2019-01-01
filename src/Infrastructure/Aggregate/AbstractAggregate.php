<?php
declare(strict_types=1);

namespace MyBudget\Infrastructure\Aggregate;

use MyBudget\Infrastructure\Event\Event;
use ReflectionClass;

class AbstractAggregate
{
    /** @var Event[]|array */
    private $recordedEvents = [];

    final protected function __construct()
    {
        // So that no child class can be instantiated in any
        // other way than being reconstituted from events.
    }

    /**
     * @param iterable|Event[] $events
     *
     * @return AbstractAggregate
     */
    public static function fromEvents(iterable $events) : self
    {
        $self = new static();

        foreach ($events as $event) {
            $self->callEventListener($event);
        }

        return $self;
    }

    /**
     * @return array|Event[]
     */
    public function extractEvents() : array
    {
        $recordedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $recordedEvents;
    }

    protected function recordThat(Event $event) : void
    {
        $this->recordedEvents[] = $event;

        $this->callEventListener($event);
    }

    private function callEventListener(Event $event) : void
    {
        $listenerMethodName = self::getEventListenerMethodName($event);

        if (!method_exists($this, $listenerMethodName)) {
            throw new \LogicException(sprintf(
                'Could not find method called "%s" in the class "%s".',
                $listenerMethodName,
                get_class($this)
            ));
        }

        $this->$listenerMethodName($event);
    }

    private static function getEventListenerMethodName(object $event) : string
    {
        $eventReflClass = new ReflectionClass($event);
        $eventClassName = $eventReflClass->getShortName();

        return 'on'.ucfirst($eventClassName);
    }
}