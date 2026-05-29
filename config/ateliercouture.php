<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rappels automatiques
    |--------------------------------------------------------------------------
    */
    'rappel_pre_livraison_jours' => (int) env('RAPPEL_PRE_LIVRAISON_JOURS', 2),
    'rappel_precommande_heures' => (int) env('RAPPEL_PRECOMMANDE_HEURES', 24),
    'rappel_relance_retard_jours' => (int) env('RAPPEL_RELANCE_RETARD_JOURS', 3),

    /*
    |--------------------------------------------------------------------------
    | Commandes
    |--------------------------------------------------------------------------
    */
    'reference_prefix' => 'CMD',
    'default_duree_precommande_jours' => 14,

    /*
    |--------------------------------------------------------------------------
    | Upload d'images
    |--------------------------------------------------------------------------
    */
    'max_image_size_kb' => 5120,
    'max_images_supplementaires' => 10,
    'images_disk' => env('IMAGES_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Suivi client
    |--------------------------------------------------------------------------
    */
    'lien_suivi_length' => 64,

];
