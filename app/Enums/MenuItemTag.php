<?php

namespace App\Enums;

class MenuItemTag
{
    public static function all(): array
    {
        return [
            'new',
            'best-seller',
            'chef-special',
            'popular',
            'recommended',
            'trending',
            'must-try',
            'special',
            'premium',
            'healthy',
            'spicy',
            'mild',
            'sweet',
            'crispy',
            'fresh',
            'homemade',
            'organic',
            'classic',
            'signature',
            'limited-time',
            'seasonal',
            'combo',
            'value-for-money',
            'kids-favorite',
            'customer-favorite',
        ];
    }
}
