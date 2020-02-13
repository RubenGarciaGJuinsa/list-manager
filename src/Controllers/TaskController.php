<?php


namespace Kata\Controllers;


use Almacen\Core\Application;
use Almacen\Core\Db\Db;
use Almacen\Core\Request;
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
}