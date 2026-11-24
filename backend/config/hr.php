<?php

return [

    /*
    | Weekend days, used when counting leave days (0 = Sunday ... 6 = Saturday).
    | Default: Friday and Saturday. Set HR_WEEKEND_DAYS=6,0 for Saturday/Sunday.
    */
    'weekend_days' => array_map('intval', explode(',', env('HR_WEEKEND_DAYS', '5,6'))),

    'documents_disk' => env('HR_DOCUMENTS_DISK', 'local'),

    'per_page' => 15,

];
