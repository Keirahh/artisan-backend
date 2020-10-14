<?php

namespace App\ImageImport;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageImport
{
    public function upload($file)
    {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/';
        $target_file = $target_dir . basename($_FILES[$file]['tmp_name']);
        $error = false;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES[$file]['tmp_name']);
        if ($check !== false) {
            $error = false;
        } else {
            $message = 'File is not an image.';
            $error = true;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $message = 'Sorry, file already exists.';
            $error = true;
        }

        // Check file size
        if ($_FILES[$file]['size'] > 500000) {
            $message = 'Sorry, your file is too large.';
            $error = true;
        }

        // Allow certain file formats
        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
            $message = 'Sorry, only JPG, JPEG & PNG files are allowed.';
            $error = true;
        }

        // Check if $error is set to 0 by an error
        if ($error == true) {
            throw new \Exception($message);
        } else {
            if (move_uploaded_file($_FILES[$file]['tmp_name'], $target_file)) {
                return $target_file;
            }
        }
        return null;
    }
}
