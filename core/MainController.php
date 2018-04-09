<?php

namespace App;

class MainController
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function checkSession()
    {
        if ($_SESSION['user'] !== 'logged') {
            header('Location: /', true, 307);
            die();
        }
    }

}