<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Correos administradores
    |--------------------------------------------------------------------------
    |
    | Solo estos correos podran acceder al panel de administracion. El resto
    | de usuarios iniciaran sesion en la zona general de la aplicacion.
    |
    */
    'admin_emails' => array_values(array_unique(array_filter(array_map(
        static fn (string $email): string => mb_strtolower(trim($email)),
        array_merge(
            explode(',', (string) env('GEES_ADMIN_EMAILS', 'admin@gees.local')),
            [(string) env('GEES_ADMIN_EMAIL', 'admin@gees.local')]
        )
    )))),
];
