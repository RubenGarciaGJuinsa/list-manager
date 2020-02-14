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
        $this->title = "Tasks list";

        $tasks = $this->taskManager->getAllTasks();

        $lists = $this->getListsNamesById();

        return $this->render('index', ['tasks'=>$tasks, 'lists' => $lists]);
    }

    public function actionCreate()
    {
        $this->title = 'New Task';

        $formData = Request::getPostParam('task', null);
        if ( ! empty($formData)) {
            try {
                if ($insertId = $this->taskManager->createNewTask($formData['name'], $formData['list_id'])) {
                    Alert::getInstance()->add(Application::t('Article', 'Tarea creada correctamente!'), 'success');
                    header("Location: /task/view/".$insertId);

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

    public function actionView($id = '')
    {
        if (empty($id)) {
            header('Location: /task/index');
        }

        $listsByName = $this->getListsNamesById();

        $task = $this->taskManager->getTask($id);

        return $this->render('view', ['task' => $task, 'lists' => $listsByName]);
    }

    /**
     * @return array
     */
    protected function getListsNamesById(): array
    {
        $lists = (new ListManager(Db::getInstance()))->getLists();
        $listsByName = [];
        foreach ($lists as $list) {
            $listsByName[$list['id']] = $list['name'];
        }

        return $listsByName;
    }

    public function actionUpdate($id = '')
    {
        if (empty($id)) {
            header('Location: /task/index');
        }

        $formData = Request::getPostParam('task', null);
        if ( ! empty($formData)) {
            try {
                if ($this->taskManager->editTask($id, $formData['name'], $formData['list_id'])) {
                    Alert::getInstance()->add(Application::t('Article', 'Tarea editada correctamente!'), 'success');
                    header("Location: /task/view/".$id);

                    return '';
                } else {
                    throw new \Exception(Application::t('Article', 'Error cuando se estaba editando la tarea!'));
                }
            } catch (\Exception $e) {
                Alert::getInstance()->add($e->getMessage(),'danger');
            }
        }

        $task = $this->taskManager->getTask($id);
        $this->title = 'Edit Task: ' . $task['name'];

        $lists = (new ListManager(Db::getInstance()))->getLists();



        return $this->render('form', ['task' => $task, 'lists' => $lists]);
    }

    public function actionDelete($id = '')
    {
        if (empty($id)) {
            header('Location: /task/index');
        }
        try {
            if ($this->taskManager->deleteTask($id)) {
                Alert::getInstance()->add(Application::t('Article', 'Tarea borrada correctamente!'), 'success');
            } else {
                throw new \Exception(Application::t('Article', 'No se pudo borrar la tarea!'));
            }
        } catch (\Exception $e) {
            Alert::getInstance()->add($e->getMessage(),'danger');
        }
        header('Location: /task/index');
    }
}