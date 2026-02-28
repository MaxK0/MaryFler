<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Очищаем телефон
        $phone = preg_replace('/[^0-9]/', '', $value);

        // Проверяем длину и начало
        if (strlen($phone) !== 11 || !in_array(substr($phone, 0, 1), ['7', '8'])) {
            $fail('Некорректный формат телефона. Используйте +7 (999) 999 99-99');
        }
    }
}
