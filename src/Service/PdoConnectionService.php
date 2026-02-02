<?php

declare(strict_types=1);

namespace App\Service;

use PDO;

final class PdoConnectionService
{
    public function create(): PDO
    {
        $driver = $_ENV['DATABASE_DRIVER'];
        $host = $_ENV['DATABASE_HOST'];
        $db = $_ENV['DATABASE_NAME'];
        $user = $_ENV['DATABASE_USER'];
        $pass = $_ENV['DATABASE_PASSWORD'];
        $charset = $_ENV['DATABASE_CHARSET'];

        $dsn = "$driver:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, $user, $pass, $options);
    }
}
