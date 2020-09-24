<?php

function importCsv()
{
    $test = "test";
    if (isset($_POST["import"])) {
        $fileName = $_FILES["file"]["tmp_name"];
        if ($_FILES["file"]["size"] > 0) {
            $file = fopen($fileName, "r");
           
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                var_dump($column);
            }
        }
    }
    return $test;
}
