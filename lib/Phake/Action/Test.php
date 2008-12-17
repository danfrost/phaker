<?php

/*
 * test class
 * 
 * @todo 	Remove this
 * 
\brief	Example Phake Action.


Declare parameters in order. E.g., if you want to the syntax:
\code
f("myfile.txt")->someaction('param1', 'param2', 'param3');
\endcode

Would be:
\code
	public	$param1;
	public	$param2;
	public	$param3;
\endcode

If you want a parameter to be turned into a class, just use the class name as the default value.

\code
f("myfile.txt")->someaction('filename', 'a string', 'another-filename');
\endcode

Would be:
\code
	public	$source	= Phake_File;
	public	$contents = '';
	public	$target	= Phake_File;
\endcode

 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_Action_Test extends Phake_Action implements Phake_Action_Undoable {
	
	public $source = Phake_File;
	public $target = Phake_File;
	public $content	= '';
	
	function doAction() {
		echo "\nDoing nothing\n";
	}
	
	function undoAction() {
		echo "\nUndoing nothing\n";
	}
}

?>