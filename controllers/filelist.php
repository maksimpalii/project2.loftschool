<?php

namespace App;

class Filelist extends MainController
{
    public function index()
    {
        $this->checkSession();

        $userInfo = new Users();
        $dataUser = $userInfo->getUserInfo();

        $data = $userInfo->allPhoto();

        //$this->view->render('filelist', $data, $dataPage);
        $this->view->twigLoad('filelist', ['title' => 'Список файлов',
            'page' => 'filelist',
            'userInfo' => $dataUser,
            'users' => $data]);
    }
    public function delete()
    {
        $this->checkSession();

        $userInfo = new Users();
        $dataUser = $userInfo->getUserInfo();
        $data = $userInfo->deleteAvatar();

        $this->view->twigLoad('filelistdelete', ['title' => 'Аватарка удалена',
            'page' => 'userlist',
            'userInfo' => $dataUser,
            'msg' => $data]);
        //$this->view->render('userdelete', $datas, $dataPage);
    }
}