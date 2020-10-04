<?php

namespace App\Helpers;

class CurrencyHelper
{
    
    private static $currencyCurrencyCode = 'USD';
    
    private static $currencySymbol = '&#36;';
    
    private static $currencyConverter = 1;

    public static function init()
    {
        if(session()->has('currentCurrency')) {
            
            $currency = session()->get('currentCurrency');
            
            self::$currencyConverter = round($currency->geoplugin_currencyConverter, 2);
            self::$currencySymbol = $currency->geoplugin_currencySymbol;
            self::$currencyCurrencyCode = $currency->geoplugin_currencyCode;
        }
     }

    public static function getPrice($price) 
    {
        $symbol = self::getSymbol();
        $convertPrice = self::getTotalPrice($price);
        
        return $symbol.' '.number_format($convertPrice, 2);
    }

    public static function currencyFormat($price)
    {
        return number_format($price, 2);
    }

    public static function getPriceWithoutSymbol($price)
    {
        return static::getTotalPrice($price);
    }

    public static function getSymbol()
    {
        CurrencyHelper::init();
        
        return (!empty(self::$currencySymbol)) ? self::$currencySymbol : self::$currencyCurrencyCode;
    }

    private static function getTotalPrice($price) 
    {
        CurrencyHelper::init();

        return round($price * self::$currencyConverter, 2);
    }

    public static function getCurrencyCode()
    {
        CurrencyHelper::init();

        return self::$currencyCurrencyCode;
    }
    
    public static function getCurrencyConverter()
    {
        CurrencyHelper::init();
        
        return self::$currencyConverter;
    }
}