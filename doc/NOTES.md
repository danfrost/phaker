# Git #


- 	work on a branch

- 	when changes are ready, switch to master and merge in but don't commit:

>	git checkout master
>	git merge --squash mybranch

- 	Then commit all the changes in one:

>	git add [files...]
>	git commit -m "One lump of changes instead of many small changes"


# PhpDocumentor # 

>	phpdoc -d /www/phaker/lib/ -t . -o HTML:frames:earthli  -pp on -po Phaker -ti Phaker -s 
