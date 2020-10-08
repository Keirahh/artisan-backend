<?php

namespace App\ImageImport;

class ImportImage
{
    public function getImage()
    {
        if (isset($_POST["importImage"])) {
            $fileName = $_FILES["file"]["tmp_name"];
            if ($_FILES["file"]["size"] > 0) {
                var_dump($fileName);
            }
        }
    }
}
