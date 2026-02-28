<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Мутатор: очищает телефон перед сохранением в БД
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $this->cleanPhone($value);
    }

    /**
     * Аксессор: форматирует телефон для отображения
     */
    public function getPhoneAttribute($value)
    {
        return $this->formatPhone($value);
    }

    /**
     * Получить "чистый" телефон для БД
     */
    public function getRawPhoneAttribute(): string
    {
        return $this->attributes['phone'] ?? '';
    }

    /**
     * Очистка телефона от всех символов кроме цифр
     */
    public static function cleanPhone(string $phone): string
    {
        // Удаляем всё кроме цифр
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Если номер начинается с 8, заменяем на 7
        if (Str::startsWith($phone, '8')) {
            $phone = '7' . substr($phone, 1);
        }

        // Если номер начинается с 7, оставляем как есть
        if (Str::startsWith($phone, '7')) {
            return $phone;
        }

        // Если номер начинается с другой цифры, добавляем 7
        return '7' . $phone;
    }

    /**
     * Форматирование телефона для отображения
     */
    private function formatPhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        // Получаем чистый номер
        $phone = $this->cleanPhone($phone);

        // Форматируем: +7 (999) 999 99-99
        return '+7 (' .
            substr($phone, 1, 3) . ') ' .
            substr($phone, 4, 3) . ' ' .
            substr($phone, 7, 2) . '-' .
            substr($phone, 9, 2);
    }
}
