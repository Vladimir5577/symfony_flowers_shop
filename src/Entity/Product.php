<?php

namespace App\Entity;

use App\Enum\ProductBadge;
use App\Enum\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 200)]
    private ?string $fullName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(type: 'string', enumType: ProductType::class)]
    private ?ProductType $type = null;

    #[ORM\Column(type: 'string', nullable: true, enumType: ProductBadge::class)]
    private ?ProductBadge $badge = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $mood = null;

    #[ORM\Column(options: ['default' => true])]
    private bool $inStock = true;

    #[ORM\Column(options: ['default' => false])]
    private bool $isGift = false;

    #[ORM\Column(options: ['default' => false])]
    private bool $canBeCombined = false;

    #[ORM\Column(options: ['default' => 0])]
    private int $sortOrder = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /** @var Collection<int, ProductImage> */
    #[ORM\OneToMany(targetEntity: ProductImage::class, mappedBy: 'product', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['sortOrder' => 'ASC'])]
    private Collection $images;

    /** @var Collection<int, Occasion> */
    #[ORM\ManyToMany(targetEntity: Occasion::class, inversedBy: 'products')]
    #[ORM\JoinTable(name: 'product_occasion')]
    private Collection $occasions;

    /** @var Collection<int, BudgetTier> */
    #[ORM\ManyToMany(targetEntity: BudgetTier::class, inversedBy: 'products')]
    #[ORM\JoinTable(name: 'product_budget_tier')]
    private Collection $budgetTiers;

    /** @var Collection<int, HomeProduct> */
    #[ORM\OneToMany(targetEntity: HomeProduct::class, mappedBy: 'product', cascade: ['remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['sortOrder' => 'ASC'])]
    private Collection $homeProducts;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->occasions = new ArrayCollection();
        $this->budgetTiers = new ArrayCollection();
        $this->homeProducts = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        if (!$this->slug && $this->name) {
            $slugger = new AsciiSlugger('ru');
            $this->slug = strtolower($slugger->slug($this->name)->toString());
        }
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getType(): ?ProductType
    {
        return $this->type;
    }

    public function setType(ProductType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getBadge(): ?ProductBadge
    {
        return $this->badge;
    }

    public function setBadge(?ProductBadge $badge): static
    {
        $this->badge = $badge;
        return $this;
    }

    public function getMood(): ?string
    {
        return $this->mood;
    }

    public function setMood(?string $mood): static
    {
        $this->mood = $mood;
        return $this;
    }

    public function isInStock(): bool
    {
        return $this->inStock;
    }

    public function setInStock(bool $inStock): static
    {
        $this->inStock = $inStock;
        return $this;
    }

    public function isGift(): bool
    {
        return $this->isGift;
    }

    public function setIsGift(bool $isGift): static
    {
        $this->isGift = $isGift;
        return $this;
    }

    public function isCanBeCombined(): bool
    {
        return $this->canBeCombined;
    }

    public function setCanBeCombined(bool $canBeCombined): static
    {
        $this->canBeCombined = $canBeCombined;
        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /** @return Collection<int, ProductImage> */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ProductImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduct($this);
        }
        return $this;
    }

    public function removeImage(ProductImage $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }
        return $this;
    }

    public function getMainPhoto(): ?string
    {
        $first = $this->images->first();
        return $first ? $first->getImageName() : null;
    }

    /** @return Collection<int, Occasion> */
    public function getOccasions(): Collection
    {
        return $this->occasions;
    }

    public function addOccasion(Occasion $occasion): static
    {
        if (!$this->occasions->contains($occasion)) {
            $this->occasions->add($occasion);
        }
        return $this;
    }

    public function removeOccasion(Occasion $occasion): static
    {
        $this->occasions->removeElement($occasion);
        return $this;
    }

    /** @return Collection<int, BudgetTier> */
    public function getBudgetTiers(): Collection
    {
        return $this->budgetTiers;
    }

    public function addBudgetTier(BudgetTier $budgetTier): static
    {
        if (!$this->budgetTiers->contains($budgetTier)) {
            $this->budgetTiers->add($budgetTier);
        }
        return $this;
    }

    public function removeBudgetTier(BudgetTier $budgetTier): static
    {
        $this->budgetTiers->removeElement($budgetTier);
        return $this;
    }

    /** @return Collection<int, HomeProduct> */
    public function getHomeProducts(): Collection
    {
        return $this->homeProducts;
    }

    public function addHomeProduct(HomeProduct $homeProduct): static
    {
        if (!$this->homeProducts->contains($homeProduct)) {
            $this->homeProducts->add($homeProduct);
            $homeProduct->setProduct($this);
        }

        return $this;
    }

    public function removeHomeProduct(HomeProduct $homeProduct): static
    {
        if ($this->homeProducts->removeElement($homeProduct)) {
            if ($homeProduct->getProduct() === $this) {
                $homeProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
