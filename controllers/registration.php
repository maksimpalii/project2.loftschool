<?php

namespace App;

class Registration extends MainController
{
    public function index()
    {
        $userInfo = new Users();
        $dataUser = $userInfo->getUserInfo();

        $this->view->twigLoad('registration', ['title' => 'Регистрация', 'page' => 'registr', 'userInfo' => $dataUser]);
    }

    public function post()
    {
        $userModel = new Users();
        $userModel->registrUser();
    }
}
