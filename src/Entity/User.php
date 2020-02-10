<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
<<<<<<< HEAD
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
=======
>>>>>>> 8d549f1b49a46d9fc0edf7740a6c972a4ca5233f
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
<<<<<<< HEAD
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
=======
 * @UniqueEntity(fields={"email"}, message="There is already an account with this pseudo")
 */
class User implements userInterface

>>>>>>> 8d549f1b49a46d9fc0edf7740a6c972a4ca5233f
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Trip", inversedBy="users")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trip", mappedBy="user", orphanRemoval=true)
     */
    private $organizer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $school;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->organizer = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Trip $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(Trip $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getOrganizer(): Collection
    {
        return $this->organizer;
    }

    public function addOrganizer(Trip $organizer): self
    {
        if (!$this->organizer->contains($organizer)) {
            $this->organizer[] = $organizer;
            $organizer->setUser($this);
        }

        return $this;
    }

    public function removeOrganizer(Trip $organizer): self
    {
        if ($this->organizer->contains($organizer)) {
            $this->organizer->removeElement($organizer);
            // set the owning side to null (unless already changed)
            if ($organizer->getUser() === $this) {
                $organizer->setUser(null);
            }
        }

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


    /**
     * @inheritDoc
     */
    public function getRoles()
    {
<<<<<<< HEAD
        // TODO: Implement getRoles() method.
    }

=======
        if (empty($this->roles)){
            $this->roles = ['ROLE_USER'];
        }
        return $this-> roles;
    }

    /**
     * @inheritDoc
     */
    public function setRoles(array $roles): self
    {
        $this -> roles = $roles;
        return $this;

    }




>>>>>>> 8d549f1b49a46d9fc0edf7740a6c972a4ca5233f
    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
<<<<<<< HEAD
        // TODO: Implement getUsername() method.
=======
        return $this->email;
>>>>>>> 8d549f1b49a46d9fc0edf7740a6c972a4ca5233f
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
