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

    #[ORM\ManyToMany(targetEntity: Recepe::class, mappedBy: 'likers')]
    private Collection $likedRecepes;

    public function __construct()
    {
        $this->recepes = new ArrayCollection();
        $this->likedRecepes = new ArrayCollection();
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

    /**
     * @return Collection<int, Recepe>
     */
    public function getLikedRecepes(): Collection
    {
        return $this->likedRecepes;
    }

    public function addLikedRecepe(Recepe $likedRecepe): self
    {
        if (!$this->likedRecepes->contains($likedRecepe)) {
            $this->likedRecepes->add($likedRecepe);
            $likedRecepe->addLiker($this);
        }

        return $this;
    }

    public function removeLikedRecepe(Recepe $likedRecepe): self
    {
        if ($this->likedRecepes->removeElement($likedRecepe)) {
            $likedRecepe->removeLiker($this);
        }

        return $this;
    }
}
