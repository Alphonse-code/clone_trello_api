<?php

namespace App\Entity;

use App\Entity\Project;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"read:tableau_with_card"})
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min=5)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=80, unique=true)
     * @Assert\Length(min=5, max=100)
     *  @Assert\NotBlank(message="L'email est obligatoire")
     * @Assert\Email(message="email format invalide")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="user")
     */
    private $projects;

    
    /**
     * @ORM\Column(type="json")
     *
     */
    private $roles = ['ROLE_USER'];

    /**
     * User constructor.
     * @param $username
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=6)
     * @Assert\NotBlank(message="Le Mot de Passe est obligatoire")
     *
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $forgotPasswordToke;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"read:tableau_with_card"})
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=Carte::class, inversedBy="users")
     */
    private $cartes;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    /**
     * @return array|string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    /**
     *
     * @param array $roles
     * @return void
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }
    public function eraseCredentials()
    {
    }

    public function getForgotPasswordToke(): ?string
    {
        return $this->forgotPasswordToke;
    }

    public function setForgotPasswordToke(?string $forgotPasswordToke): self
    {
        $this->forgotPasswordToke = $forgotPasswordToke;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getCartes(): ?Carte
    {
        return $this->cartes;
    }

    public function setCartes(?Carte $cartes): self
    {
        $this->cartes = $cartes;

        return $this;
    }
}
