<?php

declare(strict_types=1);

namespace App\Controller;

use PDO;
use PDOException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use const PASSWORD_DEFAULT;

final class MainController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index(): Response
    {
        $content = $this->twig->render('index.html.twig');

        return new Response($content);
    }

    public function registration(Request $request): Response
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

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            return new Response('Connection error: ' . $e->getMessage(), 500);
        }

        $name = (string) $request->request->get('username', '');
        $email = (string) $request->request->get('email', '');
        $password = (string) $request->request->get('password', '');

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = 'INSERT INTO registration (user_name, user_email, user_password_hash) VALUES (?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $passwordHash]);

            return new Response('Ok! The user is registered!', 200);
        } catch (PDOException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();

            $errorType = '';
            if ('23000' === $errorCode) {
                if (str_contains($errorMessage, 'user_email')) {
                    $errorType = 'The email already exists!';
                } elseif (str_contains($errorMessage, 'user_name')) {
                    $errorType = 'The username already exists!';
                }
                return new Response($errorType, 409);
            }

            return new Response('DB error: ' . $errorMessage, 500);
        }
    }
}
