<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Entity\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Column(nullable=false)
     * @Groups({"user"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Column(nullable=false)
     * @Groups({"list"})
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=Artisan::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"user"})
     */
    private $artisan;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="user")
     * @Groups({"user"})
     */
    private $ad;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"user"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="user")
     * @Groups({"user"})
     */
    private $conversation;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class, mappedBy="user")
     * @Groups({"user"})
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Column(nullable=false)
     * @Groups({"user"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Column(nullable=false)
     * @Groups({"user"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string")
     * @ORM\Column(nullable=false)
     * @Groups({"user"})
     */
    private $birthday;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user"})
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user"})
     */
    private $location;


    public function __construct()
    {
        $this->ad = new ArrayCollection();
        $this->conversation = new ArrayCollection();
        $this->message = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getArtisan(): ?Artisan
    {
        return $this->artisan;
    }

    public function setArtisan(Artisan $artisan): self
    {
        $this->artisan = $artisan;

        // set the owning side of the relation if necessary
        if ($artisan->getUser() !== $this) {
            $artisan->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getAd(): Collection
    {
        return $this->ad;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ad->contains($ad)) {
            $this->ad[] = $ad;
            $ad->setUser($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ad->contains($ad)) {
            $this->ad->removeElement($ad);
            // set the owning side to null (unless already changed)
            if ($ad->getUser() === $this) {
                $ad->setUser(null);
            }
        }

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): self
    {
        $this->image = $image;

        // set the owning side of the relation if necessary
        if ($image->getUser() !== $this) {
            $image->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversation(): Collection
    {
        return $this->conversation;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversation->contains($conversation)) {
            $this->conversation[] = $conversation;
            $conversation->setUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversation->contains($conversation)) {
            $this->conversation->removeElement($conversation);
            // set the owning side to null (unless already changed)
            if ($conversation->getUser() === $this) {
                $conversation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessage(): Collection
    {
        return $this->message;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->message->contains($message)) {
            $this->message[] = $message;
            $message->addUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->message->contains($message)) {
            $this->message->removeElement($message);
            $message->removeUser($this);
        }

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

}
