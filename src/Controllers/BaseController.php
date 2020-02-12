<?php


namespace Kata\Controllers;

use Almacen\Core\Controller;
use Almacen\Core\Tools\Alert\Alert;
use Kata\Models\MenuElement;

class BaseController extends Controller
{
    public array $menuElements;
    protected string $layout = 'main';
    /**
     * @var array
     */
    public array $alertMessages;

    public function __construct()
    {
        parent::__construct();

        $this->menuElements = [
            new MenuElement('Home', '/', 'active'),
            new MenuElement('Tasks', '/task', ''),
        ];
    }

    public function render(string $viewName, $params = []): string
    {
        $this->alertMessages = Alert::getInstance()->getMessages();

        return parent::render($viewName, $params);
    }
}