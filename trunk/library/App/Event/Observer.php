<?php
/**
 * Kohana event observer. Uses the SPL observer pattern.
 *
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html 
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class App_Event_Observer implements SplObserver {

	// Calling object
	protected $caller;

	/**
	 * Initializes a new observer and attaches the subject as the caller.
	 *
	 * @param   object  Event_Subject
	 * @return  void
	 */
	public function __construct(SplSubject $caller)
	{
		// Update the caller
		$this->update($caller);
	}

	/**
	 * Updates the observer subject with a new caller.
	 *
	 * @chainable
	 * @param   object  Event_Subject
	 * @return  object
	 */
	public function update(SplSubject $caller)
	{
		if ( ! ($caller instanceof App_Event_Subject))
			throw new App_Exception('Attempt to attach invalid subject ' . get_class($caller) . ' to ' . get_class($this) . ' failed: Subjects must extend the Event_Subject class');

		// Update the caller
		$this->caller = $caller;

		return $this;
	}

	/**
	 * Detaches this observer from the subject.
	 *
	 * @chainable
	 * @return  object
	 */
	public function remove()
	{
		// Detach this observer from the caller
		$this->caller->detach($this);

		return $this;
	}

	/**
	 * Notify the observer of a new message. This function must be defined in
	 * all observers and must take exactly one parameter of any type.
	 *
	 * @param   mixed   message string, object, or array
	 * @return  void
	 */
	abstract public function notify($message);

} // End Event Observer