<?php

class Phake_Script_Init extends Phake_Script
{
    function index()
    {
        echo "Generating .phake dir" . PHP_EOL;
        Phake_Vars::makePhakeDir();
        echo "Done.";
    }
    
    /**
     * Add a local phake dir - this requires .phake dir to exist
     */
    function add_local_dir($path)
    {
        echo "Adding: $path".PHP_EOL;
        f(Phake_Pwd::get().'.phake/config')
            ->append($path);
    }
}

?>