<?php

namespace App\Entity;

use App\Repository\SortiesRepository;



use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Array_;


/**
 * @ORM\Entity(repositoryClass=SortiesRepository::class)
 */
class Sorties
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;


    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private ?DateTime $dateHeureDebut;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private ?DateTime $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private int $duree;

    /**
     * @ORM\Column(type="integer")
     * @var int|null
     */
    private ?int $nbIncriptionsMax;

    /**
     * @ORM\Column(type="text", nullable=true, length=500)
     * @var string|null
     */
    private ?string $infosSortie;

    /**
     * @ORM\ManyToMany(targetEntity=User::class,
     *     mappedBy="SortiesInscrites", cascade={"persist"})
     *
     *
     * ensemble des id des participants inscrits
     */
    private Collection $listeDesInscrits;

    /**
     * @ORM\ManyToOne(targetEntity=User::class,
     *     inversedBy="listeSortiesOrganisees")
     *@ORM\JoinColumn()
     *
     */
    private User $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class,
     *      inversedBy="sortiesRattacheesCampus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $siteOrganisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Lieux::class, inversedBy="sortiesRattacheesLieu")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Etats::class, inversedBy="sortiesRattacheesEtat")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etatSortie;

    public function __construct()
    {
        $this->listeDesInscrits = new ArrayCollection();
    }



    /**
     * @return int
     */
    public function getIdSortie(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return DateTime
     */
    public function getDateHeureDebut(): ?DateTime
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param DateTime $dateHeureDebut
     */
    public function setDateHeureDebut(?DateTime $dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    /**
     * @return DateTime
     */
    public function getDateLimiteInscription(): ?DateTime
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param DateTime $dateLimiteInscription
     */
    public function setDateLimiteInscription(?DateTime $dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    /**
     * @return int|null
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param int|null $duree
     *
     */
    public function setDuree(?int $duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @return int|null
     */
    public function getNbIncriptionsMax(): ?int
    {
        return $this->nbIncriptionsMax;
    }

    /**
     * @param int|null $nbIncriptionsMax
     */
    public function setNbIncriptionsMax(?int $nbIncriptionsMax): void
    {
        $this->nbIncriptionsMax = $nbIncriptionsMax;
    }

    /**
     * @return string|null
     */
    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    /**
     * @param string|null $infosSortie
     */
    public function setInfosSortie(?string $infosSortie): void
    {
        $this->infosSortie = $infosSortie;
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
     * @return Collection|User[]
     */
    public function getListeDesInscrits(): Collection
    {
        return $this->listeDesInscrits;
    }

    public function addListeDesInscrit(User $listeDesInscrit): self
    {
        if (!$this->listeDesInscrits->contains($listeDesInscrit)) {
            $this->listeDesInscrits[] = $listeDesInscrit;
            $listeDesInscrit->addSortiesInscrite($this);
        }

        return $this;
    }

    public function removeListeDesInscrit(User $listeDesInscrit): self
    {
        if ($this->listeDesInscrits->removeElement($listeDesInscrit)) {
            $listeDesInscrit->removeSortiesInscrite($this);
        }

        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getSiteOrganisateur(): ?Campus
    {
        return $this->siteOrganisateur;
    }

    public function setSiteOrganisateur(?Campus $siteOrganisateur): self
    {
        $this->siteOrganisateur = $siteOrganisateur;

        return $this;
    }

    public function getLieu(): ?Lieux
    {
        return $this->lieu;
    }

    public function setLieu(?Lieux $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEtatSortie(): ?Etats
    {
        return $this->etatSortie;
    }

    public function setEtatSortie(?Etats $etatSortie): self
    {
        $this->etatSortie = $etatSortie;

        return $this;
    }




}


