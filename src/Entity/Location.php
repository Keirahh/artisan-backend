<?php

namespace App\Entity;

use App\Repository\LocationRepository;
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
     * @Groups({"location", "user", "zip"})
     */
    private $zip;

    /**
     * @ORM\ManyToOne(targetEntity=LocationRegion::class, cascade={"persist", "remove"})
     * @Groups({"location", "user"})
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity=LocationDepartement::class, cascade={"persist", "remove"})
     * @Groups({"location", "user"})
     */
    private $departement;

    /**
     * @ORM\ManyToOne(targetEntity=LocationCity::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"location", "user"})
     */
    private $city;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="location", cascade={"persist", "remove"})
     */
    private $user;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        // set the owning side of the relation if necessary
        if ($user->getLocation() !== $this) {
            $user->setLocation($this);
        }

        return $this;
    }
}
