<?php

namespace App\Entity;

use App\Repository\AdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Utilities;

use function App\Utilities\formatDate;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 */
class Ad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ad"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ad"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ad"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="ad")
     * @Groups({"ad"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ad")
     * @Groups({"ad"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ad"})
     */
    private $location;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"ad"})
     */
    private $createdAt;

    /**
     * Ad constructor.
     */
    public function __construct()
    {
        $this->image = new ArrayCollection();
    }

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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function removeImage(Image $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

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

        return $this;
    }

    /**
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location|null $location
     * @return $this
     */
    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCreatedAt(): ?array
    { 
        return formatDate($this->createdAt);
    }

    /**
     * @param \DateTimeInterface|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
