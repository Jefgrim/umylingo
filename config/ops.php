<?php

return [
    'mysqldump_paths' => [
        'env' => env('MYSQLDUMP_PATH'),
        'windows' => [
            'C:\\ServBay\\service\\mysql\\bin\\mysqldump.exe',
            'C:\\ServBay\\service\\mariadb\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files (x86)\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.32\\bin\\mysqldump.exe',
        ],
        'darwin' => [
            '/Applications/ServBay/package/mysql/8.4/8.4.7/bin/mysqldump',
            '/opt/homebrew/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ],
        'linux' => [
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ],
    ],

    'mysql_paths' => [
        'env' => env('MYSQL_PATH'),
        'windows' => [
            'C:\\ServBay\\service\\mysql\\bin\\mysql.exe',
            'C:\\ServBay\\service\\mariadb\\bin\\mysql.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysql.exe',
            'C:\\Program Files (x86)\\MySQL\\MySQL Server 8.0\\bin\\mysql.exe',
            'C:\\xampp\\mysql\\bin\\mysql.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.32\\bin\\mysql.exe',
        ],
        'darwin' => [
            '/Applications/ServBay/package/mysql/8.4/8.4.7/bin/mysql',
            '/opt/homebrew/bin/mysql',
            '/usr/local/bin/mysql',
        ],
        'linux' => [
            '/usr/bin/mysql',
            '/usr/local/bin/mysql',
        ],
    ],
];
