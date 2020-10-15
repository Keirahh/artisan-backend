<?php

namespace App\Controller;

use App\Entity\Image;
use App\ImageImport\ImageImport;
use App\Repository\AdRepository;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * AdController constructor.
     * @param AdRepository $adRepository
     * @param ImageRepository $imageRepository
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(AdRepository $adRepository, ImageRepository $imageRepository, UserRepository $userRepository, SerializerInterface $serializer)
    {
        $this->adRepository = $adRepository;
        $this->imageRepository = $imageRepository;
        $this->userRepository = $userRepository;
        parent::__construct($serializer);
    }

    /**
     * @Route("/add", name="add_ad", methods={"POST"})
     */
    public function addAd(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dataset = ['title', 'description', 'token', 'location'];

        foreach ($dataset as $property) {
            if (empty($data[$property])) {
                throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$property] . ')');
            }
        }

        $title = $data['title'];
        $description = $data['description'];
        $user = $this->userRepository->findOneBy(['token' => $data['token']]);
        $location = $data['location'];

        if ($user) {
            $ad = $this->adRepository->saveAd($title, $description, $user->getId(), $location);

            if ($data['path']) {
                $this->imageRepository->saveAdImage($data['path'], $ad);
            }

            return new JsonResponse([
                'status' => "Success"
            ], 200);
        }
        return new JsonResponse([
            'message' => 'no user found'
        ], 400);
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

        if ($title) {
            return $this->serializeDoctrine($this->adRepository->findByTitle($page, $limit, $title), 'ad');
        } else {
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
     * @Route("/myAd", name="get_my_ad", methods={"GET"})
     */
    public function getMyAd(): Response
    {
        $token = $_GET['token'];
        $user = $this->userRepository->findOneBy(['token' => $token]);
        if ($user) {
            return $this->serializeDoctrine($this->adRepository->findByUserId($user->getId()), 'ad');
        }
        return new JsonResponse([
            'message' => 'no user found'
        ], 400);
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
