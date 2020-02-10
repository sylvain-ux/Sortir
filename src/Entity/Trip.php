<?php

namespace App\Entity;

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
}
