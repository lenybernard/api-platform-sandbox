<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Repository\RecepeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecepeRepository::class)]
#[ApiResource(
    graphQlOperations: [
        new Query(),
        new QueryCollection(),
        new Mutation(
            normalizationContext: ['groups' => ['recepe:read']],
            denormalizationContext: ['groups' => ['recepe:write']],
            name: 'create',
        ),
        new Mutation(
            normalizationContext: ['groups' => ['recepe:read']],
            denormalizationContext: ['groups' => ['recepe:write']],
            name: 'update',
        )
    ]
)]
class Recepe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recepe:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recepe:read', 'recepe:write'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recepe:read', 'recepe:write'])]
    private ?float $duration = null;

    #[ORM\ManyToOne(inversedBy: 'recepes')]
    #[Groups(['recepe:read', 'recepe:write'])]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'recepe', targetEntity: Ingredient::class, cascade: ["persist"], orphanRemoval: true)]
    #[Groups(['recepe:read', 'recepe:write'])]
    private Collection $ingredients;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'likedRecepes')]
    #[Groups(['recepe:read', 'recepe:write'])]
    private Collection $likers;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->likers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(?float $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->setRecepe($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecepe() === $this) {
                $ingredient->setRecepe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikers(): Collection
    {
        return $this->likers;
    }

    public function addLiker(User $liker): self
    {
        if (!$this->likers->contains($liker)) {
            $this->likers->add($liker);
        }

        return $this;
    }

    public function removeLiker(User $liker): self
    {
        $this->likers->removeElement($liker);

        return $this;
    }
}
