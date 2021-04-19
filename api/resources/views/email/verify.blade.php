
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Здравствуйте {{ $name }},
    <br>
    Спасибо за создание учетной записи у нас. Не забудьте завершить регистрацию!
    <br>
    Нажмите ссылку ниже, чтобы подтвердить Вашу регистрацию:
    <br>

    <a href="{{ url(env('FRONTPAGE_VERIFY_REGISTRY') . $verification_code)}}">Подтвердить регистрацию</a>

    <br/>
</div>

</body>
</html>
