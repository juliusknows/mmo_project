<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PdoConnectionService;
use App\Validation\RegistrationValidator;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use const PASSWORD_DEFAULT;

final readonly class MainController
{
    public function __construct(
        private Environment $twig,
        private LoggerInterface $logger,
        private RegistrationValidator $validator,
        private PdoConnectionService $pdoService,
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
        $validationError = $this->validator->validate($request);

        if (null !== $validationError) {
            return $validationError;
        }

        try {
            $pdo = $this->pdoService->create();
        } catch (PDOException $e) {
            $this->logger->error('Ошибка подключения PDO: ' . __FILE__ . $e->getMessage());

            return new Response('Регистрация временно недоступна!', 500);
        }

        $email = (string) $request->request->get('email');

        if ($this->isEmailDuplicated($pdo, $email)) {
            return new Response("Email '$email' уже занят!", 409);
        }

        try {
            $this->registerUser($pdo, $request);
            $this->logger->info("Успешная регистрация пользователя $email");

            return new Response('Пользователь успешно зарегистрирован!', 200);
        } catch (PDOException $e) {
            $this->logger->error('Ошибка SQL при регистрации: ' . $e->getMessage());

            return new Response('Произошла ошибка при обработке запроса.', 500);
        }
    }

    private function isEmailDuplicated(PDO $pdo, string $email): bool
    {
        $stmt = $pdo->prepare('SELECT EXISTS(SELECT 1 FROM registration WHERE user_email = ?)');
        $stmt->execute([$email]);

        return $stmt->fetchColumn() > 0;
    }

    private function registerUser(PDO $pdo, Request $request): void
    {
        $name = $request->request->get('username');
        $email = $request->request->get('email');
        $password = (string) $request->request->get('password');
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO registration (user_name, user_email, user_password_hash) VALUES (?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $passwordHash]);
    }
}
