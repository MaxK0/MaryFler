<?php

namespace App\Enums;

enum OrderStatus: string
{
    case NEW = 'Новое';
    case IN_PROGRESS = 'В работе';
    case READY = 'Готово к получению';
    case COMPLETED = 'Получено';
    case CANCELLED = 'Отменено';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function toArrayWithKeys(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
