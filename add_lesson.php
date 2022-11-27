<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1.0", initial-scale="1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Добавление уроков</title>
</head>
<body>
  <?php
    $host = 'localhost';  // Хост, у нас все локально
    $user = 'j95017jb_db';    // Имя созданного вами пользователя
    $pass = 'S&w1m&3C'; // Установленный вами пароль пользователю
    $db_name = 'j95017jb_db';   // Имя базы данных
    $link = mysqli_connect($host, $user, $pass, $db_name); // Соединяемся с базой
    // Ругаемся, если соединение установить не удалось
    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
    if (isset($_POST["Урок"])) {
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red_id'])) {
          $sql = mysqli_query($link, "UPDATE `discipline` SET 
          `Урок` = '{$_POST['Урок']}',

           WHERE `Код урока`={$_GET['red_id']}");
      } else {
          //Иначе вставляем данные, подставляя их в запрос
          $sql = mysqli_query($link, "INSERT INTO `discipline` (`Урок`) VALUES ('{$_POST['Урок']}' )");
      }
      //Если вставка прошла успешно
      if ($sql) {
        echo '<p>Успешно!</p>';
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
    if (isset($_GET['del_id'])) { 
      $sql = mysqli_query($link, "DELETE FROM `discipline` WHERE `Код урока` = {$_GET['del_id']}");
      if ($sql) {
        echo "<p>удалено</p>";
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
    //Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red_id'])) {
      $sql = mysqli_query($link, "SELECT `Урок` FROM `discipline` WHERE `Код урока`={$_GET['red_id']}");
      $product = mysqli_fetch_array($sql);
    }
  ?>
  <form action="" method="post">
    <table>
      <tr>
        <td>Урок:</td>
        <td>
            <input type="text" name="Урок" value="<?= isset($_GET['red_id']) ? $product['Урок'] : ''; ?>">
        </td>
      </tr>
      <tr>
        <td colspan="4"><input class="btn" type="submit" value="OK"></td>
      </tr>
    </table>
  </form>
  <p><a href="?add=new">Добавить новый урок</a> 
  </br></br>
  <a href="/admin.php">Назад</a>
  </br></br>
  <table border='1'>
    <tr>
        <td>Код урока</td>
        <td>Урок</td>
    </tr>
    <?php
      $sql = mysqli_query($link, 'SELECT `Код урока`,`Урок` FROM `discipline`');
      while ($result = mysqli_fetch_array($sql)) {
        echo '<tr>' .
             "<td>{$result['Код урока']}</td>" .
             "<td>{$result['Урок']}</td>" .
             "<td><a href='?del_id={$result['Код урока']}'>Удалить</a></td>" .
             "<td><a href='?red_id={$result['Код урока']}'>Изменить</a></td>" .
             '</tr>';
      }
    ?>
  </table>
</body>
</html>