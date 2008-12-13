<?php


class Phake_Action_Test extends Phake_Action implements Phake_Action_Undoable {
	
	/**
	 * You declare parameters in order. If you want a parameter to be turned into a class, just use the class name as the default value.
	 */
	public $source = Phake_File;
	public $target = Phake_File;
	public $content	= '';
	
	function doAction() {
	}
	
	function undoAction() {
	}
}

?>