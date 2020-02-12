<?php


namespace Almacen\Core\Tools\Alert;


class SessionStorage implements AlertStorageInterface
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ( ! isset($_SESSION['appAlerts'])) {
            $_SESSION['appAlerts'] = [];
        }
    }

    public function pushMessage(string $level, string $message)
    {
        if ( ! isset($_SESSION['appAlerts'][$level])) {
            $_SESSION['appAlerts'][$level] = [];
        }

        $_SESSION['appAlerts'][$level][] = $message;
    }

    public function getMessages($level = ''): array
    {
        if (empty($level)) {
            $messages = $_SESSION['appAlerts'];
            $_SESSION['appAlerts'] = [];
        } else {
            $messages = $_SESSION['appAlerts'][$level];
            $_SESSION['appAlerts'][$level] = [];
        }

        return $messages;
    }
}