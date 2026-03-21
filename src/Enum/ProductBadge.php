<?php

namespace App\Enum;

enum ProductBadge: string
{
    case Hit = 'Хит';
    case New = 'Новинка';
    case Choice = 'Выбор';
    case Max = 'Макс';
}
