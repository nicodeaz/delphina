<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Calendar Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Calendar API integration
    |
    */

    'admin_calendar_id' => env('GOOGLE_CALENDAR_ADMIN_ID', 'primary'),

    'credentials_path' => storage_path('app/google-calendar/credentials.json'),

    'token_path' => storage_path('app/google-calendar/token.json'),

    /*
    |--------------------------------------------------------------------------
    | Sync Settings
    |--------------------------------------------------------------------------
    */

    'sync_days_ahead' => 30,

    'sync_interval_minutes' => 15, // How often to run sync command
];