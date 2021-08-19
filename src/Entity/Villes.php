<?php

namespace App\Entity;

use App\Repository\VillesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VillesRepository::class)
 */
class Villes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity=Lieux::class,
     *      mappedBy="ville"
     *     )
     */
    private Collection $lieuxRattaches;

    public function __construct()
    {
        $this->lieuxRattaches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection|Lieux[]
     */
    public function getLieuxRattaches(): Collection
    {
        return $this->lieuxRattaches;
    }

    public function addLieuxRattach(Lieux $lieuxRattach): self
    {
        if (!$this->lieuxRattaches->contains($lieuxRattach)) {
            $this->lieuxRattaches[] = $lieuxRattach;
            $lieuxRattach->setVille($this);
        }

        return $this;
    }

    public function removeLieuxRattach(Lieux $lieuxRattach): self
    {
        if ($this->lieuxRattaches->removeElement($lieuxRattach)) {
            // set the owning side to null (unless already changed)
            if ($lieuxRattach->getVille() === $this) {
                $lieuxRattach->setVille(null);
            }
        }

        return $this;
    }
}
