#usage#
	phaker	> this will create a phaker directory for you. Alias of 'phaker init'
	
	phaker	class:command  	options...
	
	phaker	class:command 	--help
	
	phaker	class:command 	--pretend
	
	
Core stuff:

	No Args:
	phaker
	  [core]
	    phaker:welcome
	    phaker:...
	    config:set...
	
	  [plugin]
	    ...:...
	  
	  [etc]
		help	show this help msg
		config	show config
		db		...
	
	
	phaker config
		config:set		set config
		config:get		get config
		config:show		show config
		config:flush	flush config
	
	
	phaker build
		build:full
	
	
	This requires:
		Knowing all the commands (classes etc)
			- do this by parsing all phaker directories / files. Load all classes into memory??
		
		Get docs for each class / function
	
	















Phake-script:
	class MyPhakeScript extends PhakeScript {
		
		function command1() {
			
		}
		
		function command2() {
			
		}
	}


Features:
	Interaction
	..for config
	Configuration - store, cache, and easily distribute / move configuration. 
	Auto-create required directories
	(core) Fast file management - use sqlite3 to keep track of files/directories for rapid file/directory management.

Utilities - things it can do:

	Dependencies		(core)
	Archiving / Tarring (plugin)
	Skeleton files		(plugin)
	DB Dumping			(plugin)
	Upgrades (like Don's system)
	Auto-committing 	(would just be a plugin)
	Profiling 			(")
	Documentation		(")

And:
	Setup arbitrary environments (TYPO3, wordpress...) for running scripts. E.g.
	>	phake	typo3	path/to/script.php
	Or, head of script.php can be:
	>	#!/usr/bin/phake typo3
	>	#!/usr/bin/phake wordpress

	Show *all* files affected by the events:
	>	# Files affected by this phake:
	>		path/to/file.txt		Modified		MyScript:update_config	line 123
	>		path/to/another.php 	Created			MyScript:generate_skeleton	line 234
	>		path/to/something.log	Deleted			MyScript:clear_cache line 345
	>	# DB Queries in this phake: (requires everything going through an API)
	>		INSERT...			MyScript:foo
	>		DELETE...			MyScript:bar
	

# lib/: #

class.Phaker.php
  (Include CLI Dispatcher as in J5)


  j5/ (Include this as part of phaker)
  
  templates/ 	- J5 templates
	phaker/phaker/ - template for a project's phake scripts
			class.PROJECT_Phaker.php
			config.ini
	phaker/script/
			class.PhakeScript_NAME.php
	phaker/macro/
			class.PhakeMacroScript_NAME.php

  modules/
	**All these will be created using "phake phakemodule:new"**
	mysql/
			phake	mysql:dump
			phake	mysql:create
			phake	mysql:...
	archiving/
			phake 	archive:tar
			phake 	archive:full
			phake 	archive:snapshot
	skel/
			phake	skel:transform
	SVN/
			phake	svn:commit
			phake	svn:update
	documentor/
			phake	doc:configure	- interactive config
	profile/
			phake	profile:...
	cronjob/
			phake	cronjob:create	- for creating script intended to be run at particular times. These automatically implement locking, logging etc.
			phake	cronjob:run

# Using Phake within PHP #
phake('mysql:dump', list, of, arguments... );



# starting #
>	phaker init
1. create required directories (logs, config, ...etc)
2. setup project config - name, description, technical lead etc
3. setup "phake" directory. 


# Making your ow scripts #
phaker	script:new
> What's name shall we call it?		>> Used for classname
> Do you want multiple actions - e.g. mysql:dump, mysql:load... ?
> Write a short description of what your script will do
> What parameters does your script take?...
>> This builds the script:

class MyScript_Phaker extends PhakerScript {
	
	function command() {
		$phake_event = phake('project:backup'); // Make sure there's a backup first
		
		p::fwrite('blar.txt');
		p::fread('blar.txt');
		p::fcreate('blar.txt');
		
		p::sql('');
		
		p::db_dump();
		p::data();
		p::backup...();
		
		p::symlink('source', 'target');
		
		p::exec(); //< Shouldn't be used - everything should go through APIs/wrappers
		
		// Run other scripts from this one:
		phake('mysql:dump', 'output file...'); // Note that, if any arguments are missing, we'll just get them from the user!
		
		do_y();
		if($this->opts->opt1=='y') { // done dynamically - pulls input from the CLI
			...
		} else {
			$phake_event->revert(); // This phake event can be undone
		}
	}
}


# Extension to phake: phake groups #

Groups of files. 

>	phake 	group:list
>	phake 	group:new
>	phake 	group:enter - start using the group. This enters a wrapper shell - the only commands known are (below)... everything else gets
 						pushed back into BASH>
>	~/ l 		- list
>	~/ add		- add a file
>	~/ remove 	- remove file from group
>	~/ merge	- merge a group into the current group


Add a file to a group add any time:

>	cd path/to/blar
>	phake	group:add	mygroup		myfile.txt

Backup a group:
>	phake 	group:backup	
This can either be done using tarballs or using a versioning tool to keep track of the files.




# Extension: Macros #

Useful for creating scripts quickly>

>	phake	macro:new	
>	[phake]:/ What shall we call it?
>	[phake]:/ What does it do?
>	...start
>	(Do stuff normally...)
>	phake	macro:end	- to end

Commands are stored in a phake script file:
	phake/macros/class.PhakeMacro_my_macro.php
	>>
	function run() {
		phake::cmd('rm -f logs/* backups/*');
		phake::cmd('tar czf backups/full-backup.tgz htdocs/ config/');
		phake::cmd('scp backups/full-backup.tgz user@server:~/');
	}


Because the phake macro becomes a phake script, it can be used just like all the other scripts:
>	phake	help
>	[p]	mysql:...
>	[p]	group:...
>	[p]	custom:my_macro		"Does some stuff specific to this project."
>	[p]	make:db_dump		"Wrapper for make db_dump"
>	[p]	make:all			"Wrapper for make all"




The script can be edited to add variables:
>	function run() {
>		phake::cmd('rm -f logs/* backups/*');
>		phake::cmd('tar czf backups/'.$this->backup_name->toFilename();.'.tgz htdocs/ config/');
>		phake::cmd('scp backups/'.$this->backup_name->toFilename();.'.tgz user@server:~/');
>	}


Of course, you can use phake macros/scripts (and anything else) within phake macros:
>	phake	macro:new	make_db_dump --description "Wrapper for make db_dump"
>	# STARTING...
>	[phake macro "make_db_dump"]:~/		phake	mysql:backup
>	[phake macro "make_db_dump"]:~/		make db_dump



