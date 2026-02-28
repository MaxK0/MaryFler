<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Устанавливаем предполагаемое время готовности заказа
        if (!$order->estimated_completion) {
            $order->estimated_completion = now()->addHour();
            $order->save();
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Если статус заказа изменился на "Готово к получению", обновляем предполагаемое время готовности
        if ($order->isDirty('status') && $order->status === OrderStatus::READY->value) {
            $order->estimated_completion = now();
            $order->save();
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
