<?php

namespace App\Entity;

use App\Repository\EtatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatsRepository::class)
 */
class Etats
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
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Sorties::class, mappedBy="etatSortie")
     */
    private $sortiesRattacheesEtat;

    public function __construct()
    {
        $this->sortiesRattacheesEtat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getSortiesRattacheesEtat(): Collection
    {
        return $this->sortiesRattacheesEtat;
    }

    public function addSortiesRattacheeEtat(Sorties $sortiesRattacheeEtat): self
    {
        if (!$this->sortiesRattacheesEtat->contains($sortiesRattacheeEtat)) {
            $this->sortiesRattacheesEtat[] = $sortiesRattacheeEtat;
            $sortiesRattacheeEtat->setEtatSortie($this);
        }

        return $this;
    }

    public function removeSortiesRattacheeEtat(Sorties $sortiesRattacheeEtat): self
    {
        if ($this->sortiesRattacheesEtat->removeElement($sortiesRattacheeEtat)) {
            // set the owning side to null (unless already changed)
            if ($sortiesRattacheeEtat->getEtatSortie() === $this) {
                $sortiesRattacheeEtat->setEtatSortie(null);
            }
        }

        return $this;
    }
}
