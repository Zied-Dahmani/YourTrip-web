<?php

namespace App\Entity;

use App\Repository\CentreCampingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=CentreCampingRepository::class)
 */
class CentreCamping
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("api:centre")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\NotBlank(message="Tapez le nom!")
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+",
     *     message="Le nom doit être alphabétique"
     * )
     * @Groups("api:centre")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\NotBlank(message="Tapez la description!")
     * @Groups("api:centre")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\NotBlank(message="Tapez l' adresse!")
     * @Groups("api:centre")
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity=Tente::class, mappedBy="centreCamping")
     */
    private $tentes;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\NotBlank(message="Tapez l'email!")
     * * @Assert\Email(message="Tapez un email valide!")
     * @Groups("api:centre")
     */
    private $email;



    public function __construct()
    {
        $this->tentes = new ArrayCollection();
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Tente>
     */
    public function getTentes(): Collection
    {
        return $this->tentes;
    }

    public function addTente(Tente $tente): self
    {
        if (!$this->tentes->contains($tente)) {
            $this->tentes[] = $tente;
            $tente->setCentreCamping($this);
        }

        return $this;
    }

    public function removeTente(Tente $tente): self
    {
        if ($this->tentes->removeElement($tente)) {
            // set the owning side to null (unless already changed)
            if ($tente->getCentreCamping() === $this) {
                $tente->setCentreCamping(null);
            }
        }

        return $this;
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
}
