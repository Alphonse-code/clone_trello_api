<?php

namespace App\Entity;


use App\Entity\Project;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 * @Uploadable
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
     * @Groups({"read:tableau_with_card"})
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
        $this->cartes = new ArrayCollection();
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
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:tableau_with_card"})
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="images_directory", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity=Carte::class, mappedBy="users")
     * 
     */
    private $cartes;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
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
    public function setRoles( $roles)
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

    /**
     * @return Collection<int, Carte>
     */
    public function getCartes(): Collection
    {
        return $this->cartes;
    }

    public function addCarte(Carte $carte): self
    {
        if (!$this->cartes->contains($carte)) {
            $this->cartes[] = $carte;
            $carte->setUsers($this);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): self
    {
        if ($this->cartes->removeElement($carte)) {
            // set the owning side to null (unless already changed)
            if ($carte->getUsers() === $this) {
                $carte->setUsers(null);
            }
        }
        return $this;
    }
}
