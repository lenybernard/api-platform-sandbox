<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $firstname = null;

    #[ORM\Column(length: 55)]
    private ?string $lastname = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Recepe::class)]
    private Collection $recepes;

    public function __construct()
    {
        $this->recepes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Recepe>
     */
    public function getRecepes(): Collection
    {
        return $this->recepes;
    }

    public function addRecepe(Recepe $recepe): self
    {
        if (!$this->recepes->contains($recepe)) {
            $this->recepes->add($recepe);
            $recepe->setAuthor($this);
        }

        return $this;
    }

    public function removeRecepe(Recepe $recepe): self
    {
        if ($this->recepes->removeElement($recepe)) {
            // set the owning side to null (unless already changed)
            if ($recepe->getAuthor() === $this) {
                $recepe->setAuthor(null);
            }
        }

        return $this;
    }
}
