<?php

namespace App\ImageImport;


class ImageImport
{
    public function upload($file)
    {
        $target_dir = "../src/ImageImport/";
        $target_file = $target_dir . basename($_FILES[$file]["name"]);
        $error = false;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES[$file]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $error = false;
        } else {
            echo "File is not an image.";
            $error = true;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $error = true;
        }

        // Check file size
        if ($_FILES[$file]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $error = true;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        ) {
            echo "Sorry, only JPG, JPEG & PNG files are allowed.";
            $error = true;
        }

        // Check if $error is set to 0 by an error
        if ($error == true) {
            echo "Sorry, your file was not uploaded.";
            return null;
            // if everything is ok, try to upload file
        }

        if (move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES[$file]["name"])) . " has been uploaded.";
            return $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }

        return null;

    }

//    public function uploadPath($file, $user)
//    {
//        $existing = $this->image_repo->findOneBy([
//            'path' => $file,
//        ]);
//
//        if (!$existing) {
//            $image = new Image();
//
//            $image->setPath($file);
//            $image->getUser($user);
//            $this->manager->persist($image);
//            $this->manager->flush();
//        }
//    }
}
