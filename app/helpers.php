<?php

//if (!function_exists('formatPhone')) {
//    /**
//     * Форматирование номера телефона в формат +7 (999) 999 99-99
//     *
//     * @param string $phone Номер телефона в формате 79999999999
//     * @return string Отформатированный номер телефона
//     */
//    function formatPhone($phone)
//    {
//        // Удаляем все нецифровые символы
//        $phone = preg_replace('/[^0-9]/', '', $phone);
//
//        // Если номер начинается с 8, заменяем на 7
//        if (strlen($phone) === 11 && $phone[0] === '8') {
//            $phone = '7' . substr($phone, 1);
//        }
//
//        // Проверяем длину номера
//        if (strlen($phone) !== 11) {
//            return $phone; // Возвращаем как есть, если формат неверный
//        }
//
//        // Форматируем номер
//        return '+' . $phone[0] . ' (' . substr($phone, 1, 3) . ') ' . substr($phone, 4, 3) . ' ' . substr($phone, 7, 2) . '-' . substr($phone, 9, 2);
//    }
//}
