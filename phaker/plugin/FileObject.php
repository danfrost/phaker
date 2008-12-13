<?php

/**
 * 
 */
class Phake_Script_FileObject extends Phake_Script {
	
	function index() {
		Phake_File::$context = new Phake_Context(dirname(__FILE__).'/tmp/');
		
		$f = f('myfile.txt')->touch();
		
		//$f->rename('otherfile.txt');
		
		$f->test('filename.txt', 'this is a string');
	}
	
	function blar() {
		
		$config = f('myfile.ini')->as_array();
		
		$config['test'] = 'asdfasdf';
		
		$f = f('myotherfile.ini')->write($config);
		
		$f = $f->rename('blar.txt');
		
		$f->backup()->send_to('me@example.com');
		
	}	
}

?>