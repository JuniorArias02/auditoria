<?php

namespace App\Models;

class SystemLog
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $channel, string $level, string $message, string $createdAt): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO system_logs (channel, level, message, created_at)
            VALUES (:channel, :level, :message, :created_at)
        ");

        $stmt->execute([
            ':channel'    => $channel,
            ':level'      => $level,
            ':message'    => $message,
            ':created_at' => $createdAt
        ]);
    }
}
