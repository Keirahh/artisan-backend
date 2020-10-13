<?php

namespace App\Controller;

use App\Entity\Image;
use App\ImageImport\ImageImport;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function addImage(Request $request): JsonResponse
    {
        $uploader = new ImageImport();
        $path = $uploader->upload('image');
        if ($path) {
            $img = $this->imageRepository->saveImage($path);
            $rst = $this->imageRepository->findOneBy(['path' => $path]);
            return new JsonResponse([
                'status' => "Success",
                'path' => json_decode($this->serializeDoctrineRaw($rst, "image"))
            ], 200);
        }
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
