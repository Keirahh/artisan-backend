<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\ManyToMany(targetEntity=Zip::class, inversedBy="cities")
     */
    private $zip;

    public function __construct()
    {
        $this->zip = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Zip[]
     */
    public function getZip(): Collection
    {
        return $this->zip;
    }

    public function addZip(Zip $zip): self
    {
        if (!$this->zip->contains($zip)) {
            $this->zip[] = $zip;
        }

        return $this;
    }

    public function removeZip(Zip $zip): self
    {
        if ($this->zip->contains($zip)) {
            $this->zip->removeElement($zip);
        }

        return $this;
    }
}
