<?php

namespace App\ImageImport;

class ImageImport
{
    public function getImage()
    {
        if (isset($_POST["importImage"])) {
            $fileName = $_FILES["fileImage"]["tmp_name"];
            if ($_FILES["fileImage"]["size"] > 0) {
                var_dump($fileName);
            }
        }
    }
}
