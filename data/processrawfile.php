<?php

$file = file_get_contents("rawfile");
$chems = explode("\r\n\r\n",$file);
$chemicals = [];


foreach ($chems as $chem)
{
    $chemical = [];
    $chemical['produced']=1;
    $entries = explode("\r\n",$chem);
    foreach ($entries as $entry)
    {
        $entry = trim($entry);
        if(strstr($entry,"id ="))
        {
            $chemical['id']=trim(str_replace('"',"",explode("=", $entry)[1]));
        }
        if(strstr($entry,"name ="))
        {
            $chemical['name']=trim(str_replace('"',"",explode("=", $entry)[1]));
        }
        if(strstr($entry,"required_reagents"))
        {
            $parents=explode(",", trim(str_replace(['required_reagents = list(',")"],"",$entry)));
            foreach ($parents as $parent)
            {
                $parent = explode("=",str_replace(['"'],'', trim($parent)));
                $chemical['parents'][trim($parent[0])]=intval($parent[1]);
            }
        }
        if(strstr($entry,"result_amount"))
        {
            $chemical['produced']=intval(trim(str_replace('"',"",explode("=", $entry)[1])));
        }
        if(strstr($entry,"required_temperature = T0C + 100"))
        {
            $chemical['requiresHeat']=intval(trim(str_replace(['"','T0C + '],"",explode("=", $entry)[1])))+274;
        }
    }
    if(!isset($chemical['id']))
    {
        var_dump($chemical);
    }
    $chemicals[$chemical['id']]=$chemical;
}
file_put_contents("out.json", json_encode($chemicals));
