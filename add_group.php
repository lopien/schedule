<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1.0", initial-scale="1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Добавление классов</title>
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
    //Если переменная Name передана
    if (isset($_POST["Класс"])) {
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red_id'])) {
          $sql = mysqli_query($link, "UPDATE `group_name` SET 
          `Класс` = '{$_POST['Класс']}',
          `Количество учеников` = '{$_POST['Количество_учеников']}'
          WHERE `Код класса`={$_GET['red_id']}");
      } else {
          //Иначе вставляем данные, подставляя их в запрос
          $sql = mysqli_query($link, "INSERT INTO `group_name` (`Класс`, `Количество учеников`) VALUES 
               ('{$_POST['Класс']}', '{$_POST['Количество_учеников']}')");
      }
      //Если вставка прошла успешно
      if ($sql) {
        echo '<p>Успешно!</p>';
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
    if (isset($_GET['del_id'])) { //проверяем, есть ли переменная
      //удаляем строку из таблицы
      $sql = mysqli_query($link, "DELETE FROM `group_name` WHERE `Код класса` = {$_GET['del_id']}");
      if ($sql) {
        echo "<pУспешно удалено.</p>";
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
    //Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red_id'])) {
      $sql = mysqli_query($link, "SELECT `Класс`, `Количество учеников` FROM `group_name` WHERE `Код класса`={$_GET['red_id']}");
      $product = mysqli_fetch_array($sql);
    }
  ?>
  <form action="" method="post">
    <table>
      <tr>
        <td>Класс:</td>
        <td><input type="text" name="Класс" value="<?= isset($_GET['red_id']) ? $product['Класс'] : ''; ?>"></td>
      </tr>
      <tr>
        <td>Количество учеников:</td>
        <td><input type="text" name="Количество учеников" value="<?= isset($_GET['red_id']) ? $product['Количество учеников'] : ''; ?>"></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" value="OK"></td>
      </tr>
    </table>
  </form>
  <p><a href="?add=new">Добавить новую группу</a></p>
  <table border='1'>
    <tr>
        <td>Код класса</td>
        <td>Класс</td>
        <td>Количество учеников</td>
    </tr>
    <?php
      $sql = mysqli_query($link, 'SELECT `Код класса`, `Класс`, `Количество учеников` FROM `group_name`');
      while ($result = mysqli_fetch_array($sql)) {
        echo '<tr>' .
             "<td>{$result['Код класса']}</td>" .
             "<td>{$result['Класс']}</td>" .
             "<td>{$result['Количество учеников']} </td>" .
             "<td><a href='?del_id={$result['Код класса']}'>Удалить</a></td>" .
             "<td><a href='?red_id={$result['Код класса']}'>Изменить</a></td>" .
             '</tr>';
      }
    ?>
  </table>
</body>
</html>