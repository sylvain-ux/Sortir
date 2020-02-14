<?php

namespace App\Entity;

use App\Entity\School;
use App\Entity\Trip;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 6, minMessage = "Your password must be at least {{ limit }} characters long")
     */
    private $password;


//    /**
//     *
//     * !! nouvelle propriété de user qui ne correspond à aucune colonne
//     * !! Cette propriété est indispensable pour le changement de mot de passe
//     *
//     * @SecurityAssert\UserPassword(
//     *     message = "Wrong value for your current password"
//     * )
//     */
//     protected $oldPassword;
//
//    /**
//     * @return mixed
//     */
//    public function getOldPassword()
//    {
//        return $this->oldPassword;
//    }
//
//    /**
//     * @param mixed $oldPassword
//     */
//    public function setOldPassword($oldPassword): void
//    {
//        $this->oldPassword = $oldPassword;
//    }

    /**
     * @Assert\Image(
     *     maxWidth = 1000,
     *     maxHeight = 1000,
     * )
     * @ORM\Column(type="string", length=1024)
     */

    private $avatar;

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

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

    /**
     * @ORM\Column(type="array")
     */
    private $roles = ['ROLE_USER'];

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
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
