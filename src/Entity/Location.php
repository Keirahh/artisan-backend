<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"location", "user"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=LocationZip::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"location", "user", "zip", "city","ad"})
     */
    private $zip;

    /**
     * @ORM\ManyToOne(targetEntity=LocationRegion::class, cascade={"persist", "remove"})
     * @Groups({"location", "user","ad"})
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity=LocationDepartement::class, cascade={"persist", "remove"})
     * @Groups({"location", "user","ad"})
     */
    private $departement;

    /**
     * @ORM\ManyToOne(targetEntity=LocationCity::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"location", "user", "city", "zip","ad"})
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="location")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="location")
     */
    private $ads;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->ads = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZip(): ?LocationZip
    {
        return $this->zip;
    }

    public function setZip(LocationZip $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getRegion(): ?LocationRegion
    {
        return $this->region;
    }

    public function setRegion(LocationRegion $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getDepartement(): ?LocationDepartement
    {
        return $this->departement;
    }

    public function setDepartement(LocationDepartement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getCity(): ?LocationCity
    {
        return $this->city;
    }

    public function setCity(LocationCity $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setLocation($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getLocation() === $this) {
                $user->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setLocation($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->contains($ad)) {
            $this->ads->removeElement($ad);
            // set the owning side to null (unless already changed)
            if ($ad->getLocation() === $this) {
                $ad->setLocation(null);
            }
        }

        return $this;
    }

}
