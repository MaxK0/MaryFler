<?php

namespace App\Enums;

enum OrderStatus: string
{
    case NEW = 'Новое';
    case IN_PROGRESS = 'В работе';
    case READY = 'Готово к получению';
    case COMPLETED = 'Получено';
    case CANCELLED = 'Отменено';
}
