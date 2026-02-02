<?php

declare(strict_types=1);

namespace App\Controller;

use PDO;
use PDOException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function strlen;
use const FILTER_VALIDATE_EMAIL;
use const PASSWORD_DEFAULT;

final readonly class MainController
{
    public function __construct(
        private Environment $twig,
        private LoggerInterface $logger
    ) {
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
            $this->logger->error('Ошибка подключения PDO: ' . __FILE__ . $e->getMessage() . ' Code: ' . $e->getCode());
            return new Response('Регистрация временно недоступна!', 500);
        }

        $name = (string) $request->request->get('username', '');
        $email = (string) $request->request->get('email', '');
        $password = (string) $request->request->get('password', '');

        if ('' === $name || '' === $email || '' === $password) {
            return new Response('Все поля должны быть заполнены!', 409);
        }

        if (0 === preg_match('!^[a-zA-Z]{3,}$!', $name)) {
            return new Response('Имя должно содержать только латиницу, быть одним словом и иметь не менее 3 символов!', 409);
        }

        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response('Некорректный Email!', 409);
        }

        if (
            str_contains($password, ' ')
            || str_contains($password, "\t")
            || str_contains($password, "\n")
            || str_contains($password, "\r")
            || strlen($password) < 8
            || strlen($password) > 30
        ) {
            return new Response('Пароль должен иметь от 8 до 30 символов и не иметь пробелов!', 409);
        }

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM registration WHERE user_email = ?');
        $stmt->execute([$email]);

        if ($stmt->fetchColumn() > 0) {
            return new Response("Email '$email' уже занят!", 409);
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = 'INSERT INTO registration (user_name, user_email, user_password_hash) VALUES (?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $passwordHash]);
            $this->logger->info("Успешная регистрация пользователя '$name'!");

            return new Response("Пользователь '$name' успешно зарегистрирован!", 200);
        } catch (PDOException $e) {
            $this->logger->error('Ошибка SQL при регистрации: ' . __FILE__ . $e->getMessage() . ' Code: ' . $e->getCode());

            return new Response('Произошла ошибка при обработке запроса. Попробуйте позже.', 500);
        }
    }
}
