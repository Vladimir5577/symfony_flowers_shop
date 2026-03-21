<?php

namespace App\Controller\Api;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\DeliveryType;
use App\Enum\OrderStatus;
use App\Enum\PaymentMethod;
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class OrderController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private CustomerRepository $customerRepository,
    ) {}

    #[Route('/orders', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $order = new Order();
        $order->setOrderNumber($this->generateOrderNumber());
        $order->setDeliveryType(DeliveryType::from($data['delivery']['type'] ?? 'delivery'));
        $order->setDeliveryAddress($data['delivery']['address'] ?? null);
        $order->setDeliveryTimeSlot($data['delivery']['timeSlot'] ?? null);
        $order->setRecipientName($data['recipient']['name'] ?? '');
        $order->setRecipientPhone($data['recipient']['phone'] ?? '');
        $order->setComment($data['recipient']['comment'] ?? null);
        $order->setPaymentMethod(PaymentMethod::from($data['paymentMethod'] ?? 'cash'));

        $totalAmount = 0;

        foreach ($data['items'] ?? [] as $itemData) {
            $product = $this->productRepository->findOneBy(['slug' => $itemData['productId']]);

            $item = new OrderItem();
            $item->setProduct($product);
            $item->setQuantity($itemData['quantity'] ?? 1);
            $item->setPrice($itemData['snapshotPrice'] ?? $product?->getPrice() ?? 0);
            $item->setProductName($itemData['snapshotName'] ?? $product?->getName() ?? '');
            $item->setProductPhoto($itemData['snapshotPhoto'] ?? '');

            $totalAmount += $item->getPrice() * $item->getQuantity();
            $order->addItem($item);
        }

        $order->setTotalAmount($totalAmount);

        if ($order->getPaymentMethod() === PaymentMethod::Sbp) {
            $order->setStatus(OrderStatus::PaymentPending);
        }

        // Link customer if phone matches
        $phone = $order->getRecipientPhone();
        if ($phone) {
            $customer = $this->customerRepository->findOneBy(['phone' => $phone]);
            if ($customer) {
                $order->setCustomer($customer);
            }
        }

        $this->em->persist($order);
        $this->em->flush();

        return $this->json([
            'orderId' => $order->getOrderNumber(),
        ], 201);
    }

    private function generateOrderNumber(): string
    {
        return 'BF-' . strtoupper(bin2hex(random_bytes(4)));
    }
}
