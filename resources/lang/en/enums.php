<?php

return [

    \App\Enums\BatchStatus::class => [
        \App\Enums\BatchStatus::InProduction => 'In production',
        \App\Enums\BatchStatus::Shipping     => 'Shipping',
        \App\Enums\BatchStatus::Finished     => 'Finished',
    ],
];

