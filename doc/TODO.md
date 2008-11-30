# TODO - Tasks #

1.	PhakerScript class - this is the heart of phaker's ability to run stuff. 
		nesting, dependecy, 
		dispatcher, controllers and actions
		documentation - phaker --help etc
	
	class PhakeScript {
		
		// parent
		private $action;
		private $args = array();
		
		function __construct($action, $args) {
			$this->...
			
			$this->dispatchAction();
		}
		
		
		function __toString() {
			// Dump a human readable version of the even
		}
		
		
		
		function dispatchAction($action, $args) {
			///...
		}
		
		// child
		
		protect $config = array(
			PS::need_backup,
			PS::not_undoable
		);
		
		abstract function help() { } // This will use views/
		
		function action1() {}
		
	}
	
	phake('controller', 'action', 'args...', 'args...');

	CODE_STRUCTURE 	- implement 'lib/' for code shared between j5 and phaker
	
X	Merge J5 and phaker as per CODE_STRUCTURE
	
	Check J5 and phaker both work - write some manual tests (instructions!) for them.

#n:	

	
	*At this stage, both j5 and phaker should be working in very skeleton forms*
	
	Phake scripts
	
	Code structure for PhakeScripts
		commands/class.PhakeScript_blar.php
		views/blar/help.php << This will contain
	
	
	
	
		
			
								
			

1a.	Create template for new PhakeScripts - will make new stuff easier.


2. 	Library of standard things - 	
		read, write, move files
		manage files.
		..
		> 	Each of these creates an 'PhakeEvent()' object


3. 	Prototype of "show all files affected by the script"


		