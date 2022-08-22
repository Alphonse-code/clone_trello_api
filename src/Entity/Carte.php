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
     * @ORM\ManyToOne(targetEntity=Labels::class, inversedBy="carte")
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     *@Groups({"read:tableau_with_card"})
     */
    private $labels;
   
    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="carte")
     * @ORM\JoinColumn(nullable=true)
     *
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Tasks::class, mappedBy="carte")
     * 
     */
    private $tasks;

    
    

    
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

   

    public function __construct()
    {
        $this->tableaus = new ArrayCollection();
       // $this->tasks = new ArrayCollection();
        //$this->user = new ArrayCollection();
    }

    public function getUser(): Collection
    {
        return $this->user;
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

    public function getLabels(): ?array
    {
        return $this->labels;
    }

    public function setLabels(?array $labels): self
    {
        $this->labels = $labels;

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

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function setTasks(?array $tasks): self
    {
        $this->tasks = $tasks;

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
}
