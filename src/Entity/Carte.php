<?php

namespace App\Entity;

use App\Entity\Tasks;
use App\Entity\Users;
use App\Entity\Labels;
use App\Entity\Tableau;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CarteRepository::class)
 */
class Carte
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
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"read:tableau_with_card"})
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read:tableau_with_card"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Tableau::class, inversedBy="carte")
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $tableau;

    /**
     * @ORM\OneToMany(targetEntity=Labels::class, mappedBy="cartes")
     * @Groups({"read:tableau_with_card"})
     */
    private $labels;

    /**
     * @ORM\OneToMany(targetEntity=Tasks::class, mappedBy="cartes")
     * @Groups({"read:tableau_with_card"})
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity=users::class, inversedBy="cartes")
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     * @Groups({"read:tableau_with_card"})
     */
    private $users;


    public function __construct()
    {
        $this->tableaus = new ArrayCollection();
        $this->labels = new ArrayCollection();
       // $this->users = new ArrayCollection();
        
    }
   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

   

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Tableau>
     */
    public function getTableaus(): Collection
    {
        return $this->tableaus;
    }

    public function addTableau(Tableau $tableau): self
    {
        if (!$this->tableaus->contains($tableau)) {
            $this->tableaus[] = $tableau;
            $tableau->setCarte($this);
        }
        return $this;
    }

    public function removeTableau(Tableau $tableau): self
    {
        if ($this->tableaus->removeElement($tableau)) {
            // set the owning side to null (unless already changed)
            if ($tableau->getCarte() === $this) {
                $tableau->setCarte(null);
            }
        }
        return $this;
    }

    public function getTableau(): ?Tableau
    {
        return $this->tableau;
    }

    public function setTableau(?Tableau $tableau): self
    {
        $this->tableau = $tableau;

        return $this;
    }

    /**
     * @return Collection<int, Tasks>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setCartes($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getCartes() === $this) {
                $task->setCartes(null);
            }
        }

        return $this;
    }
     /**
     * @return Collection<int, Labels>
     */
    public function getLabels(): ?Collection
    {
        return $this->labels;
    }

    public function setLabels(?Labels $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    public function getUsers(): ?users
    {
        return $this->users;
    }

    public function setUsers(?users $users): self
    {
        $this->users = $users;
        return $this;
    }
}
