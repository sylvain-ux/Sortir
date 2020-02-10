<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
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
     * @ORM\Column(type="string", length=255)
     */
    private $zipCode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TripLocation", mappedBy="city", orphanRemoval=true)
     */
    private $tripLocations;

    public function __construct()
    {
        $this->tripLocations = new ArrayCollection();
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

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return Collection|TripLocation[]
     */
    public function getTripLocations(): Collection
    {
        return $this->tripLocations;
    }

    public function addTripLocation(TripLocation $tripLocation): self
    {
        if (!$this->tripLocations->contains($tripLocation)) {
            $this->tripLocations[] = $tripLocation;
            $tripLocation->setCity($this);
        }

        return $this;
    }

    public function removeTripLocation(TripLocation $tripLocation): self
    {
        if ($this->tripLocations->contains($tripLocation)) {
            $this->tripLocations->removeElement($tripLocation);
            // set the owning side to null (unless already changed)
            if ($tripLocation->getCity() === $this) {
                $tripLocation->setCity(null);
            }
        }

        return $this;
    }
}
