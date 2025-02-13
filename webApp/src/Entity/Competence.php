<?php

namespace App\Entity;

use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetenceRepository::class)]
class Competence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2)]
    private ?string $type = null;


    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Besoin>
     */
    #[ORM\ManyToMany(targetEntity: Besoin::class, inversedBy: 'competences')]
    private Collection $besoins;

    /**
     * @var Collection<int, SalarieCompetence>
     */
    #[ORM\OneToMany(targetEntity: SalarieCompetence::class, mappedBy: 'competence')]
    private Collection $salarieCompetences;

    public function __construct()
    {
        $this->besoins = new ArrayCollection();
        $this->salarieCompetences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Besoin>
     */
    public function getBesoins(): Collection
    {
        return $this->besoins;
    }

    public function addBesoin(Besoin $besoin): static
    {
        if (!$this->besoins->contains($besoin)) {
            $this->besoins->add($besoin);
        }

        return $this;
    }

    public function removeBesoin(Besoin $besoin): static
    {
        $this->besoins->removeElement($besoin);

        return $this;
    }

    /**
     * @return Collection<int, SalarieCompetence>
     */
    public function getSalarieCompetences(): Collection
    {
        return $this->salarieCompetences;
    }

    public function addSalarieCompetence(SalarieCompetence $salarieCompetence): static
    {
        if (!$this->salarieCompetences->contains($salarieCompetence)) {
            $this->salarieCompetences->add($salarieCompetence);
            $salarieCompetence->setCompetence($this);
        }

        return $this;
    }

    public function removeSalarieCompetence(SalarieCompetence $salarieCompetence): static
    {
        if ($this->salarieCompetences->removeElement($salarieCompetence)) {
            // set the owning side to null (unless already changed)
            if ($salarieCompetence->getCompetence() === $this) {
                $salarieCompetence->setCompetence(null);
            }
        }

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
}
