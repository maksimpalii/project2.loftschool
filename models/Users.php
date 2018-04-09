<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{

    protected $guarded = ['id'];
    public $timestamps = false;
    public $table = "users";

    public function getUserInfo()
    {
        $userModel = new Users();
        $data = $userModel::query()->select('name', 'photo')
            ->where('login', '=', $_SESSION['login'])
            ->get();
        return $data->toArray()[0];
    }

    public function clearAll($dataIn, $isArray = false)
    {
        if ($isArray === false) {
            $data_ = strip_tags($dataIn);
            $data = htmlspecialchars($data_, ENT_QUOTES);
        } else {
            $data = [];
            foreach ($dataIn as $key => $value) {
                $data[$key] = htmlspecialchars(strip_tags($value), ENT_QUOTES);
            }
        }
        return $data;
    }

    private function getUserAvatar($id)
    {
        $userModel = new Users();
        $data = $userModel::query()->select('photo')
            ->where('id', '=', $id)
            ->get();
        return $data->toArray()[0];
    }

    public function getUser()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $id = $routes[3];
        $userModel = new Users();
        $data = $userModel::first()
            ->where('id', '=', $id)
            ->get();
        return $data->toArray()[0];
    }

    private function checkLogin($login)
    {
        $userModel = new Users();
        $data = $userModel::query()
            ->select('login')
            ->where('login', '=', $login)
            ->get();
        return $data->toArray()[0];
    }

    public function loginUser()
    {
        $userModel = new Users();
        $login = $_POST['login'];
        $paswword = $_POST['password'];
        $logged = '';
        if (!empty($login) && !empty($paswword)) {
            $paswword_ver = $userModel->cryptPasswd($paswword);
            $datas = $this->autentificationUser($paswword_ver, $login);
            if (!empty($datas)) {
                if ($datas['login'] === $login) {
                    $logged = 'Logged succes';
                    $_SESSION['user'] = 'logged';
                    $_SESSION['login'] = $login;
                }
            } else {
                $logged = 'No user';
            }
        } else {
            $logged = 'Not empty';
        }
        echo $logged;
    }

    public function editUser()
    {
        if ($_SESSION['user'] !== 'logged') {
            header('Location: /');
            die();
        }
        $userModel = new Users();
        if (!empty($_POST['login']) && !empty($_POST['name'])
            && !empty($_POST['age']) && !empty($_POST['description'])) {
            $routes = explode('/', $_SERVER['REQUEST_URI']);
            $login = $this->clearAll($_POST['login']);
            $paswword = $this->clearAll($_POST['password']);
            $name = $this->clearAll($_POST['name']);
            $age = $this->clearAll($_POST['age']);
            $description = $this->clearAll($_POST['description']);

            $fileUpload = $_FILES;
            //var_dump($_FILES);
            if ($fileUpload['image']['size'] === 0) {
                //echo 'file not' . PHP_EOL;
                if ($paswword === '') {
                    $data = ['login' => $login, 'name' => $name, 'age' => $age, 'description' => $description];
                    $userModel->updateUser($data, $routes[3]);
                } else {
                    $paswword = $userModel->cryptPasswd($paswword);
                    $data = ['login' => $login,
                        'password' => $paswword,
                        'name' => $name,
                        'age' => $age,
                        'description' => $description];
                    $userModel->updateUser($data, $routes[3]);
                    // echo 'yes' . PHP_EOL;
                }
            } else {
                //echo 'file yes' . PHP_EOL;
                $userModel = new Users();
                $inImg = $userModel->getUserAvatar($routes[3]);
                //var_dump($inImg);
                if ($userModel->deleteOnlyPhoto($inImg['photo']) === 'delete') {
                    $fileUp = new File();
                    $img_url = $fileUp->uploadImg($fileUpload, $login);
                    if ($img_url !== null) {
                        //echo 'new img update' .PHP_EOL;
                        if ($paswword === '') {
                            $data = ['login' => $login,
                                'name' => $name,
                                'age' => $age,
                                'description' => $description,
                                'img_url' => $img_url];
                            $userModel->updateUser($data, $routes[3]);
                        } else {
                            $paswword = $userModel->cryptPasswd($paswword);
                            $data = ['login' => $login,
                                'password' => $paswword,
                                'name' => $name,
                                'age' => $age,
                                'description' => $description,
                                'img_url' => $img_url];
                            $userModel->updateUser($data, $routes[3]);
                        }
                    }
                } else {
                    echo 'Wrong delete file';
                }
            }
        } else {
            echo 'Not empty';
        }
    }

    public function registrUser()
    {
        $is_valid = \GUMP::is_valid(array_merge($_POST, $_FILES), array(
            //$is_valid = \GUMP::is_valid($_POST, array(
            'login' => 'required|alpha_numeric',
            'password' => 'required|max_len,100|min_len,6',
            'password_repeat' => 'required|max_len,100|min_len,6',
            'name' => 'required|alpha_numeric',
            'age' => 'required|numeric|max_len,2',
            'description' => 'required|alpha_numeric',
            'image' => 'required_file|extension,png;jpg;jpeg'
        ));
        if ($is_valid === true) {
            $login = $this->clearAll($_POST['login']);
            $paswword = $this->clearAll($_POST['password']);
            $password_repeat = $this->clearAll($_POST['password_repeat']);
            $name = $this->clearAll($_POST['name']);
            $age = $this->clearAll($_POST['age']);
            $description = $this->clearAll($_POST['description']);
            if (empty($this->checkLogin($login))) {
                if ($paswword === $password_repeat) {
                    $fileUpload = $_FILES;
                    $fileUp = new File();
                    $img_url = $fileUp->uploadImg($fileUpload, $login);
                    $paswword = $this->cryptPasswd($paswword);
                    $data = ['login' => $login,
                        'password' => $paswword,
                        'name' => $name,
                        'age' => $age,
                        'description' => $description,
                        'img_url' => $img_url];
                    $this->addUserDB($data);
                    echo 'Registration succes';
                    die();
                } else {
                    echo 'Password error';
                }
            } else {
                echo 'Error login';
            }
        } else {
//            print_r($is_valid);
            echo $is_valid[0];
        }
    }


    public function addUser()
    {
        $is_valid = \GUMP::is_valid(array_merge($_POST, $_FILES), array(
            //$is_valid = \GUMP::is_valid($_POST, array(
            'login' => 'required|alpha_numeric',
            'password' => 'required|max_len,100|min_len,6',
            'name' => 'required|alpha_numeric',
            'age' => 'required|numeric|max_len,2',
            'description' => 'required|alpha_numeric',
            'image' => 'required_file|extension,png;jpg;jpeg'
        ));
        if ($is_valid === true) {
            $login = $this->clearAll($_POST['login']);
            $paswword = $this->clearAll($_POST['password']);
            $name = $this->clearAll($_POST['name']);
            $age = $this->clearAll($_POST['age']);
            $description = $this->clearAll($_POST['description']);
            if (empty($this->checkLogin($login))) {
                $fileUpload = $_FILES;
                $fileUp = new File();
                $img_url = $fileUp->uploadImg($fileUpload, $login);
                $paswword = $this->cryptPasswd($paswword);
                $data = ['login' => $login,
                    'password' => $paswword,
                    'name' => $name,
                    'age' => $age,
                    'description' => $description,
                    'img_url' => $img_url];
                $this->addUserDB($data);
                echo 'User add succes';
                die();
            } else {
                echo 'Error login';
            }
        } else {
//            print_r($is_valid);
            echo $is_valid[0];
        }
    }

    public function deleteAvatar()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $id = $routes[3];
        $userModel = new Users();
        $datas = $userModel::query()->select('photo')->where('id', '=', $id)->get();
        $data = $datas->toArray()[0];
        if ($data !== false) {
            if ($this->deleteOnlyPhoto($data['photo']) === 'delete') {
                $user = $userModel::find($id);
                $user->photo = '';
                $user->save();
                $msg = 'Аватарка удалена';
            } else {
                $msg = 'Нет доступа к фото';
            }
        } else {
            $msg = 'Такой аватарки нет';
        }
        return $msg;
    }

    private function deleteOnlyPhoto($photo)
    {
        if (file_exists('photos/' . $photo)) {
            @unlink('photos/' . $photo);
            $msg = 'delete';
        } else {
            $msg = 'no';
        }
        return $msg;
    }

    public function deleteUser()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $id = $routes[3];
        $userModel = new Users();
        $datas = $userModel::query()->select('login', 'photo')->where('id', '=', $id)->get();
        $data = $datas->toArray()[0];
        if ($data !== false) {
            if ($this->deleteOnlyPhoto($data['photo']) === 'delete') {
                $user = $userModel::find($id);
                if ($_SESSION['login'] === $data['login']) {
                    $user->delete();
                    $msg = 'Вы удалили себя!';
                } else {
                    $user->delete();
                    $msg = 'Пользователь удален';
                }
            } else {
                $msg = 'Нет доступа к фото';
            }
        } else {
            $msg = 'Такого пользователя нет';
        }
        return $msg;
    }

    public function allPhoto()
    {
        $userModel = new Users();
        $data = $userModel::query()->select('id', 'photo')->where('photo', '>', '')->get();
        return $data->toArray();
    }

    public function allUser()
    {
        $userModel = new Users();
        $data = $userModel::query()->select('login', 'name', 'age', 'description', 'photo', 'id')->get();
        return $data->toArray();
    }

    private function addUserDB($data)
    {
        $user = new Users();
        $user->login = $data['login'];
        $user->password = $data['password'];
        $user->name = $data['name'];
        $user->age = $data['age'];
        $user->description = $data['description'];
        $user->photo = $data['img_url'];
        $user->save();
    }

    private function updateUser($data, $id)
    {
        $userModel = new Users();
        if (array_key_exists('img_url', $data)) {
            if (array_key_exists('password', $data)) {
                //echo "Массив содержит 'password' & 'img_url' .";
                $user = $userModel::find($id);
                $user->login = $data['login'];
                $user->password = $data['password'];
                $user->name = $data['name'];
                $user->age = $data['age'];
                $user->description = $data['description'];
                $user->photo = $data['img_url'];
                $user->save();
            } else {
                //echo "Массив не содержит 'password' но содержит 'img_url' .";
                $user = $userModel::find($id);
                $user->login = $data['login'];
                $user->name = $data['name'];
                $user->age = $data['age'];
                $user->description = $data['description'];
                $user->photo = $data['img_url'];
                $user->save();
            }
        } else {
            if (array_key_exists('password', $data)) {
                //echo "Массив содержит 'password' и не содержит 'img_url' .";
                $user = $userModel::find($id);
                $user->login = $data['login'];
                $user->password = $data['password'];
                $user->name = $data['name'];
                $user->age = $data['age'];
                $user->description = $data['description'];
                $user->save();
            } else {
                //echo "Массив не содержит 'password' и не содержит 'img_url' .";
                $user = $userModel::find($id);
                $user->login = $data['login'];
                $user->name = $data['name'];
                $user->age = $data['age'];
                $user->description = $data['description'];
                $user->save();
            }
        }
        echo 'data update';
    }

    private function autentificationUser($password, $login)
    {
        $userModel = new Users();
        $data = $userModel::query()
            ->select('login')
            ->where('login', '=', $login)
            ->where('password', '=', $password)
            ->get();
        return $data->toArray()[0];
    }

    private function cryptPasswd($data)
    {
        $passuser = crypt($data, '$6$rounds=5458$yopta23GDs43yopta$');
        return $passuser;
    }
}
