# Specification #

Phaker is designed to be a more powerful script-runner than bog standard PHP scripts, with some 
of the features of 'make' and some pickings from 'rake' and some other environments that I like.

This document sets out the aims of Phake - how it interacts with users, developers etc.

## Aim ##

Allow coders to create scripts very quick and easily using either (nearly) native PHP, or
a library of very simple functions.

# Core #

## Code structure ##

Core code is structured using Zend naming conventions.

See CODE_STRUCTURE for details.

## CLI Helpers - doing things with files ##

All file-system interaction must be done using file-objects which provide *all* the
standard CLI functionality, but via an object interface. 

Any interaction with files must either be undoable, or we must have the ability to
warn the user that the action is not undoable.

## Phake scripts ##

A user must be able to make a new phake script quickly and easily. There will be 3 main ways of doing this:

1. 	Creating a new Phake_Script Class

2. 	Creating a bare php file (which contains only standard PHP but no classes). This can be used for making
	phake scripts out of existing PHP scripts.

3.	Recording the script using 'phake macro'

All 3 methods must result in the same functionality: 
	the ability to run the script; 
	to track all commands executed in the script;
	integration with 'phake help' to list all available scripts

Phake scripts can sit anywhere in the filesystem. Phaker will see them when the user changes an environment 
variable.

If 2 scripts have the same name then:
	The last script to be found will be used
	Scripts can override specific actions. E.g. 
		core/Myscript.php contains actions 'action1', 'action2'
		plugin/Myscript.php contains action 'action2'
		The result is:
			action1: 	served by core/Myscript.php
			action2:	served by plugin/Myscript.php

Phake scripts can call other phake scripts. (Just like in make/rake etc). The syntax for this must 
be the same as on CLI or very similar.

## Interaction with the user ##

All scripts must auto-magically have a verbose mode that shows the user everything that's being run. 

All scripts must be listed when the user types:

>	phake help commands
>	$ ...
>	$   script_name:action_name

All scripts that have documentation will have it displayed in the help screen:

>	$   script_name:action_name		[help docs go here]

All scripts must auto-magically have a 'pretend' mode which will absolutely do _nothing_.

Scripts that aren't running in 'pretend' mode must have two modes: 
	warn when running non-undoable actions
	don't warn...

Script documentation must be standard PHP documentation - no new syntax will be required.

If a script fails (i.e. throws a fatal error), the user must be presented with:
	- all commands already executed
	- all commands in the stack that let to the error.

The user must have the option to undo the entire script. 

A log mode must be available. The log must contain *everything* that happens in the script.

The log must be viewable after the script is run - e.g. view logs of scripts run yesterday.

A 'notification' method must be available. The notification method would be chosen by the user at run-time,
but can be configured by the script writer. (This is the 'tell_me(...)' idea).

## Core scripts ##

The core scripts will contain:

help:commands - List all commands

welcome messages

## Standard options ##

The following options are available for *all* scripts.

These are part of the core.

### --verbose ###

Show extra detail. This will output messages sent to Phake_Log::log()


### --quiet ###

Suppress *all* output. This will use output buffering to suppress *all* output from *all* scripts.

### --log-file ###

--log-file=path/to/file

This will push all output to the log-file. 

### --summary ###

Print a summary at the end of the script.

Format is:

>	Summary:
>	
>	Script:	'phake foo:bar --summary' was run
>	Date:	2008 Jan 12th 	12:13
>	Duration:	1hour 2mins 30seconds
>	
>	Scripts executed:
>	[1]	 	phake blar
>	[2]	 	phake foo
>	- [3]	phake bar
>	
>	Notifications:
>		Sent report to someone@example.com
>	

### --notify ###

Usage:

>	--notify someone@example.com

This will send a summary of the script to the email address when it is complete.

The sender will need option SMTP settings in case its sending from a crappy server (e.g. 1and1).

Other useful options would include:

> --include-log   - include the full logs as *an attachment*
> --message       - a message that will be included at the top of the email to explain what it's about

Also - could provide a 'digest' option, so the recipient only gets x1 email a day instead of x1 per cronjob run.

### --history (Rename this?) ###

Show all the phake scripts that have been run.

$ phake --history

  id      |  Date and time | Script      | Result   | Location
  ============================================================
  [sha1]  | 2009 Jan 11th | dummy       | Pass     | /path/to/blar
  [sha1]  | 2009 Jan 11th | something   | Pass     | /asdf/asdf

Allows the user to see everything that phake has executed in the past.

How could this link into logs - could provide log for all scripts:
> phake --log sha1
E.g.
> phake --log abc123

This information should be stored in a sqlite DB:
> ~/.phake/db/phaker.db



## Uniqueness of core options ###

These options cannot be declared by any functions, but can be used.

# ~/.phake/ # 

Contains:
- logs
- settings
- phake db (sqlite3)

# Plugin scripts #

@todo: add to this.

## Tests ##

All core functionality must be behaviour-tested.

(There isn't a requirement to test individual scripts.)



# Documenting phake scripts #

It must be easy to document scripts. 

All documentation is held in class or function documentation.



# Code structure #



## Context ##

Currently: Not clear exactly what this does


## Dispatcher ##

### Dispatch from CLI (::dispatch_cli()) ###
- This is used *only* in the phaker.php script.

The dispatcher is called from the phaker script, which passes the command line arguments to it:

Removes the 'php phaker' stuff and passes just the interesting stuff (i.e. user arguments).

> Phake_Dispatcher::dispatch_cli($argv)

Cli dispatcher then parses $argv into an array. 

### Dispatch from PHP (::dispatch_command()) ###

Used to parse calls to phake() - this might just use the _cli parser??

### Dispatch parser (private) ###

This function parses the arguments into an object that contains command, action 
and arguments.

Params:
  $dispatch_array   An array that may contain command and action and args

  Pull command from the beginning of the array.
  Pull action from beginning of array

If the command isn't known, defaults to self::default_command (help) and the
command is pushed onto the $arguments array.

!! Q: How do we find out if the command is known without loading. Perhaps the __autoload()er can
!! use something like Phake_Finder::find_class($class_name), which this class can also use??

If the action isn't known, defaults to self::default_action (index) and the
action is pushed onto the $arguments array.

All remaining arguments are added to the $arguments array.

This returns an object that defines what is to be dispatched:
  new Phake_Dispatcher_Script (
    $command,
    $action,
    $args)

### Dispatcher (private) ###

Does the actual dispatching of the command.

Requires argument:
  Phake_Dispatcher_Script (as returned by the parser argument, above)

This will create the command object, pick the method and use reflection to
find the method's arguments.

The the passed arguments known, the default ones are added to the configuration:
  verbose
  quiet
  notify
  ... etc 

If the method asks for these arguments, the it will be passed them - but only 
if the method's declaration AGREES with the default arguments. E.g. if the 
method declares "$verbose=''" (i.e. a string), then an exception is thrown:
  (Phake..ScriptIsBad)

With the required arguments known, the passed arguments are checked using the 
Zend Opt parser.



### Helper function 'phake()' ###

Can be used from anywhere in php - although the helper function will probably be used most of the time.




## Inspector / Reflector ##

Get information about Scripts:
* Methods
* Documentation
* Arguments for Methods
...

## Log ##

Phake_Log

Logging for
  general messages
  debugging
  ...
  Use standard log levels

## File handling ##

File.php

File_Collection.php

### File actions ###

chmod, copy, mkdir, mv, remove....

Either undoable or not-undoable

## Autoloader ##

Loads all Phaker_... files

Loads all Zend_... files

Tries to load any phake scripts from environment variable




