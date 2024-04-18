<?php

namespace App\CentralLogics;
use App\Models\BusinessSetting;

class Helpers {
    public static function get_business_settings($name) {
        $config = null;
        $paymentmethod = BusinessSetting::where('key', $name)->first();

        if($paymentmethod != null) {
            $config = json_decode(json_encode($paymentmethod->value), true);
            $config = json_decode($config, true);
        }

        return $config;
    }

    public static function currency_code() {
        return BusinessSetting::where(['key' => 'currency'])->first()->value;
    }
}