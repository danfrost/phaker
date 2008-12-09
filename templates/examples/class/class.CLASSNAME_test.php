&lt;?php
class <?= $this->project->asVariable() ?>_<?= $this->class->asVariable() ?> extends SomeClass {
	
	private $myvar = '<?= $this->myvar('one', 'two', 'three')->asVariable(); ?>';
	
	function doSomething() {
		
		$email = '<?= $this->email ?>';
		
	}
}
?&gt;