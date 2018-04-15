$('.btn-default').on('click', function (e) {
    e.preventDefault();
    var formData = new FormData($('.form-horizontal')[0]);
    // if ($('input[type=file]')[0]) {
    //     formData.append('file', $('input[type=file]')[0].files[0]);
    // }
    $.ajax({
        url: '',
        type: 'POST',
        data: formData, // Данные которые мы передаем
        cache: false, // В запросах POST отключено по умолчанию, но перестрахуемся
        contentType: false, // Тип кодирования данных мы задали в форме, это отключим
        processData: false
        // data: $('.form-horizontal').serialize()
        //dataType:'json'
    }).done(function (resultat) {
        $('#outmessage').html(resultat)
        //console.log(resultat);
        // switch (resultat) {
        //     case 'not empty':
        //         $('#outmessage').html('Не все поля заполнены!');
        //         break;
        //     case 'error img':
        //         $('#outmessage').html('Не верный формат картинки!');
        //         break;
        //     case 'error login':
        //         $('#outmessage').html('Пользователь с таким логином уже есть!');
        //         break;
        //     case 'data update':
        //         $('#outmessage').html('Данные обновлены!');
        //         break;
        //     case 'user add':
        //         $('#outmessage').html('Пользователь добавлен!');
        //         break;
        //     case 'wrong delete file':
        //         $('#outmessage').html('Ошыбка при удалении файла!');
        //         break;
        //     case 'not img':
        //         $('#outmessage').html('Картинка не выбрана!');
        //         break;
        //     case 'password error':
        //         $('#outmessage').html('Пароли не совпадают!');
        //         break;
        //     case 'registration':
        //         $('#outmessage').html('Регистрация успешна!');
        //         break;
        //     case 'autorisation':
        //         $('#outmessage').html('Авторизация успешна!');
        //         break;
        //     case 'message':
        //         $('#outmessage').html('Сообщение отправлено!');
        //         break;
        //     case 'No user':
        //         $('#outmessage').html('Нет такого пользователя!');
        //         break;
        //     case 'logged':
        //         $('#outmessage').html('Авторизация успешна!');
        //         break;
        //     default:
        //         break;
        // }
    })
});

$('.list_desc').on('click', function (e) {
    e.preventDefault();

    $.ajax({
        url: 'sort',
        type: 'GET',
        data: 'desc',
        dataType: 'json'
    }).done(function (resultat) {
        var content = '<tr>';
        resultat.forEach(function (element) {
            content += '<td>' + element.login + '</td>';
            content += '<td>' + element.name + '</td>';
            content += '<td>' + element.age + '</td>';
            if (element.age > 18){
                content += '<td>Совершеннолетний</td>';
            }else{
                content += '<td>Несовершеннолетний</td>';
            }
            content += '<td>' + element.description + '</td>';
            if (element.photo){
                content += '<td><img src="/photos/' + element.photo + '"/></td>';
            }else{
                content += '<td><img src="/images/notphoto.jpg"/></td>';
            }
            content += '<td><a class="delete" href="edit/' + element.id +'"><img src="/images/edit.jpg"/></a></td>';
            content += '<td><a class="delete" href="delete/' + element.id +'"><img src="/images/del.jpg"/></a></td></tr>';
        });
        $('#out').html(content);
    })
});

$('.list_asc').on('click', function (e) {
    e.preventDefault();
    $.ajax({
        url: 'sort',
        type: 'GET',
        data: 'asc',
        dataType: 'json'
    }).done(function (resultat) {

        // console.log(resultat);
        var content = '<tr>';
        resultat.forEach(function (element) {
            content += '<td>' + element.login + '</td>';
            content += '<td>' + element.name + '</td>';
            content += '<td>' + element.age + '</td>';
            if (element.age > 18){
                content += '<td>Совершеннолетний</td>';
            }else{
                content += '<td>Несовершеннолетний</td>';
            }
            content += '<td>' + element.description + '</td>';
            if (element.photo){
                content += '<td><img src="/photos/' + element.photo + '"/></td>';
            }else{
                content += '<td><img src="/images/notphoto.jpg"/></td>';
            }
            content += '<td><a class="delete" href="edit/' + element.id +'"><img src="/images/edit.jpg"/></a></td>';
            content += '<td><a class="delete" href="delete/' + element.id +'"><img src="/images/del.jpg"/></a></td></tr>';
        });
        $('#out').html(content);
    })
});
