<?php

namespace App\Entity;

use App\Repository\ZipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZipRepository::class)
 */
class Zip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="zip", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity=City::class, mappedBy="zip")
     */
    private $city;

    public function __construct()
    {
        $this->city = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?User
    {
        return $this->code;
    }

    public function setCode(User $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|City[]
     */
    public function getCity(): Collection
    {
        return $this->city;
    }

    public function addCity(City $city): self
    {
        if (!$this->city->contains($city)) {
            $this->city[] = $city;
            $city->addZip($this);
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->city->contains($city)) {
            $this->city->removeElement($city);
            $city->removeZip($this);
        }

        return $this;
    }

}
