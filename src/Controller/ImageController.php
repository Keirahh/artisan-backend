<?php

namespace App\Controller;

use App\Entity\Image;
use App\ImageImport\ImageImport;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api/image")
 */
class ImageController extends ApiController
{
   /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * ImageController constructor.
     * @param SerializerInterface $serializer
     * @param ImageRepository $imageRepository
     */
    public function __construct(SerializerInterface $serializer, ImageRepository $imageRepository)
    {
        parent::__construct($serializer);
        $this->imageRepository = $imageRepository;
    }

    /**
     * @Route("/add", name="add_image", methods={"POST"})
     */
    public function addImage(Request $request)
    {
        $uploader = new ImageImport();
        $path = $uploader->upload('image');
    }

    /**
     * @Route("/{id}", name="get_image", methods={"GET"})
     */
    public function getImage($id): Response
    {
        return $this->serializeDoctrine($this->imageRepository->find($id), 'image');
    }

    public function getEntity($id)
    {
        return $this->imageRepository->find($id);
    }
}
