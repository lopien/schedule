<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1.0", initial-scale="1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Распределение людей</title>
</head>
<body>
  <?php
    $host = 'localhost';  // Хост, у нас все локально
    $user = 'j95017jb_db';    // Имя созданного вами пользователя
    $pass = 'S&w1m&3C'; // Установленный вами пароль пользователю
    $db_name = 'j95017jb_db';   // Имя базы данных
    $link = mysqli_connect($host, $user, $pass, $db_name); // Соединяемся с базой
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $opt);
    // Ругаемся, если соединение установить не удалось
    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
    if (isset($_POST["Минуты"])) {
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red_id'])) {
          $sql = mysqli_query($link, "UPDATE `time` SET 
          `День недели` = '{$_POST['День_недели']}',
          `Часы` = '{$_POST['Часы']}',
          `Минуты` = '{$_POST['Минуты']}'

           WHERE `Id`={$_GET['red_id']}");
      } else {
          //Иначе вставляем данные, подставляя их в запрос
          $sql = mysqli_query($link, "INSERT INTO `time` (`День недели`,`Часы`,`Минуты`) VALUES ('{$_POST['День_недели']}', '{$_POST['Часы']}', '{$_POST['Минуты']}' )");
      }

      //Если вставка прошла успешно
      if ($sql) {
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
    if (isset($_GET['del_id'])) { 
      $sql = mysqli_query($link, "DELETE FROM `time` WHERE `Id` = {$_GET['del_id']}");
      if ($sql) {
        echo "<p>удалено</p>";
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
    //Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red_id'])) {
      $sql = mysqli_query($link, "SELECT `День недели`, `Часы`, `Минуты` FROM `time` WHERE `Id`={$_GET['red_id']}");
      $product = mysqli_fetch_array($sql);
    }
  ?>
  <form action="" method="post">
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
        <td>Часы:</td>
        <td>
            <input type="number" required  min='0' max='23' name="Часы"  value="<?= isset($_GET['red_id']) ? $product['Часы'] : ''; ?>">
        </td>
      </tr>
      <tr>
        <td>Минуты:</td>
        <td>
            <input type="number" required min='0' max='59' name="Минуты" value="<?= isset($_GET['red_id']) ? $product['Минуты'] : ''; ?>">
        </td>
      </tr>
      <tr>
        <td colspan="4"><input class="btn" type="submit" value="OK"></td>
      </tr>
    </table>
  </form>
  <p><a href="?add=new">Добавить новое время</a> 
  </br></br>
  <a href="/admin.php">Назад</a>
  </br></br>
  <table border='1'>
    <?php
    $sql = mysqli_query($link, 'SELECT `День недели`, `Часы`, `Минуты` FROM `time` WHERE Id=(SELECT MAX(Id) FROM time);');
      while ($result = mysqli_fetch_array($sql)) {
        $day = $result['День недели'];
        $time = $result['Часы'] . $result['Минуты'];
        $time = (int)$time;
        //echo $time;
        //echo gettype($time);
    }
    if($time >= 830 & $time <= 910) {
        $number = 1;
    } elseif($time >= 920 & $time <= 1000) {
        $number = 2;
    } elseif($time >= 1015 & $time <= 1055) {
        $number = 3;
    } elseif($time >= 1110 & $time <= 1150) {
        $number = 4;
    } elseif($time >= 1205 & $time <= 1245) {
        $number = 5;
    } elseif($time >= 1305 & $time <= 1345) {
        $number = 6;
    } elseif($time >= 1355 & $time <= 1435) {
        $number = 7;
    } elseif($time >= 1450 & $time <= 1530) {
        $number = 8;
    } else{
        $number = 9;
    }
    echo '<tr>
        <td>Аудитория</td>
        <td>Количество учеников</td>
    </tr>';
    $stmt = $pdo->prepare('SELECT  
	         `schedule`.`Аудитория`, 
             `group_name`.`Количество учеников` 
            FROM 
             schedule, 
             group_name 
            WHERE 
             (schedule.`День недели` = ?)  
             AND (schedule.`Номер урока` = ?) 
             AND (schedule.Класс = group_name.Класс)');
        $stmt->execute([$day,$number]);
        while ($row = $stmt->fetch())
        {
            echo '<tr>' .
                     "<td>{$row[`schedule`.'Аудитория']}</td>" .
                     "<td>{$row[`group_name`.'Количество учеников']}</td>" .
                '</tr>';
        }
    echo '<tr><td>Людей в здании</td>';
    $stmt = $pdo->prepare('SELECT  
             SUM(`group_name`.`Количество учеников`)
            FROM 
             schedule, 
             group_name 
            WHERE 
             (schedule.`День недели` = ?)  
             AND (schedule.`Номер урока` = ?) 
             AND (schedule.Класс = group_name.Класс)');
        $stmt->execute([$day,$number]);
        while ($row = $stmt->fetch())
        {
            echo 
                 "<td>{$row['SUM(`group_name`.`Количество учеников`)']}</td>";
        }
    echo '</tr>';
    ?>
  </table>
  </br></br></br>
  <table border='1'>
    <tr>
        <td>Id</td>
        <td>День недели</td>
        <td>Часы</td>
        <td>Минуты</td>
    </tr>
    <?php
      $sql = mysqli_query($link, 'SELECT `Id`, `День недели`,`Часы`, `Минуты` FROM `time`');
      while ($result = mysqli_fetch_array($sql)) {
        echo '<tr>' .
             "<td>{$result['Id']}</td>" .
             "<td>{$result['День недели']}</td>" .
             "<td>{$result['Часы']}</td>" .
             "<td>{$result['Минуты']}</td>" .
             "<td><a href='?del_id={$result['Id']}'>Удалить</a></td>" .
             "<td><a href='?red_id={$result['Id']}'>Изменить</a></td>" .
             '</tr>';
      }
    ?>
  </table>
</body>
</html>