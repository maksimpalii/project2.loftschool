<?php

namespace App;

class Listd extends MainController
{
    public function index()
    {
//
    }

    public function post()
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

        $datas = json_encode($data, JSON_UNESCAPED_UNICODE);
        print_r($datas);

    }
}