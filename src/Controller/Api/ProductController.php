<?php

namespace App\Controller\Api;

use App\Entity\HomeProduct;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\HomeProductRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ProductController extends AbstractController
{
    private const PER_PAGE = 20;

    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private HomeProductRepository $homeProductRepository,
    ) {}

    #[Route('/products', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $criteria = [];

        if ($type = $request->query->get('type')) {
            $criteria['type'] = $type;
        }

        if ($category = $request->query->get('category')) {
            $catEntity = $this->categoryRepository->findOneBy(['slug' => $category]);
            if (!$catEntity) {
                return $this->json(['items' => [], 'total' => 0, 'page' => 1, 'pages' => 0]);
            }
            $criteria['category'] = $catEntity;
        }

        if ($request->query->getBoolean('combinable')) {
            $criteria['canBeCombined'] = true;
            $criteria['inStock'] = true;
        }

        $page = max(1, $request->query->getInt('page', 1));
        $limit = $request->query->getInt('limit', 0);
        $hasBadge = $request->query->getBoolean('badge');

        // Get all matching products for filtering
        $allProducts = $this->productRepository->findBy($criteria, ['sortOrder' => 'ASC']);

        if ($hasBadge) {
            $allProducts = array_values(array_filter($allProducts, fn(Product $p) => $p->getBadge() !== null));
        }

        $total = count($allProducts);

        // If limit is set (e.g. top picks), use it directly without pagination
        if ($limit > 0) {
            $products = array_slice($allProducts, 0, $limit);
            return $this->json([
                'items' => array_map(fn(Product $p) => $this->serialize($p), $products),
                'total' => $total,
                'page' => 1,
                'pages' => 1,
            ]);
        }

        // Paginate
        $perPage = self::PER_PAGE;
        $pages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $perPage;
        $products = array_slice($allProducts, $offset, $perPage);

        return $this->json([
            'items' => array_map(fn(Product $p) => $this->serialize($p), $products),
            'total' => $total,
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    #[Route('/products/{slug}', methods: ['GET'])]
    public function show(string $slug): JsonResponse
    {
        $product = $this->productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        return $this->json($this->serialize($product));
    }

    #[Route('/home/products', methods: ['GET'])]
    public function homeProducts(Request $request): JsonResponse
    {
        $limit = max(1, $request->query->getInt('limit', 12));

        $items = $this->homeProductRepository->findBy(
            [],
            ['sortOrder' => 'ASC'],
            $limit
        );

        $products = array_map(
            fn(HomeProduct $homeProduct) => $homeProduct->getProduct(),
            $items
        );
        $products = array_values(array_filter($products));

        return $this->json([
            'items' => array_map(fn(Product $p) => $this->serialize($p), $products),
            'total' => count($products),
        ]);
    }

    private function serialize(Product $product): array
    {
        $photos = [];
        foreach ($product->getImages() as $img) {
            $name = $img->getImageName();
            $url = '/uploads/products/' . $name;
            $photos[] = [
                'mini' => $url,
                'thumb' => $url,
                'detail' => $url,
                'original' => $url,
            ];
        }

        return [
            'id' => $product->getSlug(),
            'name' => $product->getName(),
            'fullName' => $product->getFullName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'category' => $product->getCategory()?->getSlug(),
            'type' => $product->getType()?->value,
            'photos' => $photos,
            'badge' => $product->getBadge()?->value,
            'mood' => $product->getMood(),
            'occasions' => $product->getOccasions()->map(
                fn($o) => $o->getSlug()
            )->toArray(),
            'budgetTiers' => $product->getBudgetTiers()->map(
                fn($b) => $b->getSlug()
            )->toArray(),
            'inStock' => $product->isInStock(),
            'isGift' => $product->isGift(),
            'canBeCombined' => $product->isCanBeCombined(),
        ];
    }
}
