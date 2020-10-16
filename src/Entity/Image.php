<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ad","image"})
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="image")
     */
    private $ad;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="image", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Ad|null
     */
    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    /**
     * @param Ad|null $ad
     * @return $this
     */
    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newImage = null === $user ? null : $this;
        if ($user->getImage() !== $newImage) {
            $user->setImage($newImage);
        }

        return $this;
    }
}
