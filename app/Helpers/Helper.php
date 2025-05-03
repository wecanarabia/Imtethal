<?php

namespace App\Helpers;

use App\Enums\DeliveryStatusEnum;

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

    public static function getDeliveryClass($status)
    {
        return match (true) {
            $status == DeliveryStatusEnum::ON_TIME->value => 'on-time',
            $status == DeliveryStatusEnum::WITHIN_GRACE_PERIOD->value => 'within-grace-period',
            $status == DeliveryStatusEnum::DELAYED->value => 'delayed',
            $status == '' || $status == null => '',
        };
    }

    public static function getRecordId()
    {
        $url = Request()->route()->parameters()['id'];
        return $url;
    }
}
