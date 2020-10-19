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
use OpenApi\Annotations as OA;

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
     * Create image
     * 
     * It is necessary to have the identification token to be able to retrieve the image
     * 
     * @OA\Parameter(
     *     name="path",
     *     in="query",
     *     description="The field used to set image",
     *     @OA\Schema(type="file")
     * )
     * 
     * @Route("/add", name="add_image", methods={"POST"})
     */
    public function addImage(Request $request): JsonResponse
    {
        $uploader = new ImageImport();
        $path = $uploader->upload('image');
        if ($path) {
            $img = $this->imageRepository->saveImage($path);
//            $rst = $this->imageRepository->findOneBy(['path' => $path]);
            return new JsonResponse([
                'status' => "Success",
                'path' => $path
            ], 200);
        }

        return new JsonResponse([
            'status' => "Failed",
            'error' => "Image upload failed"
        ], 400);
    }

    /**
     * Get Image by Id
     * 
     * @Route("/{id}", name="get_image", methods={"GET"})
     */
    public function getImage($id): Response
    {
        return $this->serializeDoctrine($this->imageRepository->find($id), 'image');
    }

    /**
     * @param $id
     * @return Image|null
     */
    public function getEntity($id)
    {
        return $this->imageRepository->find($id);
    }
}
