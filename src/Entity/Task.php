<?php

namespace App\Entity;

use App\Entity\Users;
use App\Entity\Comment;
use App\Entity\Project;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
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
    private $task_name;

    /**
     * @ORM\Column(type="text")
     */
    private $task_description;

    /**
     * @ORM\Column(type="date")
     */
    private $task_datebigin;

    /**
     * @ORM\Column(type="date")
     */
    private $task_enddate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $task_status;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $user;

     /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $projects;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskName(): ?string
    {
        return $this->task_name;
    }

    public function setTaskName(string $task_name): self
    {
        $this->task_name = $task_name;

        return $this;
    }

    public function getTaskDescription(): ?string
    {
        return $this->task_description;
    }

    public function setTaskDescription(string $task_description): self
    {
        $this->task_description = $task_description;

        return $this;
    }

    public function getTaskDatebigin(): ?\DateTimeInterface
    {
        return $this->task_datebigin;
    }

    public function setTaskDatebigin(\DateTimeInterface $task_datebigin): self
    {
        $this->task_datebigin = $task_datebigin;

        return $this;
    }

    public function getTaskEnddate(): ?\DateTimeInterface
    {
        return $this->task_enddate;
    }

    public function setTaskEnddate(\DateTimeInterface $task_enddate): self
    {
        $this->task_enddate = $task_enddate;

        return $this;
    }

    public function getTaskStatus(): ?string
    {
        return $this->task_status;
    }

    public function setTaskStatus(string $task_status): self
    {
        $this->task_status = $task_status;

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

    public function getProjects(): ?Project
    {
        return $this->projects;
    }

    public function setProjects(?Project $projects): self
    {
        $this->projects = $projects;
        return $this;
    }
    
    public function getComment(): ?Comment
    {
        return $this->comment;
    }
    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;
        return $this;
    }
}
