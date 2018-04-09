<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=$url?>">Project name</a>

        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="home"><a href="../../">Авторизация</a></li>
                <li class="registr"><a href="<?=$url?>/registration/">Регистрация</a></li>
                <li class="userlist"><a href="<?=$url?>/userlist/">Список пользователей</a></li>
                <li class="filelist"><a href="<?=$url?>/filelist/">Список файлов</a></li>
            </ul>
        </div><!--/.nav-collapse -->
        <div class="user_info">
            <?php
            if (!empty($user)){
                echo '<span>' . $user['name'] . '</span>';
                echo '<img src="';
                if (!empty($user['photo'])) {
                    echo $url . '/photos/' . $user['photo'];
                } else {
                    echo $url . '/images/notphoto.jpg';
                }
                echo '" width="25" height="25"/><a href="'. $url . '/logout">Выйти</a>';
            }
            ?>
        </div>
    </div>
</nav>