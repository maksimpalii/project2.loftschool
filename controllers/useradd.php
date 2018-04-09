<?php

namespace App;

class Useradd extends MainController
{

    public function index()
    {
        $this->checkSession();

        $userInfo = new Users();
        $dataUser = $userInfo->getUserInfo();

        $this->view->twigLoad('useradd', ['title' => 'Добавить пользователя',
            'page' => 'userlist',
            'userInfo' => $dataUser]);
    }

    public function post()
    {
        $this->checkSession();

        $userModel = new Users();
        $userModel->addUser();
    }
}