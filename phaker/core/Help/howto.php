# How to create a custom script #

First, create a directory to contain your scripts:

>	mkdir ~/myscripts/

Next, set your environment variable to contain this directory:

>	export PHAKE_SCRIPTS_DIR=~/myphake/

Next, create the file:

>	~/myphake/Myscript.php

And then put the class into it:

>	class PhakeScript_Myscript extends PhakeScript {
>		function index() {
>			echo "
>				This is my script!!...
>			";
>		}
>	}

Now, when you run phake, your script is included:

>	phake help
>		help
>		...
>		myscript

To run the script, do:

>	phake myscript
>		...
>		This is my script!!...

