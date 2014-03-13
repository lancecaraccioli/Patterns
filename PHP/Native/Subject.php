<?php

/**
 * Class Subject
 *
 * Implements the "Subject" component of the "Observer" design pattern
 *
 * [Conceptual Definitions]
 * - Subject: The 'thing' whose Events can be observed (i.e. and instance of this Class)
 * - Event: A 'happening' within a Subject, which whose occurrence is made known to interested Observers
 * - Observer: The 'thing' which is made aware of Events on a Subject for which it is interested.
 *
 * [Working Definitions]
 * - Subject: (Aliases: Observable, Publisher) an instance of this class; the object that triggers events which
 *            can callback functions can be registered for.
 * - Event: Some logical happening within the Subject which the Subject notifies the Observers about via fireEvent
 * - Observer: a callback which is executed when an Event occurs on the Subject.
 *             <author> prefers anonymous functions, but any callback will work
 *
 * @link   http://en.wikipedia.org/wiki/Observer_pattern Observer Design Pattern
 * @link   http://www.php.net/manual/en/functions.anonymous.php Anonymous Functions
 * @link   http://www.php.net/manual/en/language.types.callable.php 'callback'
 *
 * @author Lance Caraccioli
 */
class Subject
{
    /**
     * A two dimensional associative array where the key represents an Event name and the value is a
     * collection (array) of Observers (any valid php 'callback') that will be executed when that Event occurs
     *
     * Structure
     * <code>
     * $observers = array(
     *     //@var callback[]
     *     'eventName'=>array($closureInstance1, $closureInstance2, ..., $closureInstanceN);
     * );
     * </code>
     *
     * @var array[string][]callback
     */
    protected $observers = array();

    /**
     * Add an Observer interested in occurrences of an Event ($event) on this Subject.
     *
     * @param string   $event    the Event name
     * @param callback $observer Event Observer
     *
     * @link http://mootools.net/docs/core/Class/Class.Extras#Events:addEvent Analogous MooTools Class Method
     *
     * @return $this
     */
    public function addObserver($event, $observer)
    {
        array_push($this->getObservers($event), $observer);

        return $this;
    }

    /**
     * Register multiple Event Observers at once
     *
     * @param array[string]callback $observers Associative array whose key represents an Event name and whose value is
     *                              an Observer (callback) that will be executed when that Event occurs.
     *
     * @link http://mootools.net/docs/core/Class/Class.Extras#Events:addEvents Analogous MooTools Class Method
     *
     * @return $this
     */
    public function addObservers($observers = array())
    {

        if (!empty($observers)) {
            foreach ($observers as $event => $observer) {
                $this->addObserver($event, $observer);
            }
        }

        return $this;
    }

    /**
     * Internal method to simplify retrieving a 'reference' to the container which
     * stores registered Event Observers for the specified Event name ($event)
     *
     * @param string $event Event name
     *
     * @return callback[]
     */
    private function &getObservers($event)
    {
        if (empty($this->observers[$event])) {
            $this->observers[$event] = array();
        }

        return $this->observers[$event];
    }

    /**
     *
     *
     * @param string   $event    Event name
     * @param callback $observer Event Observer
     *
     * @return $this
     */
    public function removeObserver($event, $observer)
    {
        $observers = & $this->getObservers($event);
        $index     = array_search($observer, $observers);
        unset($observers[$index]);

        return $this;
    }

    /**
     * Trigger an Event
     *
     * Conceptually: Send Observers registered for this Event a notification of it's occurrence.
     * Practically: Execute each of the callback functions registered to handle the $event Event
     *
     * @param string $event     Event name
     * @param mixed  $eventData data to be broadcasts to Event Observers
     *
     * @return $this
     */
    public function fireEvent($event, $eventData = null)
    {
        foreach ($this->getObservers($event) as $observer) {
            call_user_func($observer, $eventData);
        }

        return $this;
    }
}
