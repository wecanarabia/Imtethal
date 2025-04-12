<?php

namespace App\Helpers;

class Helper
{
    public static function getRating($rating)
    {
        return match (true) {
            $rating >= 90 && $rating <= 100 => __('views.EXCELLENT'),
            $rating >= 75 && $rating <= 89  => __('views.VERY_GOOD'),
            $rating >= 50 && $rating <= 74  => __('views.GOOD'),
            $rating >= 0  && $rating <= 49  => __('views.POOR'),
        };
    }
}
