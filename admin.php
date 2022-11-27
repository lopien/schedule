<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1.0", initial-scale="1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Админ-панель</title>
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
    if (isset($_POST["День_недели"])) {
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red_id'])) {
          $sql = mysqli_query($link, "UPDATE `schedule` SET 
          `День недели` = '{$_POST['День_недели']}',
          `Номер урока` = '{$_POST['Номер_урока']}',
          `Класс` = '{$_POST['Класс']}',
          `Урок` = '{$_POST['Урок']}',
          `Аудитория` = '{$_POST['Аудитория']}'
          
          WHERE `Код расписания`={$_GET['red_id']}");
      } else {
          //Иначе вставляем данные, подставляя их в запрос
          $sql = mysqli_query($link, "INSERT INTO `schedule` (`День недели`, `Номер урока`, `Класс`, `Урок`, `Аудитория`) VALUES (
              '{$_POST['День_недели']}', 
              '{$_POST['Номер_урока']}',
              '{$_POST['Класс']}',
              '{$_POST['Урок']}',
              '{$_POST['Аудитория']}')");
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
      $sql = mysqli_query($link, "DELETE FROM `schedule` WHERE `Код расписания` = {$_GET['del_id']}");
      if ($sql) {
        echo "<p>Удалено</p>";
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
    //Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red_id'])) {
      $sql = mysqli_query($link, "SELECT `День недели`, `Номер урока`, `Класс`, `Урок`, `Аудитория` FROM `schedule` WHERE `Код расписания`={$_GET['red_id']}");
      $product = mysqli_fetch_array($sql);
    }
  ?>
  <iframe name="iframe1" style="position: absolute; left: -9999px;"></iframe>
  <form action="" method="post" target="iframe1">
    <table>
      <tr>
        <td>День недели:</td>
        <td>
            <select name="День недели">
            <?php 
                $lessons = mysqli_query($link, 'SELECT * FROM `week`');
                while($lesson = mysqli_fetch_array($lessons)){ ?>
                    <option value="<?= isset($_GET['red_id']) ? $lesson['День недели'] : $lesson['День недели']; ?>" ><?php echo $lesson['День недели'] ?></option>
                <?php
                }
            ?>
            </select>
        </td>
      </tr>
      <tr>
        <td>Класс:</td>
        <td>
            <select name="Класс">
            <?php 
                $groups = mysqli_query($link, 'SELECT * FROM `group_name` ORDER BY `Код класса` DESC');
                while($group = mysqli_fetch_array($groups)){ ?>
                    <option value="<?= isset($_GET['red_id']) ? $group['Класс'] : $group['Класс']; ?>" ><?php echo $group['Класс'] ?></option>
                <?php
                }
            ?>
            </select>
        </td>
      </tr>
      <tr>
        <td>Номер урока:</td>
        <td>
            <select name="Номер_урока">
            <?php 
                $lessons = mysqli_query($link, 'SELECT * FROM `lesson_number`');
                while($lesson = mysqli_fetch_array($lessons)){ ?>
                    <option value="<?= isset($_GET['red_id']) ? $lesson['Номер урока'] : $lesson['Номер урока']; ?>" ><?php echo $lesson['Номер урока'] ?></option>
                <?php
                }
            ?>
            </select>
        </td>
      </tr>
      <tr>
        <td>Урок:</td>
        <td>
            <select name="Урок">
            <?php 
                $groups = mysqli_query($link, 'SELECT * FROM `discipline`');
                while($group = mysqli_fetch_array($groups)){ ?>
                    <option value="<?= isset($_GET['red_id']) ? $group['Урок'] : $group['Урок']; ?>" ><?php echo $group['Урок'] ?></option>
                <?php
                }
            ?>
            </select>
        </td>
      </tr>
      <tr>
        <td>Аудитория:</td>
        <td>
            <select name="Аудитория">
            <?php 
                $groups = mysqli_query($link, 'SELECT * FROM `auditory`');
                while($group = mysqli_fetch_array($groups)){ ?>
                    <option value="<?= isset($_GET['red_id']) ? $group['Аудитория'] : $group['Аудитория']; ?>" ><?php echo $group['Аудитория'] ?></option>
                <?php
                }
            ?>
            </select>
        </td>
      </tr>
      <tr>
        <td colspan="4"><input class="btn" type="submit" value="OK"></td>
      </tr>
    </table>
  </form>
  <p><a href="?add=new">Добавить новое расписание</a> </br> <a href="/add_auditory.php">Добавить новую аудиторию</a></br> <a href="/add_group.php">Добавить новую группу</a></p>
  <a href="/">Вернуться на главную</a>
  </br></br>
  <table border='1'>
    <tr>
        <td>Код расписания</td>
        <td>День недели</td>
        <td>Номер урока</td>
        <td>Класс</td>
        <td>Урок</td>
        <td>Аудитория</td>
    </tr>
    <?php
      $sql = mysqli_query($link, 'SELECT `Код расписания`, `День недели`, `Номер урока`, `Класс`, `Урок`, `Аудитория` FROM `schedule` ORDER BY `Код расписания` DESC');
      while ($result = mysqli_fetch_array($sql)) {
        echo '<tr>' .
             "<td>{$result['Код расписания']}</td>" .
             "<td>{$result['День недели']}</td>" .
             "<td>{$result['Номер урока']} </td>" .
             "<td>{$result['Класс']} </td>" .
             "<td>{$result['Урок']} </td>" .
             "<td>{$result['Аудитория']} </td>" .
             "<td><a href='?del_id={$result['Код расписания']}'>Удалить</a></td>" .
             "<td><a href='?red_id={$result['Код расписания']}'>Изменить</a></td>" .
             '</tr>';
      }
    ?>
  </table>
</body>
</html>