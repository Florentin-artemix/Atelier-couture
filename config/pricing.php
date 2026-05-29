<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Taux de reduction quand le client fournit un accessoire/tissu
    |--------------------------------------------------------------------------
    */
    'taux_reduction_fourni_client' => (float) env('PRICING_TAUX_REDUCTION_FOURNI', 0.80),

    /*
    |--------------------------------------------------------------------------
    | Bornes du coefficient de complexite
    |--------------------------------------------------------------------------
    */
    'coefficient_min' => 0.50,
    'coefficient_max' => 5.00,

    /*
    |--------------------------------------------------------------------------
    | Prix minimum d'une commande (0 = pas de minimum)
    |--------------------------------------------------------------------------
    */
    'prix_minimum_commande' => 0.00,

];
