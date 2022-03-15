<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 40, name: "gls_identifier")]
    #[Assert\Length(
        min: 40,
        max: 40,
    )]
    private string $identifier;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 16,
    )]
    #[ORM\Column(type: 'string', length: 50, name: "gls_firstname")]
    private string $firstname;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 16,
    )]
    #[ORM\Column(type: 'string', length: 50, name: "gls_lastname")]
    private string $lastname;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 255,
    )]
    #[ORM\Column(type: 'string', length: 255, name: "gls_email")]
    private string $email;

    #[ORM\Column(type: 'integer', nullable: true, name: "gls_mirian")]
    private int $mirian;

    #[ORM\Column(type: 'datetime', name: "gls_creation")]
    private \DateTime $creation;

    #[ORM\Column(type: 'datetime', name: "gls_modification")]
    private \DateTime $modification;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Character::class)]
    private $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getMirian(): ?int
    {
        return $this->mirian;
    }

    public function setMirian(?int $mirian): self
    {
        $this->mirian = $mirian;

        return $this;
    }

    public function getCreation(): ?\DateTime
    {
        return $this->creation;
    }

    public function setCreation(\DateTime $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    public function getModification(): ?\DateTime
    {
        return $this->modification;
    }

    public function setModification(\DateTime $modification): self
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->setPlayer($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getPlayer() === $this) {
                $character->setPlayer(null);
            }
        }

        return $this;
    }
}
