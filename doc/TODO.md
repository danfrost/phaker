# TODO - Tasks #

#now:
	Cleanup
x	-	docs for all classes (using phpdocumentor)
	
x	-	check that all header.php/common.php files don't contain crap
	
>	- 	Include "howto" and "beginner's guide" in the doc. Put this in source docs.
	
>	- 	Add a --verbose option. (subtask: support --flag style options and pass these to the scripts)
	
>	- 	Provide parameter definition support. E.g. my script needs params x, y and z.. and flags --foo and --bar.
		Do this in native PHP:
		 	function myaction($param1, $param2...);
		Parse the source for the actual variable names (this keeps everything the same)
		Give user:
			phake myscript:myaction param1 param2 
		
		*All params are method parameters:*
			function myaction($param1, $param2, $option1=true, $option2=false) {
				
			}
			... which translates into:
			$ phake myscript:myaction param1 param2 --option1 false --option2 true
			Usage:
			$ ... param1 param2 [options]
				options:
				--option1	true|false	docs here...
				--option2	true|false	docs ....
		
		Parser:
			$p = new Phake_PhpParser('Class_Name');
			$p->getMethods()
			
			$m = $p->getMethod('mymethod');
			$m->getParameters();
			Array(
				new Phake_PhpParser_Method_Parameter(
					'name',
					'type',
					'default'
					),
				
				new Phake_PhpParser_Method_Parameter(
					'name',
					'type',
					'default'
					)
				
				)
		
		*Simpler:
		
			$p = new Phake_ClassInspector('Class_Name');
				>> this parses the class. Store internally.
			
		
		
		
		*How to do optional things??*
		 	function myaction(...) {
				$arg = $this->get_option('blar');
			}
		*Perhaps declare vars?*
			$this->required(
				new Phake_Param_Int('0 - 9'),
				new Phake_Param_Email('dan@3ev.com'));
		
		*Or*
			$int = new Phake_Param_Int('0 - 9'); or:
			$int = $this->get_int_from_user('0 - 9');
			$int->confirm('Are you sure?');
		
		*Or even less*
			$int = get_option('--foo');
			$int = ask_user('blar blar');
		
	- 	better file object support in scripts. + examples in the docs
	
	-	format help msgs like git (git help -a)
	
	-	Add better logging (Zend?)
	
	- 	parse scripts and remove / disable direct file access. - reserved functions will be fopen, move, rename ...
		(Note: checkout out function re-defining, and it isn't supported so we'll just have to parse.)
		
	- 	Work out how best to install phaker. Pear? Install script? Phake script for installing?
	
	- 	Keep all file objects within a 'sandbox' - so that *no* filesystem actions can be performed outside the sandbox.
	
	-	Provide some standard CLI options for phake itself:
		--verbose		- output logging messages
		--dry-run		- don't allow any file actions to do anything
		--file-summary	- show files-changed summary at the end of the script
		--interactive	- ask the user before doing each file action
		
	-	Write text formatting style guide. Implement this in the code - humans shouldn't have to do anything.


#bug:
	- "phake help howto" throws exception. Tries to find class Phake_Script_Howto.

#next:

	* Could really do with a nice parser-wrapper. E.g. provide API like:
	
		$cmd_inspector = new Phake_Script_Inspector(Phake_Script_Dummy);
		$cmd_inspector->getClassDocs();
		$cmd_inspector->getActions();
		$cmd_inspector->getActionDocs($action);
		...
		
		This class (Phake_Script_Inspector) could contain all the parsing that needs to be done. Tokeniser etc.
		It can also implement rules for 'bare' scripts that don't have normal docs.
		
		This should replace Phake_Inspector
	
	* Context	- this should be == $PWD by default. Unless we're going to work in multiple contexts (i.e. directories) at once?
	
	
#low priority:
	
	Markdown parser for web / CLI. So that the help files (etc) look nice
	
	Consider using phpDocumentor .pkg format (althogh this might defeat the object)...
	
	Skel files / placeholders
		How is this going to work?
		
		Need to refactor the 'config' caching etc from J5 into generic library. Or merge J5 into Phake (prefer this.).
		
		Requirements:
			Ability to get arbitrary config from the user or the config cache
			>	Phake_Placeholder::get_config('some-label');
			
			I must show the user why I want this variable and what I want it for
			> 	? 
			> 	You need it for line 44 in file path/to/file.php
			
			This will all be used by a 'skel' phake class, but probably by other phake classes as well
			
		Also: how do we keep track of files generated from other files (from templates)??
		
	**	Underlying config management - .ini probably (simple to read)
		
		Caching as the user enters values
		
		Single API for getting / setting cache (plus 'read-only' mode by default)
		
		A special object like 'J5_File_ContentParser' would be nice. E.g.
			> new ConfigThingyParser(Phake_File $f)->parse()->saveTo('newfile.txt');
		
		User input must be processed for different purposes:
			Config::get('blar')->asVariable();
			Config::get('blar')->asFilename();
		
		Any code asking for the Config, must (should?) be able to provide explanation via the J5_Input_HelpException:
			try {
				Config::get('blar')->asFilename();
			} catch(J5_Input_HelpException $e) {
				// Give the user some help..
			}
			.this separates out the configuration management from the help management.
	

	
	
	Trivial thing:
		Add:
			tell_me('dan@3ev.com');
			
		
