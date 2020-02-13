<?php


namespace Kata\Controllers;


use Almacen\Core\Application;
use Almacen\Core\Db\Db;
use Almacen\Core\Request;
use Almacen\Core\Tools\Alert\Alert;
use Kata\ListManager;
use Kata\TaskManager;

class TaskController extends BaseController
{
    protected TaskManager $taskManager;
    public function __construct()
    {
        parent::__construct();
        $this->taskManager = new TaskManager(Db::getInstance());
    }

    public function actionIndex()
    {
        $tasks = $this->taskManager->getTasksFromList(1);

        return $this->render('index', ['tasks'=>$tasks]);
    }

    public function actionCreate()
    {
        $this->title = 'New Task';

        $formData = Request::getPostParam('task', null);
        if ( ! empty($formData)) {
            try {
                if ($this->taskManager->createNewTask($formData['name'], $formData['list_id'])) {
                    Alert::getInstance()->add(Application::t('Article', 'Tarea creada correctamente!'), 'success');
                    header("Refresh:0");

                    return '';
                } else {
                    throw new \Exception(Application::t('Article', 'Error cuando se estaba creando la tarea!'));
                }
            } catch (\Exception $e) {
                Alert::getInstance()->add($e->getMessage(),'danger');
            }
        }

        $lists = (new ListManager(Db::getInstance()))->getLists();

        return $this->render('form', ['lists' => $lists]);
    }
}