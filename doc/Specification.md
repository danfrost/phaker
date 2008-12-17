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

A 'notification' method must be available. The notification method would be chosen by the user at run-time,
but can be configured by the script writer. (This is the 'tell_me(...)' idea).

## Core scripts ##

The core scripts will contain:

help:commands - List all commands

welcome messages

# Plugin scripts #

@todo: add to this.

## Tests ##

All core functionality must be behaviour-tested.

(There isn't a requirement to test individual scripts.)




