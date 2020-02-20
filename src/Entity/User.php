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
 * @UniqueEntity(fields={"email"}, message="L'adresse {{ value }} existe déjà.")
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
     * @Assert\Email(message = "L'adresse '{{ value }}' n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 6, minMessage = "Your password must be at least {{ limit }} characters long")
     */
    private $password;

    /**
     * @Assert\Image(
     *     maxWidth = 2000,
     *     maxHeight = 2000,
     *     maxWidthMessage="La largeur de votre photo est trop grande",
     *     maxHeightMessage="La hauteur de votre photo est trop grande"
     * )
     *
     */
    private $avatarField;

    /**
     * @return mixed
     */
    public function getAvatarField()
    {
        return $this->avatarField;
    }

    /**
     * @param mixed $avatarField
     */
    public function setAvatarField($avatarField): void
    {
        $this->avatarField = $avatarField;
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatarName;

    /**
     * @return mixed
     */
    public function getAvatarName()
    {
        return $this->avatarName;
    }

    /**
     * @param mixed $avatarName
     */
    public function setAvatarName($avatarName): void
    {
        $this->avatarName = $avatarName;
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
        if (empty($this->roles)) {
            $this->roles = ['ROLE_USER'];
        }

        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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


//    /**
//     * @var string le token qui servira lors de l'oubli de mot de passe
//     * @ORM\Column(type="string", length=255, nullable=true)
//     */
//    protected $resetToken;
//
//    /**
//     * @return string
//     */
//    public function getResetToken(): string
//    {
//        return $this->resetToken;
//    }
//
//    /**
//     * @param string $resetToken
//     */
//    public function setResetToken(?string $resetToken): void
//    {
//        $this->resetToken = $resetToken;
//    }
//


}
