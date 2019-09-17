<?php

function mount()
{
    //delete all current mapping
    // exec("net use * /delete /y");
    //create a new mapping to local X: drive
    $path = SHAREFOLDER;
    $domine = DOMINE;
    $username = USERNAME;
    $password = PASSWORD;
    exec('net use X: '.$path.' /user:'.$domine.'\\'.$username.' '.$password.' /persistent:no 2>&1', $output, $return_var);
}
