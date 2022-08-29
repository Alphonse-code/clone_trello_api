<?php

namespace App\Entity;

use App\Entity\Carte;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LabelsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LabelsRepository::class)
 */
class Labels
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:tableau_with_card"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"read:tableau_with_card"})
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"read:tableau_with_card"})
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=Carte::class, inversedBy="labels")
     *  @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     * 
     */
    private $cartes;

    public function __construct()
    {
        $this->cartes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, Carte>
     */
    public function getCartes(): Collection
    {
        return $this->cartes;
    }

    public function setCartes(?Carte $carte)
    {
        $this->cartes = $carte;
        return $this;
    }
    public function addCarte(Carte $carte): self
    {
        if (!$this->cartes->contains($carte)) {
            $this->cartes[] = $carte;
            $carte->setLabels($this);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): self
    {
        if ($this->cartes->removeElement($carte)) {
            // set the owning side to null (unless already changed)
            if ($carte->getLabels() === $this) {
                $carte->setLabels(null);
            }
        }

        return $this;
    }
}
