<?php

namespace App\ImageImport;

class FormulaireAd
{
    public function formAd()
    {
        // var_dump($_POST);
        // var_dump($_FILES);
        if ($_POST) {
            $data = array(
                'tite'        => $_POST["title"],
                'description'    => $_POST["description"],
                'user'      => $_POST["user"],
                'location'      => $_POST["location"],
                'path' =>$_FILES['image']
            );
            $options = array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => "Content-Type: application/json",
                    'ignore_errors' => true,
                    'timeout' =>  10,
                    'content' =>json_encode($data),
                ),
            );
            $context  = stream_context_create($options);

            /* Execution de la requete */
            file_get_contents('http://localhost:8000/api/ad/add', false, $context);
            return true;
        }
    }
}
