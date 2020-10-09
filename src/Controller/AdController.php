<?php

namespace App\Controller;

use App\Entity\Image;
use App\ImageImport\ImageImport;
use App\Repository\AdRepository;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api/ad")
 */
class AdController extends ApiController
{
    /**
     * @var AdRepository
     */
    private $adRepository;
    /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * AdController constructor.
     * @param AdRepository $adRepository
     * @param ImageRepository $imageRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(AdRepository $adRepository, ImageRepository $imageRepository, SerializerInterface $serializer)
    {
        $this->adRepository = $adRepository;
        $this->imageRepository = $imageRepository;
        parent::__construct($serializer);
    }

    /**
     * @Route("/add", name="add_ad", methods={"POST"})
     */
    public function addAd(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        $dataset = ['title', 'description', 'user'];

        foreach ($dataset as $property) {
            if (empty($data[$property])) {
                throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$property] . ')');
            }
        }

        $title = $data['title'];
        $description = $data['description'];
        $user = $data['user'];

        $ad = $this->adRepository->saveAd($title, $description, $user);


        if($_FILES['image'])
        {
            $uploader = new ImageImport();
            $path = $uploader->upload('fileImage');

            if($path) {
                $img = $this->imageRepository->saveAdImage($path, $ad);
            }
        }
//        if ($data['path']) {
//            $path = $data["path"];
//            $this->imageRepository->saveImage($ad, $path);
//        }
        return new JsonResponse([
            'status' => "Success"
        ], 200);
    }

    /**
     * @Route("/ads", name="get_ads", methods={"GET"})
     */
    public function getAds(): Response
    {
        $title = $_GET['title'];
        $page = $_GET['page'];

        if (is_null($page) || $page < 1) {
            $page = 1;
        }
        $limit = 9;

        if($title)
        {
            return $this->serializeDoctrine($this->adRepository->findByTitle($page, $limit, $title), 'ad');
        }
        else
        {
            return $this->serializeDoctrine($this->adRepository->findAll(), 'ad');
        }
    }

    /**
     * @Route("/recent_ads", name="get_recent_ads", methods={"GET"})
     */
    public function getRecentAds(): Response
    {
        return $this->serializeDoctrine($this->adRepository->findRecent(), 'ad');
    }


    /**
     * @Route("/{id}", name="get_ad", methods={"GET"})
     */
    public function getAd($id): Response
    {
        return $this->serializeDoctrine($this->adRepository->find($id), 'ad');
    }

    public function getEntity($id)
    {
        return $this->adRepository->find($id);
    }
}
