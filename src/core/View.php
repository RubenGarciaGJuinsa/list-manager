<?php


namespace Almacen\core;


use Almacen\Core\Exception\IncorrectViewNameException;
use Almacen\Core\Exception\NotReadableViewFileException;
use Exception;

class View
{
    public $context = null;

    public function render($viewName, array $params, $controllerClass = '', $context = null): string
    {
        $content = '';

        $viewPath = $this->getViewFilePath($viewName, $controllerClass);

        if (empty($viewPath)) {
            throw new IncorrectViewNameException();
        }

        $this->checkIfFileIsReadable($viewPath);
        $content = $this->renderFile($viewPath, $params, $context);


        return $content;
    }

    protected function getViewFilePath(string $viewName, string $controllerClass): ?string
    {
        if (empty($viewName)) {
            return null;
        }
        if ($this->isOnlyViewName($viewName)) {
            $controllerPathName = $this->getControllerPathName($controllerClass);
            $path = $this->getViewBasePath().$controllerPathName.'/'.$viewName.'.php';
        } else {
            if ($this->isViewNameWithController($viewName)) {
                $path = $this->getViewBasePath().$viewName.'.php';
            } else {
                $path = $viewName;
            }
        }

        return $path;
    }

    protected function getControllerPathName(string $classWithNamespace)
    {
        $namespacePieces = explode('\\', $classWithNamespace);
        $className = array_pop($namespacePieces);
        $controllerPathName = preg_replace('/Controller$/', '', $className);
        $controllerPathName = strtolower($controllerPathName);

        return $controllerPathName;
    }

    /**
     * @return string
     */
    protected function getViewBasePath(): string
    {
        return Application::getInstance()->getBasePath().'/src/views/';
    }

    protected function renderFile(string $viewFile, array $params, $context = null): string
    {
        $oldContext = $this->context;
        if ($context !== null) {
            $this->context = $context;
        }
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        try {
            require $viewFile;

            $this->context = $oldContext;

            return ob_get_clean();
        } catch (Exception $e) {
            $this->cleanObBuffer($_obInitialLevel_);
            throw $e;
        }
    }

    public function renderLayout($layout, $viewContent, $context = null): string
    {
        $layoutPath = $this->getLayoutFilePath($layout);

        $this->checkIfFileIsReadable($layoutPath);

        return $this->renderFile($layoutPath, ['content' => $viewContent], $context);
    }

    /**
     * @param int $_obInitialLevel_
     */
    protected function cleanObBuffer(int $_obInitialLevel_): void
    {
        while (ob_get_level() > $_obInitialLevel_) {
            if ( ! @ob_end_clean()) {
                ob_clean();
            }
        }
    }

    /**
     * @param string $filePath
     * @throws NotReadableViewFileException
     */
    protected function checkIfFileIsReadable(string $filePath): void
    {
        if ( ! is_readable($filePath)) {
            throw new NotReadableViewFileException('File "'.$filePath.'" not readable');
        }
    }

    /**
     * @param string|null $layoutName
     * @return string
     */
    protected function getLayoutFilePath(?string $layoutName): string
    {
        if (empty($layoutName)) {
            return null;
        }
        if ($this->isOnlyViewName($layoutName) || $this->isViewNameWithController($layoutName)) {
            $layoutPath = $this->getViewBasePath().'layouts/'.$layoutName.'.php';
        } else {
            $layoutPath = $layoutName;
        }

        return $layoutPath;
    }

    /**
     * @param string $viewName
     * @return false|int
     */
    protected function isOnlyViewName(string $viewName)
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $viewName, $matches);
    }

    /**
     * @param string $viewName
     * @return false|int
     */
    protected function isViewNameWithController(string $viewName)
    {
        return preg_match('/(?<!\.php)$/', $viewName, $matches);
    }
}