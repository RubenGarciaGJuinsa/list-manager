<?php

namespace Test\Core\Tools;

use Almacen\Core\Tools\Alert\AlertStorageInterface;

class FakeAlertStorage implements AlertStorageInterface
{
    public function pushMessage(string $level, string $message)
    {
        if ( ! isset($GLOBALS['appAlerts'][$level])) {
            $GLOBALS['appAlerts'][$level] = [];
        }

        $GLOBALS['appAlerts'][$level][] = $message;
    }

    public function getMessages($level = ''): array
    {
        if ( ! isset($GLOBALS['appAlerts'])) {
            return [];
        }
        if (empty($level)) {
            $messages = $GLOBALS['appAlerts'];
            $GLOBALS['appAlerts'] = [];
        } else {
            $messages = $GLOBALS['appAlerts'][$level];
            $GLOBALS['appAlerts'][$level] = [];
        }

        return $messages;
    }
}