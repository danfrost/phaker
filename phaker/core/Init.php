<?php

class Phake_Script_Init extends Phake_Script
{
    function index()
    {
        echo "Generating .phake dir" . PHP_EOL;
        Phake_Vars::makePhakeDir();
        echo "Done.";
    }
}

?>