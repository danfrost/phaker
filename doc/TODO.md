# TODO - Tasks #

Autoloader - this is in bootstrap.php. Move it.

Use Zend logger instead of echoing

Include "howto" and "beginner's guide" in the doc. Put this in source docs.
	
Build some useful scripts - these should go in a stand-alone project, installed using "phake plugin install"

better file object support in scripts. + examples in the docs
	
format help msgs like git (git help -a)
	
Add better logging (Zend?)
		* requires using Zend
		
Add new class 'Phake_Script_Request' - or rework Phake_Dispatcher_Request.
  Add
    ->getClass()  - returns the class that will be used
    ->parseForIllegalMethods()  - checks that the script doesn't call file methods directly

parse scripts and remove / disable direct file access. - reserved functions will be fopen, move, rename ...
		(Note: checkout out function re-defining, and it isn't supported so we'll just have to parse.)
		
	- 	Work out how best to install phaker. Pear? Install script? Phake script for installing?
	
Keep all file objects within a 'sandbox' - so that *no* filesystem actions can be performed outside the sandbox.
	
Provide some standard CLI options for phake itself:
		--verbose		- output logging messages
		--dry-run		- don't allow any file actions to do anything
		--file-summary	- show files-changed summary at the end of the script
		--interactive	- ask the user before doing each file action
		* requires using a CLI parser (Zend??)
	
Include zend 
		* requires using Zend for autoloading

	
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
			
		
