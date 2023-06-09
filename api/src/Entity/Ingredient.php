<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Mutation;
use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ApiResource]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recepe:read', 'recepe:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recepe:read', 'recepe:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 55, nullable: true)]
    #[Groups(['recepe:read', 'recepe:write'])]
    private ?string $quantity = null;

    #[ORM\Column(length: 55, nullable: true)]
    #[Groups(['recepe:read', 'recepe:write'])]
    private ?string $unit = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['recepe:read'])]
    #[ApiProperty(writable: false)]
    private Recepe $recepe;

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

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(?string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getRecepe(): Recepe
    {
        return $this->recepe;
    }

    public function setRecepe(?Recepe $recepe): self
    {
        if ($recepe) {
            $this->recepe = $recepe;
        }

        return $this;
    }
}
