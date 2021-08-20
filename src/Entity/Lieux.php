<?php

namespace App\Entity;

use App\Repository\LieuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LieuxRepository::class)
 */
class Lieux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $rue;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity=Sorties::class, mappedBy="lieu")
     */
    private $sortiesRattacheesLieu;

    /**
     * @ORM\ManyToOne(targetEntity=Villes::class, inversedBy="lieuxRattaches")
     * @ORM\JoinColumn(nullable=false)
     */
    private Villes $ville;

    public function __construct()
    {
        $this->sortiesRattacheesLieu = new ArrayCollection();
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getSortiesRattacheesLieu(): Collection
    {
        return $this->sortiesRattacheesLieu;
    }

    public function addSortiesRattacheeLieu(Sorties $sortiesRattacheeLieu): self
    {
        if (!$this->sortiesRattacheesLieu->contains($sortiesRattacheeLieu)) {
            $this->sortiesRattacheesLieu[] = $sortiesRattacheeLieu;
            $sortiesRattacheeLieu->setLieu($this);
        }


        return $this;
    }

    public function removeSortiesRattacheeLieu(Sorties $sortiesRattacheeLieu): self
    {
        if ($this->sortiesRattacheesLieu->removeElement($sortiesRattacheeLieu)) {
            // set the owning side to null (unless already changed)
            if ($sortiesRattacheeLieu->getLieu() === $this) {
                $sortiesRattacheeLieu->setLieu(null);
            }
        }

        return $this;
    }

    public function getVille(): ?Villes
    {
        return $this->ville;
    }

    public function setVille(?Villes $ville): self
    {
        $this->ville = $ville;

        return $this;
    }
}
