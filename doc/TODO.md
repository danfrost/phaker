# TODO - Tasks #

#n:	
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
	
	
	
	
	
	Context	- this should be == $PWD by default. Unless we're going to work in multiple contexts (i.e. directories) at once?
	
	
	
	
	Dependencies
		Phake scripts must either exit with "fail" or (by assumption) success
		
		Errors are thrown like:
			$this->error("Some problem");
		
		TODO: Reorganise how Dispatcher::print_error works. This should have a nice view somewhere. Could even be a phake script
		in itself.
		
	
	Trivial thing:
		Add:
			tell_me('dan@3ev.com');
			
		
