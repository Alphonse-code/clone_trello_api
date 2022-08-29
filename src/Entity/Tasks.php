<?php

namespace App\Entity;

use App\Entity\Carte;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TasksRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TasksRepository::class)
 */
class Tasks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:tableau_with_card"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:tableau_with_card"})
     */
    private $copleted; 

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:tableau_with_card"})
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=Carte::class, inversedBy="tasks")
     *  @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $cartes;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCopleted(): ?bool
    {
        return $this->copleted;
    }

    public function setCopleted(bool $copleted): self
    {
        $this->copleted = $copleted;

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
