<?php

namespace App\Controller\Api;

use App\Entity\Payment;
use App\Enum\OrderStatus;
use App\Enum\PaymentStatus;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/payment')]
class PaymentController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderRepository $orderRepository,
        private PaymentRepository $paymentRepository,
    ) {}

    #[Route('/create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $orderId = $data['orderId'] ?? null;
        $amount = $data['amount'] ?? null;

        if (!$orderId || !$amount) {
            return $this->json(['error' => 'orderId and amount required'], 400);
        }

        $order = $this->orderRepository->findOneBy(['orderNumber' => $orderId]);

        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }

        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setAmount($amount);
        $payment->setExpiresAt(new \DateTimeImmutable('+15 minutes'));

        // TODO: integrate with real payment provider (YuKassa)
        // For now, return mock data
        $payment->setQrCode('https://qr.nspk.ru/mock?order=' . $orderId);
        $payment->setDeepLink('bank://pay?order=' . $orderId);

        $this->em->persist($payment);
        $this->em->flush();

        return $this->json([
            'paymentId' => (string) $payment->getId(),
            'qrCode' => $payment->getQrCode(),
            'deepLink' => $payment->getDeepLink(),
            'expiresAt' => $payment->getExpiresAt()?->format('c'),
        ]);
    }

    #[Route('/status/{paymentId}', methods: ['GET'])]
    public function status(int $paymentId): JsonResponse
    {
        $payment = $this->paymentRepository->find($paymentId);

        if (!$payment) {
            return $this->json(['error' => 'Payment not found'], 404);
        }

        return $this->json([
            'status' => $payment->getStatus()->value,
        ]);
    }
}
