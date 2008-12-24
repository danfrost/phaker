# Text formatting style guide #

Style guide for the formatting of CLI text in Phaker.

# Help messages #

Types of help messages:
1.	list of commands
2.	list of actions
3.	usage for specific command/action



# Messages #

Format is configurable.

*How will these things be configured??*

Can contain:
1. 	name of script / action being run		- e.g. [myscript:someaction]

2.	stack depth (using option '--forest'?)	- e.g.
	top level
	> second level
	> > third level
	> second level again

3.	Log level - e.g INFO, WARN...



# Output from script view files #

Each script can have many different view files. 

All output from commands / actions should be in the view files.

These files should output in markdown format (http://daringfireball.net/projects/markdown/syntax).

*TODO: Add how the markdown format is translated into CLI-friendly format.*

# General output #

Scripts will want to display general output to the user. 

If the full log (INFO, WARN etc) is being viewed then these messages will need to be emphasised.

Single line:

>	[script:action]				Message space starts here...

Multiple lines:

>	[script:action]		Message space starts here...		
						It continues here...
						And here again

Different color?

*This means that all scripts will have to use output buffering*

# Information Messages #

>	! [script:action]	INFO	Message starts here

# Warning messages #

Red??

>	! [script:action]	WARN	Message starts here

>	! > [file-action]	WARN	Message starts here

# Fatal error #

Red??

>	! [script:action]	WARN	Message starts here


# Interaction with the user #




# Example #

Below is an example of the output:

*TODO*