# TODO - Tasks #

#n:	
	Context	- this should be == $PWD by default. Unless we're going to work in multiple contexts (i.e. directories) at once?
	
	Dependencies
		Phake scripts must either exit with "fail" or (by assumption) success
		
		E.g. 
		function myfunc() {
			>>
			phake('macro', 'create');
		}
		
		
