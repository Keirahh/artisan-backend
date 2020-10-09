<?php

namespace App\ImageImport;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;


class ImageImport
{
    private $manager;
    private $image_repo;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->image_repo = $this->manager->getRepository('App\Entity\Image');
    }

    public function upload()
    {
        if (isset($_POST["importImage"])) {
            $target_dir = "../src/ImageImport/";
            $target_file = $target_dir . basename($_FILES["fileImage"]["name"]);
            $error = false;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileImage"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $error = false;
                } else {
                    echo "File is not an image.";
                    $error = true;
                }
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $error = true;
            }

            // Check file size
            if ($_FILES["fileImage"]["size"] > 500000) {
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
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileImage"]["tmp_name"], $target_file)) {
                    echo "The file " . htmlspecialchars(basename($_FILES["fileImage"]["name"])) . " has been uploaded.";
                    $userId = 2;
                    $this->uploadPath($_FILES["fileImage"]["name"], $target_file, $userId);
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }

    public function uploadPath($file, $user)
    {
        $existing = $this->image_repo->findOneBy([
            'path' => $file,
        ]);

        if (!$existing) {
            $image = new Image();

            $image->setPath($file);
            $image->getUser($user);
            $this->manager->persist($image);
            $this->manager->flush();
        }
    }
}
