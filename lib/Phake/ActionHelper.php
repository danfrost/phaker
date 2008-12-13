<?php

class Phake_ActionHelper {
	function get_class_vars($obj) {
		return get_class_vars(get_class($obj));
	}
}


?>