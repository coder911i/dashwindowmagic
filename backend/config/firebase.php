<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Project ID
    |--------------------------------------------------------------------------
    */
    'project_id' => env('FIREBASE_PROJECT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | The path to the JSON file containing the Firebase service account 
    | credentials.
    */
    'credentials' => [
        'file' => base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-auth.json')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firestore Config
    |--------------------------------------------------------------------------
    */
    'firestore' => [
        'database' => '(default)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Realtime Database URL
    |--------------------------------------------------------------------------
    */
    'database_url' => env('FIREBASE_DATABASE_URL'),
];
