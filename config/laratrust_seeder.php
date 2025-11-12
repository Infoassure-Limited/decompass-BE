<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'super' => [
            'consulting' => 'c,r,u,d',
            'clients' => 'c,r,u,d',
            'staff' => 'c,r',
            'client project' => 'c,r,u,d',
            'standards' => 'c,r,u,d',
            'clauses' => 'c,r,u,d',
            'evidence' => 'c,r,u,d',
            'gap assessment' => 'c,r,u,d',
            'client certificate' => 'up',
            'feedback form' => 'm',
        ],
        'admin' => [
            'clients' => 'c,r,u',
            'staff' => 'r',
            'client project' => 'c,r,u',
            'standards' => 'c,r,u',
            'clauses' => 'c,r,u',
            'evidence' => 'c,r,u',
            'gap assessment' => 'c,r,u',
            'client certificate' => 'up',
        ],
        'client' => [],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'up' => 'upload',
        'm' => 'manage'
    ]
];
