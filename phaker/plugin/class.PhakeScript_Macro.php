<?php

class PhakeScript_Macro extends PhakeScript {
	
	function create() {
		
		$this->context = new CliContext($_SERVER['PWD']);
		
		$name	= readline("\nName of macro:\t");
		
		$commandStack	= array();
		
		$actionStack	= array();
		
		while($cmd = readline("\n:~/")) {
			
			if(substr($cmd,0,1)=='\\') {
				
				if(substr($cmd, 0, 3)=='\\rm') {
					echo PHP_EOL."Removing: $cmd".PHP_EOL;
					$i = (int) trim(str_replace('\\rm', '', $cmd));
					unset($commandStack[$i]);
					$commandStack = array_values($commandStack);
				}
				
				echo PHP_EOL."Commands so far:".PHP_EOL;
				foreach($commandStack as $i=>$c) {
					echo "[$i]\t$c".PHP_EOL;
				}
				
				//$actionStack 
				/**
				 * TOOD: Create an action stack that can manage multiple actions. Allow easy removal of actions
				 * from the action stack. Allow items to be re-ordered. Allow undos/redos. Allow looping of actions.
				 */
				
			} else {
				$commandStack[] = $cmd;
				$actionStack[] = $this->context->cmd($cmd);
			}
		}
	}
	
}

?>