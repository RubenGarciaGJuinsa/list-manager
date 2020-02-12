<?php


namespace Almacen\Core;


abstract class Controller
{
    protected string $layout;

    public string $title = '';

    public function __construct()
    {
        $this->title = Application::getInstance()->getName();
    }

    public function run($actionName, $actionParams = [])
    {
        return call_user_func_array([$this, $actionName], $actionParams);
    }

    /**
     * @param string $layout
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    protected function render(string $viewName, $params = []): string
    {
        $view = new View();
        $viewContent = $view->render($viewName, $params, get_class($this), $this);

        if (empty($this->layout)) {
            return $viewContent;
        }

        $content = $view->renderLayout($this->layout, $viewContent, $this);

        return $content;
    }
}