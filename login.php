<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') 
{
    if (isset($_GET['exit']))
    {
        session_destroy();
        foreach ($_COOKIE as $item => $value)
            setcookie($item, '', 1);
        header('Location: ./login.php');
    }

    if (!empty($_SESSION['login'])) 
    {
        print ('<div>Вы авторизованы как '. $_SESSION['login'] . ', id ' . $_SESSION['id'] . '</div>')
        ?>
        <a href="./login.php?exit=1">Выход</a>
        <a href="./">Главная страница</a>
        <?php
        exit();
    } else {
    ?>

    <form action="./login.php" method="POST">
        <input name="login" placeholder = "Введите ваш логин" required>
        <input name="password" placeholder = "Введите ваш пароль" required>
        <input type="submit" value="Войти">
    </form>
        <a href="./">Главная страница</a>
    <?php
    }
}
else 
{
    $user = 'u54029';
    $pass = '5413631';
    $connection = new PDO('mysql:host=localhost;dbname=u53012', $user, $pass, [PDO::ATTR_PERSISTENT => true]);
    $st = $connection->prepare("SELECT * FROM form1 WHERE id_login = :id_login && id_password = :id_password;");
    $sterror = $st->execute(['id_login' => $_POST['login'], 'id_password' => hash("md5",$_POST['password'])]);
    $result = $st->fetch(PDO::FETCH_ASSOC);
    if (!$result) 
    {
        print ("Пользователя с таким логином и паролем не существует");
        print ('<p><a href="./login.php">Вернуться</a></p>');
        exit();
    }

    $_SESSION['login'] = $_POST['login'];
    $_SESSION['id'] = $result['id'];
    $powers = $_POST['powers'];

    setcookie('name_value', $result['name'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('email_value', $result['email'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('dob_value', $result['birthday'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('radio-1_value', $result['gender'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('radio-2_value', $result['limbs'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('life_value', $result['bio'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('choice_value', $result['contract'], time() + 12 * 30 * 24 * 60 * 60);
    setcookie('powers_value', json_encode($powers), time() + 12 * 30 * 24 * 60 * 60);

    /*$st = $connection->prepare("SELECT * FROM form_power WHERE form_id = :form_id;");
    $sterror = $st->execute(['form_id' => $_SESSION['id']]);
    $result = $st->fetchAll(PDO::FETCH_ASSOC);

    $st = $connection->prepare("SELECT * FROM powers1;");
    $sterror =  $st -> execute();
    $abilities = $st->fetchAll();

    foreach ($abilities as $ability) {
        setcookie($ability['ability'].'_value', '', 100000);
    }

    if ($result) 
    {
        foreach ($result as $item)
        {
            foreach ($abilities as $ability) 
            {
                if ($ability['power_id'] == $item['power_id'])
                {
                    setcookie($ability['ability'].'_value', '1', time() + 12 * 30 * 24 * 60 * 60);
                    break;
                }
            }
        }
    }*/
    
    header('Location: ./login.php');
}