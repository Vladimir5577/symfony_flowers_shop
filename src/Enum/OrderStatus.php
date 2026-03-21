<?php

namespace App\Enum;

enum OrderStatus: string
{
    case Pending = 'pending';
    case PaymentPending = 'payment_pending';
    case PaymentProcessing = 'payment_processing';
    case Paid = 'paid';
    case Confirmed = 'confirmed';
    case InDelivery = 'in_delivery';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Failed = 'failed';
}
