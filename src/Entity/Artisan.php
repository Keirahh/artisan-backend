<?php

namespace App\Entity;

use App\Repository\ArtisanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArtisanRepository::class)
 */
class Artisan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $activity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $company;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="artisan", cascade={"persist", "remove"})
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
     * @return int|null
     */
    public function getSiret(): ?int
    {
        return $this->siret;
    }

    /**
     * @param int $siret
     * @return $this
     */
    public function setSiret(int $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getActivity(): ?string
    {
        return $this->activity;
    }

    /**
     * @param string $activity
     * @return $this
     */
    public function setActivity(string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return $this
     */
    public function setCompany(string $company): self
    {
        $this->company = $company;

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
        $newArtisan = null === $user ? null : $this;
        if ($user->getArtisan() !== $newArtisan) {
            $user->setArtisan($newArtisan);
        }

        return $this;
    }
}
