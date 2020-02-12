<?php


namespace Kata\Controllers;


use Exception;

class ErrorController extends BaseController
{
    public function actionIndex(Exception $e)
    {
        return $this->render('index', ['exception' => $e]);
    }
}