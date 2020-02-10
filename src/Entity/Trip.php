<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TripRepository")
 */
class Trip
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeStart;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="date")
     */
    private $registDeadline;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbRegistMin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbRegistMax;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $info;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="user")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateTimeStart(): ?\DateTimeInterface
    {
        return $this->dateTimeStart;
    }

    public function setDateTimeStart(\DateTimeInterface $dateTimeStart): self
    {
        $this->dateTimeStart = $dateTimeStart;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRegistDeadline(): ?\DateTimeInterface
    {
        return $this->registDeadline;
    }

    public function setRegistDeadline(\DateTimeInterface $registDeadline): self
    {
        $this->registDeadline = $registDeadline;

        return $this;
    }

    public function getNbRegistMin(): ?int
    {
        return $this->nbRegistMin;
    }

    public function setNbRegistMin(int $nbRegistMin): self
    {
        $this->nbRegistMin = $nbRegistMin;

        return $this;
    }

    public function getNbRegistMax(): ?int
    {
        return $this->nbRegistMax;
    }

    public function setNbRegistMax(?int $nbRegistMax): self
    {
        $this->nbRegistMax = $nbRegistMax;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

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
            $user->addUser($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeUser($this);
        }

        return $this;
    }


}
