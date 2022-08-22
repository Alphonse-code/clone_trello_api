<?php

namespace App\Entity;

use App\Entity\Carte;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LabelsRepository;

/**
 * @ORM\Entity(repositoryClass=LabelsRepository::class)
 */
class Labels
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity=Carte::class, mappedBy="labels")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=true) 
     */
    private $carte;

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
}
