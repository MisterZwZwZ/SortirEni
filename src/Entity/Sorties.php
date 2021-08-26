<?php

namespace App\Entity;

use App\Repository\EtatsRepository;
use App\Repository\SortiesRepository;



use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Length(min="8",
     *                max="255",
     *                minMessage="Le nom de la sortie doit faire au minimum 8 caractères",
     *                maxMessage="Le nom de la sortie doit faire au maximum 255 caractères")
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private ?DateTimeInterface $dateHeureDebut;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private ?DateTimeInterface $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     * @var int|null
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private ?int $duree;

    /**
     * @ORM\Column(type="integer")
     * @var int|null
     * @Assert\NotBlank(message="Ce champ est obligatoire")
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
     * Ensemble des id des participants inscrits
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
     * @ORM\JoinColumn(nullable=true)
     */
    private $siteOrganisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Lieux::class, inversedBy="sortiesRattacheesLieu")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Etats::class, inversedBy="sortiesRattacheesEtat")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Etats $etatSortie;

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
     * @return DateTimeInterface
     */
    public function getDateHeureDebut(): ?DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param DateTimeInterface $dateHeureDebut
     */
    public function setDateHeureDebut(?DateTimeInterface $dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDateLimiteInscription(): ?DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param DateTimeInterface $dateLimiteInscription
     */
    public function setDateLimiteInscription(?DateTimeInterface $dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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

    public function setOrganisateur(?User $organisateur): void
    {
        $this->organisateur = $organisateur;

//        return $this;
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


