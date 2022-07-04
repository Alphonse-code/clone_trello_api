<?php

namespace App\Entity;

use App\Entity\Task;
use App\Entity\Users;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\ProjectRepository;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title_project;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_project;

    /**
     * @ORM\Column(type="text")
     */
    private $description_project;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status_project;

    /**
     * @ORM\Column(type="date")
     */
    private $bigindate_project;

    /**
     * @ORM\Column(type="date")
     */
    private $enddate_project;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="projects")
     */
    private $tasks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleProject(): ?string
    {
        return $this->title_project;
    }

    public function setTitleProject(string $title_project): self
    {
        $this->title_project = $title_project;

        return $this;
    }

    public function getTypeProject(): ?string
    {
        return $this->type_project;
    }

    public function setTypeProject(string $type_project): self
    {
        $this->type_project = $type_project;

        return $this;
    }

    public function getDescriptionProject(): ?string
    {
        return $this->description_project;
    }

    public function setDescriptionProject(string $description_project): self
    {
        $this->description_project = $description_project;

        return $this;
    }

    public function getStatusProject(): ?string
    {
        return $this->status_project;
    }

    public function setStatusProject(string $status_project): self
    {
        $this->status_project = $status_project;

        return $this;
    }

    public function getBigindateProject(): ?\DateTimeInterface
    {
        return $this->bigindate_project;
    }

    public function setBigindateProject(
        \DateTimeInterface $bigindate_project
    ): self {
        $this->bigindate_project = $bigindate_project;

        return $this;
    }

    public function getEnddateProject(): ?\DateTimeInterface
    {
        return $this->enddate_project;
    }

    public function setEnddateProject(\DateTimeInterface $enddate_project): self
    {
        $this->enddate_project = $enddate_project;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTasks(): ?Task
    {
        return $this->tasks;
    }

    public function setTasks(?Task $tasks): self
    {
        $this->tasks = $tasks;
        return $this;
    }
}