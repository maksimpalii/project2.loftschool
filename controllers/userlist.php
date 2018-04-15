<?php

namespace App;

class Userlist extends MainController
{

    public function index()
    {
        $this->checkSession();

        $userInfo = new Users();
        $dataUser = $userInfo->getUserInfo();
        $dataPage['user'] = $dataUser;


        $data = $userInfo->allUser();

        $data_age = array();
        foreach ($data as $key => $arr) {
            $data_age[$key] = $arr['age'];
        }

        array_multisort($data_age, SORT_NUMERIC, SORT_DESC, $data);

        //$dataPage['page'] = 'userlist';
        // $this->view->render('userlist', $data, $dataPage);
        $this->view->twigLoad('userlist', ['title' => 'Список пользователей',
            'page' => 'userlist',
            'userInfo' => $dataUser,
            'users' => $data]);
    }


    public function delete()
    {
        $this->checkSession();

        $userInfo = new Users();
        $dataUser = $userInfo->getUserInfo();
        $dataPage['user'] = $dataUser;

        $data = $userInfo->deleteUser();
        if ($data === 'Вы удалили себя!') {
            unset($_SESSION["user"]);
            unset($_SESSION["login"]);
            session_destroy();
        }
        $dataPage['page'] = 'userlist';

        $this->view->twigLoad('userdelete', ['title' => 'Список пользователей',
            'page' => 'userlist',
            'userInfo' => $dataUser,
            'msg' => $data]);
    }

    public function edit()
    {
        $this->checkSession();

        $userInfo = new Users();
        $dataUser = $userInfo->getUserInfo();

        $data = $userInfo->getUser();
        $dataPage['page'] = 'userlist';

        $this->view->twigLoad('useredit', ['title' => 'Редактировать пользователя',
            'page' => 'userlist',
            'userInfo' => $dataUser,
            'user' => $data]);
    }

    public function post()
    {
        $this->checkSession();

        $editUser = new Users();
        $editUser->editUser();
    }

    public function sort($param)
    {
        $this->checkSession();

        $userInfo = new Users();
        $dataUser = $userInfo->getUserInfo();
        $dataPage['user'] = $dataUser;

        $data = $userInfo->allUserFilter($param);

        $datas = json_encode($data, JSON_UNESCAPED_UNICODE);
        print_r($datas);
    }

}