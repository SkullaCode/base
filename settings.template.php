<?php
return [
    'settings'                  =>  [
        'displayErrorDetails'           => true, // set to false in production
        'addContentLengthHeader'        => false, // Allow the web server to send the content-length header
        'email'                 =>  [
            'user-agent'                =>  'AxiumApplication',
            'protocol'                  =>  'smtp',
            'host'                      =>  'localhost',
            'port'                      =>  '25',
            'username'                  =>  'testuser1@localhost.mail',
            'password'                  =>  'login',
            'secure-with'               =>  '',
            'mail-type'                 =>  'html',
            'from'                      =>  'testuser1@localhost.mail',
            'from-name'                 =>  'Baseline Application',
            'template-directory'        =>  __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'email' . DIRECTORY_SEPARATOR
        ],
        'company'               =>  [
            'site_title'                =>  'Basline Application',
            'name'                      =>  'Baseline Application',
            'address_line_1'            =>  'Manchester Rd.',
            'address_line_2'            =>  '',
            'city'                      =>  'Mandeville',
            'state'                     =>  'Manchester',
            'country'                   =>  'Jamaica',
            'phone_number'              =>  '1-876-539-9671',
            'cell_phone_number'         =>  '',
            'fax_number'                =>  '',
            'email_address'             =>  'eja@axium.io',
            'po_box'                    =>  ''
        ],
        'config'           =>  [
            'root_directory'            =>  __DIR__,
            'utility_directory'         =>  __DIR__. DIRECTORY_SEPARATOR . 'app'. DIRECTORY_SEPARATOR . 'utilities'. DIRECTORY_SEPARATOR,
            'provider_directory'        =>  __DIR__. DIRECTORY_SEPARATOR . 'app'. DIRECTORY_SEPARATOR . 'providers'. DIRECTORY_SEPARATOR,
            'error_page_directory'      =>  __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'error_pages'. DIRECTORY_SEPARATOR,
            'storage_directory'         =>  __DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR,
            'template_path'             =>  __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR,
            'pre_loader'                =>  __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'index.php',
            'mode'                      =>  'development',
            'development'               =>  [

                'database'                      =>  [
                    'database_type'                     =>  'sqlite',
                    'database_file'                     =>  __DIR__. DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'Default.db'
                ],
                'context_directory'             =>  __DIR__. DIRECTORY_SEPARATOR . 'dev'. DIRECTORY_SEPARATOR . 'contexts'. DIRECTORY_SEPARATOR,

                /*'database'    =>  [
                    'database_type'     =>  'mysql',
                    'database_name'     =>  'eja_dev',
                    'server'            =>  'localhost',
                    'username'          =>  'root',
                    'password'          =>  '',
                    'port'              =>  3306
                ],*/

            ],
            'production'                =>  [

                'database'                      =>  [
                    'database_type'                     =>  'sqlite',
                    'database_file'                     =>  __DIR__. DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'Default.db'
                ]
                /*'database'    =>  [
                    'database_type'     =>  'mysql',
                    'database_name'     =>  'eja_dev',
                    'server'            =>  'localhost',
                    'username'          =>  'root',
                    'password'          =>  '',
                    'port'              =>  3306
                ],*/

            ]

        ]
    ]
];