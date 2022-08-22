<?php

namespace App\Entity;

use App\Entity\Carte;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TasksRepository;

/**
 * @ORM\Entity(repositoryClass=TasksRepository::class)
 */
class Tasks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $copleted; 

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=Carte::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     *
     */
    private $carte;

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
}
