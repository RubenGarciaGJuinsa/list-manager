<?php


namespace Almacen\Core\Tools\Alert;

use Exception;

class Alert
{

    const VALID_LEVELS = ['success', 'danger', 'warning', 'info'];
    /**
     * @var Alert
     */
    private static Alert $instance;
    private AlertStorageInterface $storage;

    private function __construct($storageClass = 'Almacen\Core\Tools\Alert\SessionStorage')
    {
        $this->storage = new $storageClass();
    }

    public static function getInstance($storageClass = 'Almacen\Core\Tools\Alert\SessionStorage'): self
    {
        if (empty(static::$instance)) {
            static::$instance = new static($storageClass);
        }

        return static::$instance;
    }

    public function add($message, $level = 'info')
    {
        $this->checkLevelIsValid($level);

        $this->pushMessage($level, $message);
    }

    /**
     * @param $level
     * @throws Exception
     */
    protected function checkLevelIsValid($level): void
    {
        if ( ! in_array($level, static::VALID_LEVELS)) {
            throw new Exception('Alert level "'.$level.'" not valid');
        }
    }

    protected function pushMessage($level, $message)
    {
        $this->storage->pushMessage($level, $message);
    }

    public function getMessages($level = '')
    {
        if ( ! empty($level)) {
            $this->checkLevelIsValid($level);
        }

        return $this->storage->getMessages($level);
    }
}