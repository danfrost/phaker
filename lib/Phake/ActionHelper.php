<?php

/**
 * ActionHelper is purely for use in finding the functions in an action class. No external use.
 * @todo 		Rename "ObjectHelper" and use for inspecting Phake_Scripts also
 * @package		Phaker
 * @author		Dan Frost <dan@danfrost.co.uk>
 * @copyright 	Copyright (c) 2008, Dan Frost
 */
class Phake_ActionHelper {
	function get_class_vars($obj) {
		return get_class_vars(get_class($obj));
	}
}


?>