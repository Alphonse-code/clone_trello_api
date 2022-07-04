<?php

namespace App\Entity;

use App\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $comment_content;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $comment_photo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $comment_idparent;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="projects")
     */
    private $tasks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentContent(): ?string
    {
        return $this->comment_content;
    }

    public function setCommentContent(string $comment_content): self
    {
        $this->comment_content = $comment_content;

        return $this;
    }

    public function getCommentPhoto()
    {
        return $this->comment_photo;
    }

    public function setCommentPhoto($comment_photo): self
    {
        $this->comment_photo = $comment_photo;

        return $this;
    }

    public function getCommentIdparent(): ?int
    {
        return $this->comment_idparent;
    }

    public function setCommentIdparent(?int $comment_idparent): self
    {
        $this->comment_idparent = $comment_idparent;

        return $this;
    }

      /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}
