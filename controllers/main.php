<?php

namespace App;

class Main extends MainController
{
    public function index()
    {
        $userInfo = new Users();
        $dataUser = $userInfo->getUserInfo();

        //$this->view->render('main', $data, $dataPage);'page'] = 'registr';
        $this->view->twigLoad('main', ['title' => 'Главная', 'page' => 'home', 'userInfo' => $dataUser]);
    }

    public function post()
    {
        $loginUser = new Users();
        $loginUser->loginUser();
    }
}