<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec cet email", groups={"register"})
 * @UniqueEntity(fields={"pseudo"}, message="Il existe déjà un compte avec ce pseudo", groups={"register"})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     * @Assert\NotBlank(message="L'email est requis !", groups={"register"})
     * @Assert\Email(message="L'email est invalide !", groups={"register"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string|null The hashed password
     *
     * @Assert\NotBlank(message="Le mot de passe est requis !", groups={"register"})
     * @Assert\Length(min=8, max=50, minMessage="Le mot de passe doit contenir au minimum {{ limit }} caractères",
     *     maxMessage="Le mot de passe doit contenir au maximum {{ limit }} caractères", groups={"register"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=30)
     *
     * @Assert\NotBlank(message="Le nom est requis !", groups={"register"})
     * @Assert\Length(min=2, max=50, minMessage="Le nom doit contenir au minimum {{ limit }} caractères",
     *     maxMessage="Le nom doit contenir au maximum {{ limit }} caractères", groups={"register"})
     *
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     *
     * @Assert\NotBlank(message="Le prenom est requis !", groups={"register"})
     * @Assert\Length(min=2, max=50, minMessage="Le prenom doit contenir au minimum {{ limit }} caractères",
     *      maxMessage="Le prenom doit contenir au maximum {{ limit }} caractères", groups={"register"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Le numéro de téléphone est requis !", groups={"register"})
     * @Assert\Regex(
     *     pattern="#^(0|\+33|0033)[1-9]([-. ]?[0-9]{2}){4}$#",
     *     message="Format de numéro de téléphone invalide",
     *     groups={"register"}
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Le pseudo est requis !", groups={"register"})
     * @Assert\Length(min=4, max=30, minMessage="Le pseudo doit contenir au minimum {{ limit }} caractères",
     *      maxMessage="Le pseudo doit contenir au maximum {{ limit }} caractères", groups={"register"})
     */
    private $pseudo;


    /**
     * @ORM\ManyToMany(targetEntity=Sorties::class,
     *      inversedBy="listeDesInscrits",cascade={"persist"})
     * @ORM\JoinTable(name="sorties_user")
     *
     */
    private Collection $SortiesInscrites;

    /**
     * @ORM\OneToMany(targetEntity=Sorties::class,
     *      mappedBy="organisateur", orphanRemoval=true)
     *
     *
     */
    private Collection $listeSortiesOrganisees;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="usersRattaches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campusUser;

    public function __construct()
    {
        $this->SortiesInscrites = new ArrayCollection();
        $this->listeSortiesOrganisees = new ArrayCollection();
    }




    //TODO ajouter relation Campus

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }


    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getSortiesInscrites(): Collection
    {
        return $this->SortiesInscrites;
    }

    public function addSortiesInscrite(Sorties $sortiesInscrite): self
    {
        if (!$this->SortiesInscrites->contains($sortiesInscrite)) {
            $this->SortiesInscrites[] = $sortiesInscrite;
        }

        return $this;
    }

    public function removeSortiesInscrite(Sorties $sortiesInscrite): self
    {
        $this->SortiesInscrites->removeElement($sortiesInscrite);

        return $this;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getListeSortiesOrganisees(): Collection
    {
        return $this->listeSortiesOrganisees;
    }

    public function addListeSortiesOrganisee(Sorties $listeSortiesOrganisee): self
    {
        if (!$this->listeSortiesOrganisees->contains($listeSortiesOrganisee)) {
            $this->listeSortiesOrganisees[] = $listeSortiesOrganisee;
            $listeSortiesOrganisee->setOrganisateur($this);
        }

        return $this;
    }

    public function removeListeSortiesOrganisee(Sorties $listeSortiesOrganisee): self
    {
        if ($this->listeSortiesOrganisees->removeElement($listeSortiesOrganisee)) {
            // set the owning side to null (unless already changed)
            if ($listeSortiesOrganisee->getOrganisateur() === $this) {
                $listeSortiesOrganisee->setOrganisateur(null);
            }
        }

        return $this;
    }

    public function getCampusUser(): ?Campus
    {
        return $this->campusUser;
    }

    public function setCampusUser(?Campus $campusUser): self
    {
        $this->campusUser = $campusUser;

        return $this;
    }



}
