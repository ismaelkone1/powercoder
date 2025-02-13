<?php

namespace App\Entity;

use App\Repository\SalarieCompetenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalarieCompetenceRepository::class)]
class SalarieCompetence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $interet = null;

    #[ORM\ManyToOne(inversedBy: 'salarieCompetences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salarie $salarie = null;

    #[ORM\ManyToOne(inversedBy: 'salarieCompetences')]
    private ?Competence $competence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInteret(): ?int
    {
        return $this->interet;
    }

    public function setInteret(int $interet): static
    {
        $this->interet = $interet;

        return $this;
    }

    public function getSalarie(): ?Salarie
    {
        return $this->salarie;
    }

    public function setSalarie(?Salarie $salarie): static
    {
        $this->salarie = $salarie;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): static
    {
        $this->competence = $competence;

        return $this;
    }
}
