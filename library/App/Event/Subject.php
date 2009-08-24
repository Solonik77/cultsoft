<?php
/**
* Kohana event subject. Uses the SPL observer pattern.
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
* @author Kohana Team
* @copyright (c) 2007-2008 Kohana Team
* @license http://kohanaphp.com/license.html
*/
abstract class App_Event_Subject implements SplSubject {
    // Attached subject listeners
    protected $listeners = array();

    /**
    * Attach an observer to the object.
    *
    * @chainable
    * @param object $ Event_Observer
    * @return object
    */
    public function attach(SplObserver $obj)
    {
        if (! ($obj instanceof App_Event_Observer))
            throw new App_Exception('Attempt to attach invalid observer ' . get_class($obj) . ' to ' . get_class($this) . ' failed: Observers must extend the Event_Observer class');
        // Add a new listener
        $this->listeners[spl_object_hash($obj)] = $obj;

        return $this;
    }

    /**
    * Detach an observer from the object.
    *
    * @chainable
    * @param object $ Event_Observer
    * @return object
    */
    public function detach(SplObserver $obj)
    {
        // Remove the listener
        unset($this->listeners[spl_object_hash($obj)]);

        return $this;
    }

    /**
    * Notify all attached observers of a new message.
    *
    * @chainable
    * @param mixed $ message string, object, or array
    * @return object
    */
    public function notify($message)
    {
        foreach ($this->listeners as $obj) {
            $obj->notify($message);
        }

        return $this;
    }
} // End Event Subject