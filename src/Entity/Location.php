<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=locationZip::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $zip;

    /**
     * @ORM\OneToOne(targetEntity=LocationRegion::class, cascade={"persist", "remove"})
     */
    private $region;

    /**
     * @ORM\OneToOne(targetEntity=LocationDepartement::class, cascade={"persist", "remove"})
     */
    private $departement;

    /**
     * @ORM\OneToOne(targetEntity=LocationCity::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZip(): ?locationZip
    {
        return $this->zip;
    }

    public function setZip(locationZip $zip): self
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
}
