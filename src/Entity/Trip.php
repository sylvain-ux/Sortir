<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @Assert\GreaterThan("+2 day", message="La sortie doit être programmée au moins dans 2 jours")
     */
    private $dateTimeStart;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\LessThan(propertyPath="dateTimeStart", message="La date doit être inférieure à la date de la sortie")
     */
    private $registDeadline;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThanOrEqual(propertyPath="nbRegistMax", message="le nombre mini. doit être inférieur ou égal au nombre maxi.")
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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="organizer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="trip")
     * @ORM\JoinColumn(nullable=false)
     */
    private $school;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="trips")
     * @ORM\JoinColumn(nullable=true)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TripLocation", inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reason;

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

    public function removeTrip(int $trip): self
    {
        if ($this->contains($trip)) {
            $this->removeElement($trip);
            $trip->removeTrip($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getLocation(): ?TripLocation
    {
        return $this->location;
    }

    public function setLocation(?TripLocation $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }


    /**
     * Function whose check if the current user is already subscribed in the trip and check if this user is not the organizer
     * @param UserInterface $user
     * @return bool
     */
    public function isSubscribed(UserInterface $user)
    {
        foreach ($this->getUsers() as $us){
            // Si l'utilisateur connecté est déjà inscrit pour la sortie et qu'il n'est pas l'organisateur de celle-ci, il peut se désister
            if($us->getId() == $user->getId() && $this->getUser()->getId() != $user->getId()){
                return true;
            }
        }
        return false;
    }


    /**
     * Function whose check if the current user is not yet subscribed to the trip so he can subscribed for tit.
     * @param UserInterface $user
     * @return bool
     */
    public function isNotSubscribed(UserInterface $user)
    {
        foreach ($this->getUsers() as $us){
            if($us->getId() != $user->getId()){
                return true;
            }
        }
        return false;
    }


    /**
     * function check the status of the trip : in progress to see the details or closed
     * @param int $stateId
     * @return bool
     */
    public function tripInProgressOrClosed(int $stateId)
    {
        if($stateId == 4 or $stateId == 3){
            return true;
        }
        return false;
    }

    /**
     * function whose offer the possibility to modify the trip choices
     * @param int $stateId
     * @param UserInterface $user
     * @return bool
     */
    public function tripCreation(int $stateId, UserInterface $user)
    {
        if($stateId == 1 && $this->getUser()->getId() == $user->getId()){
            return true;
        }
        return false;
    }

    /**
     * function to make a trip passed
     * @param int $stateId
     * @return bool
     */
    public function tripPast(int $stateId)
    {
        if($stateId == 5){
            return true;
        }
        return false;
    }

    /**
     * function to open the status of a trip
     * @param int $stateId
     * @return bool
     */
    public function tripOpen(int $stateId)
    {
        if($stateId == 2){
            return true;
        }
        return false;
    }


    /**
     * Function whose change the status to close for a trip which is complete.
     */
    public function StatusClosed(int $nbInscrit, int $nbMaxInscrit)
    {

    }
}
