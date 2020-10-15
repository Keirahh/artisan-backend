<?php

namespace App\ImageImport;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageImport
{

    public function uniqId($length = 13)
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $length);

    }

    public function upload($file)
    {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/';

        $target_file = $target_dir . $this->uniqId();

        $mime_type = mime_content_type($_FILES[$file]['tmp_name']);
        $error = false;

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

        // If you want to allow certain files
        $allowed_file_types = ['image/png', 'image/jpeg'];
        if (!in_array($mime_type, $allowed_file_types)) {
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
