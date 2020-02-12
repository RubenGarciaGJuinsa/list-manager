<?php


namespace Kata\Controllers;

class SiteController extends BaseController
{
    public function actionIndex($mensaje = '')
    {
        return $this->render('index', ['mensaje' => $mensaje]);
    }
}