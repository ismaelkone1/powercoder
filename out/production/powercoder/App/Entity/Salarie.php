<?php

namespace App\Entity;

use App\Repository\SalarieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalarieRepository::class)]
class Salarie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Besoin>
     */
    #[ORM\ManyToMany(targetEntity: Besoin::class, inversedBy: 'salaries')]
    private Collection $affectations;

    /**
     * @var Collection<int, SalarieCompetence>
     */
    #[ORM\OneToMany(targetEntity: SalarieCompetence::class, mappedBy: 'salarie')]
    private Collection $salarieCompetences;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
        $this->salarieCompetences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Besoin>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Besoin $affectation): static
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
        }

        return $this;
    }

    public function removeAffectation(Besoin $affectation): static
    {
        $this->affectations->removeElement($affectation);

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
            $salarieCompetence->setSalarie($this);
        }

        return $this;
    }

    public function removeSalarieCompetence(SalarieCompetence $salarieCompetence): static
    {
        if ($this->salarieCompetences->removeElement($salarieCompetence)) {
            // set the owning side to null (unless already changed)
            if ($salarieCompetence->getSalarie() === $this) {
                $salarieCompetence->setSalarie(null);
            }
        }

        return $this;
    }
}
