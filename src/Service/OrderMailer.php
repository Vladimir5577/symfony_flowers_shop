<?php

namespace App\Service;

use App\Entity\Order;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class OrderMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private LoggerInterface $logger,
        private string $adminEmail,
        private string $mailerFrom,
    ) {}

    public function sendNewOrderNotification(Order $order): void
    {
        if (!$this->adminEmail) {
            return;
        }

        try {
            $html = $this->twig->render('email/order_notification.html.twig', ['order' => $order]);

            $email = (new Email())
                ->from($this->mailerFrom)
                ->to($this->adminEmail)
                ->subject(sprintf('Новый заказ %s на сумму %d ₽', $order->getOrderNumber(), $order->getTotalAmount()))
                ->html($html);

            $this->mailer->send($email);
        } catch (\Throwable $e) {
            $this->logger->error('Failed to send order notification email', [
                'order' => $order->getOrderNumber(),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
