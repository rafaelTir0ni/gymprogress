<?php
declare(strict_types=1);
final class Usuario
{
    public static function byEmail(string $email): ?array
    {
        $query = db()->prepare('SELECT id, nome, email, senha_hash FROM usuarios WHERE email = :email LIMIT 1');
        $query->execute(['email' => $email]);
        return $query->fetch() ?: null;
    }
    public static function create(string $name, string $email, string $password): void
    {
        $query = db()->prepare('INSERT INTO usuarios (nome, email, senha_hash) VALUES (:nome, :email, :senha_hash)');
        $query->execute(['nome' => $name, 'email' => $email, 'senha_hash' => password_hash($password, PASSWORD_DEFAULT)]);
    }
}
