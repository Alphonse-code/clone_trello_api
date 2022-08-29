<?php

namespace App\Entity;

use App\Entity\Carte;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TableauRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=TableauRepository::class)
 */
class Tableau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:tableau_with_card"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:tableau_with_card"})
     * 
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Carte::class, mappedBy="tableau")
     * @Groups({"read:tableau_with_card"})
     */
    private $carte;

    public function __construct()
    {
        $this->carte = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
 
    public function getCarte(): ?Collection
    {
        return $this->carte;
    }

    public function setCarte(?Carte $carte): self
    {
        $this->carte = $carte;

        return $this;
    }

    public function addCarte(Carte $carte): self
    {
        if (!$this->carte->contains($carte)) {
            $this->carte[] = $carte;
            $carte->setTableau($this);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): self
    {
        if ($this->carte->removeElement($carte)) {
            // set the owning side to null (unless already changed)
            if ($carte->getTableau() === $this) {
                $carte->setTableau(null);
            }
        }

        return $this;
    }
}
