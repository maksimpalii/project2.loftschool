<?php

namespace App;
class Logout extends MainController
{
    public function index(){
        unset($_SESSION["user"]);
        unset($_SESSION["login"]);
        session_destroy();
        header('Location: /', true, 307);
        die();
    }
}