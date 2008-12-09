<?php

/**
 * events will presumably be:
	- destructive [1/0] - actually change data on the filesystem
	- undoable [1/0] - we can store the reverse of the event and run it
 * events that are destructive and non-undoable should probably show some kind of warning.
 */
class PhakeScriptEvent {
	
	static public $events	= array();
	
	static function add(PhakeScriptEvent $e) {
		self::$events[] = $e;
	}
	
	private $script;
	
	function __construct(& $PhakeScript) {
		PhakeScriptEvent::add($this);
		$this->script = & $script; 
	}
}

?>