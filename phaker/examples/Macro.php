<?php

class Phake_Script_Macro extends Phake_Script {
	
	
	private	$name	= '';
	
	private	$is_recording	= true;
	
	/**
	 * \brief	Create a new macro
	 */
	function create() {
		
		$this->context = new Phake_Context($_SERVER['PWD']);
		
		$this->name	= readline("\nName of macro:\t");
		
		$commandStack	= array();
		
		$actionStack	= array();
		
		while($cmd = readline($this->getPrompt())) {
			
			if($cmd=='pause') {
				$this->is_recording = ! $this->is_recording;
			} else 
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
				//TODO: Create an action stack that can manage multiple actions. Allow easy removal of actions
				// from the action stack. Allow items to be re-ordered. Allow undos/redos. Allow looping of actions.
				
			} else {
				$commandStack[] = $cmd;
				$actionStack[] = $this->context->cmd($cmd);
			}
		}
	}
	
	protected function getPrompt() {
		return $_SERVER['PWD']." [$this->name] ".($this->is_recording ? '!' : 'p')." $";;
	}
	
}

?>