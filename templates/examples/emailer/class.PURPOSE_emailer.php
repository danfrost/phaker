&lt;?php

require_once	dirname(__FILE__).'/class.emailer.php';

class <?= $this->purpose ?>_emailer extends emailer {
	
	protected	$recipient	= '<?= $this->recipient ?>';
	
}

?&gt;