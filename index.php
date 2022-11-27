<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1.0" initial-scale="1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Расписание</title>
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
  ?>
  <?php
  /*
  $group1 = Промышленая экология;
  $group2 = Цифровая трансформация систем безопасности;
  $group3 = Эколого-экономическое и архитектурно-дизайнерское обоснование проектов природообустройства;
  */
  ?>
    <style>
        hr {
            border: none; 
            background-color: red; 
            color: red; 
            height: 2px; 
       }
        .backgraund{
            background-color: #FFFFCC;
        } 
        .backgraund2{
            background-color: #FFD876;
        }
        .bckg{
            background-color: rgba(0, 0, 0, 0.13);
        }
    </style>
    <table border='1'>
    <tr>
    <td class='backgraund'>День недели</td>
    <td class='backgraund'>Время</td>
    <td class='backgraund'>Номер урока</td>
    <?php
        $sql = mysqli_query($link, 'SELECT `Класс` FROM `group_name` ORDER BY `Код класса`');
        while ($result = mysqli_fetch_array($sql)) {
          echo "<td class='backgraund'> <strong>{$result['Класс']} </strong></td>";
        }
      ?>
    </tr>
    <?php
        $sql = mysqli_query($link, 'SELECT COUNT(`Код класса`) FROM `group_name`');
        while ($result = mysqli_fetch_array($sql)) {
          $count = $result[COUNT(`Код класса`)];
        }
        
    ?>
    <?php
    for ($pair = 1; $pair <= 8; $pair++) {
        echo '<tr>';
        echo "<td class='backgraund'>Понедельник</td>";
        $time_on = $pdo->prepare('SELECT `Время начала`, `Время окончания` FROM `lesson_number` WHERE `Номер урока`= ?');
        $time_on->execute([$pair]);
        while ($timer = $time_on->fetch()){
            echo "<td class='backgraund'>{$timer['Время начала']} </br> {$timer['Время окончания']}</td>";
        }
        $num_pare = $pdo->prepare('SELECT `Номер урока` FROM `lesson_number` WHERE `Номер урока`= ?');
        $num_pare->execute([$pair]);
        while ($pair_num = $num_pare->fetch()){
            echo "<td class='backgraund'>{$pair_num['Номер урока']} </td>";
        }
        for ($group = 1; $group <= $count; $group++) {
            echo '<td>';
            $stmt = $pdo->prepare('SELECT `Урок`, `Аудитория` 
                                    FROM `schedule`, `group_name` 
                                    WHERE 
                                    (`schedule`.`Номер урока` = ?) 
                                    AND (`group_name`.`Код класса` = ?) 
                                    AND (`schedule`.`Класс` = `group_name`.`Класс`) 
                                    AND (`schedule`.`День недели` = "Понедельник")');
            $stmt->execute([$pair,$group]);
            while ($row = $stmt->fetch()){
                if(isset($row['Урок'])) {
                    echo " {$row['Урок']} <strong> </br> {$row['Аудитория']} </strong> ";
                } else {
                    echo "...";
                }
            }
            echo '</td>';
        }
        echo '<tr>';
    }
    ?>
    <?php
    for ($pair = 1; $pair <= 8; $pair++) {
        echo '<tr>';
        echo "<td class='backgraund2'>Вторник</td>";
        $time_on = $pdo->prepare('SELECT `Время начала`, `Время окончания` FROM `lesson_number` WHERE `Номер урока`= ?');
        $time_on->execute([$pair]);
        while ($timer = $time_on->fetch()){
            echo "<td class='backgraund2'>{$timer['Время начала']} </br> {$timer['Время окончания']}</td>";
        }
        $num_pare = $pdo->prepare('SELECT `Номер урока` FROM `lesson_number` WHERE `Номер урока`= ?');
        $num_pare->execute([$pair]);
        while ($pair_num = $num_pare->fetch()){
            echo "<td class='backgraund2'>{$pair_num['Номер урока']} </td>";
        }
        for ($group = 1; $group <= $count; $group++) {
            echo '<td class="bckg">';
            $stmt = $pdo->prepare('SELECT  `Урок`, `Аудитория` 
                                    FROM `schedule`, `group_name` 
                                    WHERE 
                                    (`schedule`.`Номер урока` = ?) 
                                    AND (`group_name`.`Код класса` = ?) 
                                    AND (`schedule`.`Класс` = `group_name`.`Класс`) 
                                    AND (`schedule`.`День недели` = "Вторник")');
            $stmt->execute([$pair,$group]);
            while ($row = $stmt->fetch()){
                if(isset($row['Урок'])) {
                    echo " {$row['Урок']}  </br> <strong> {$row['Аудитория']}</strong> ";
                } else {
                    echo "...";
                }
            }
            echo '</td>';
        }
        echo '<tr>';
    }
    ?>
    <?php
    for ($pair = 1; $pair <= 8; $pair++) {
        echo '<tr>';
        echo "<td class='backgraund'>Среда</td>";
        $time_on = $pdo->prepare('SELECT `Время начала`, `Время окончания` FROM `lesson_number` WHERE `Номер урока`= ?');
        $time_on->execute([$pair]);
        while ($timer = $time_on->fetch()){
            echo "<td class='backgraund'>{$timer['Время начала']} </br> {$timer['Время окончания']}</td>";
        }
        $num_pare = $pdo->prepare('SELECT `Номер урока` FROM `lesson_number` WHERE `Номер урока`= ?');
        $num_pare->execute([$pair]);
        while ($pair_num = $num_pare->fetch()){
            echo "<td class='backgraund'>{$pair_num['Номер урока']} </td>";
        }
        for ($group = 1; $group <= $count; $group++) {
            echo '<td>';
            $stmt = $pdo->prepare('SELECT  `Урок`,  `Аудитория` 
                                    FROM `schedule`, `group_name` 
                                    WHERE 
                                    (`schedule`.`Номер урока` = ?) 
                                    AND (`group_name`.`Код класса` = ?) 
                                    AND (`schedule`.`Класс` = `group_name`.`Класс`) 
                                    AND (`schedule`.`День недели` = "Среда")');
            $stmt->execute([$pair,$group]);
            while ($row = $stmt->fetch()){
                if(isset($row['Урок'])) {
                    echo "{$row['Урок']}  </br> <strong> {$row['Аудитория']}</strong> ";
                } else {
                    echo "...";
                }
            }
            echo '</td>';
        }
        echo '<tr>';
    }
    ?>
    <?php
    for ($pair = 1; $pair <= 8; $pair++) {
        echo '<tr>';
        echo "<td class='backgraund2'>Четверг</td>";
        $time_on = $pdo->prepare('SELECT `Время начала`, `Время окончания` FROM `lesson_number` WHERE `Номер урока`= ?');
        $time_on->execute([$pair]);
        while ($timer = $time_on->fetch()){
            echo "<td class='backgraund2'>{$timer['Время начала']} </br> {$timer['Время окончания']}</td>";
        }
        $num_pare = $pdo->prepare('SELECT `Номер урока` FROM `lesson_number` WHERE `Номер урока`= ?');
        $num_pare->execute([$pair]);
        while ($pair_num = $num_pare->fetch()){
            echo "<td class='backgraund2'>{$pair_num['Номер урока']} </td>";
        }
        for ($group = 1; $group <= $count; $group++) {
            echo '<td class="bckg">';
            $stmt = $pdo->prepare('SELECT  `Урок`, `Аудитория` 
                                    FROM `schedule`, `group_name` 
                                    WHERE 
                                    (`schedule`.`Номер урока` = ?) 
                                    AND (`group_name`.`Код класса` = ?) 
                                    AND (`schedule`.`Класс` = `group_name`.`Класс`) 
                                    AND (`schedule`.`День недели` = "Четверг")');
            $stmt->execute([$pair,$group]);
            while ($row = $stmt->fetch()){
                if(isset($row['Урок'])) {
                    echo "{$row['Урок']}  </br><strong>  {$row['Аудитория']}</strong> ";
                } else {
                    echo "...";
                }
            }
            echo '</td>';
        }
        echo '<tr>';
    }
    ?>
    <?php
    for ($pair = 1; $pair <= 8; $pair++) {
        echo '<tr>';
        echo "<td class='backgraund'>Пятница</td>";
        $time_on = $pdo->prepare('SELECT `Время начала`, `Время окончания` FROM `lesson_number` WHERE `Номер урока`= ?');
        $time_on->execute([$pair]);
        while ($timer = $time_on->fetch()){
            echo "<td class='backgraund'>{$timer['Время начала']} </br> {$timer['Время окончания']}</td>";
        }
        $num_pare = $pdo->prepare('SELECT `Номер урока` FROM `lesson_number` WHERE `Номер урока`= ?');
        $num_pare->execute([$pair]);
        while ($pair_num = $num_pare->fetch()){
            echo "<td class='backgraund'>{$pair_num['Номер урока']} </td>";
        }
        for ($group = 1; $group <= $count; $group++) {
            echo '<td>';
            $stmt = $pdo->prepare('SELECT  `Урок`, `Аудитория` 
                                    FROM `schedule`, `group_name` 
                                    WHERE 
                                    (`schedule`.`Номер урока` = ?) 
                                    AND (`group_name`.`Код класса` = ?) 
                                    AND (`schedule`.`Класс` = `group_name`.`Класс`) 
                                    AND (`schedule`.`День недели` = "Пятница")');
            $stmt->execute([$pair,$group]);
            while ($row = $stmt->fetch()){
                if(isset($row['Урок'])) {
                    echo "{$row['Урок']}  </br> <strong> {$row['Аудитория']}</strong>";
                } else {
                    echo "...";
                }
            }
            echo '</td>';
        }
        echo '<tr>';
    }
    ?>
     <?php
    for ($pair = 1; $pair <= 5; $pair++) {
        echo '<tr>';
        echo "<td class='backgraund2'>Суббота</td>";
        $time_on = $pdo->prepare('SELECT `Время начала(сб)`, `Время окончания(сб)` FROM `lesson_number` WHERE `Номер урока`= ?');
        $time_on->execute([$pair]);
        while ($timer = $time_on->fetch()){
            echo "<td class='backgraund2'>{$timer['Время начала(сб)']} </br> {$timer['Время окончания(сб)']}</td>";
        }
        $num_pare = $pdo->prepare('SELECT `Номер урока` FROM `lesson_number` WHERE `Номер урока`= ?');
        $num_pare->execute([$pair]);
        while ($pair_num = $num_pare->fetch()){
            echo "<td class='backgraund2'>{$pair_num['Номер урока']} </td>";
        }
        for ($group = 1; $group <= $count; $group++) {
            echo '<td class="bckg">';
            $stmt = $pdo->prepare('SELECT  `Урок`, `Аудитория` 
                                    FROM `schedule`, `group_name` 
                                    WHERE 
                                    (`schedule`.`Номер урока` = ?) 
                                    AND (`group_name`.`Код класса` = ?) 
                                    AND (`schedule`.`Класс` = `group_name`.`Класс`) 
                                    AND (`schedule`.`День недели` = "Суббота")');
            $stmt->execute([$pair,$group]);
            while ($row = $stmt->fetch()){
                if(isset($row['Урок'])) {
                    echo "{$row['Урок']}  </br> <strong> {$row['Аудитория']}</strong>";
                } else {
                    echo "...";
                }
            }
            echo '</td>';
        }
        echo '<tr>';
    }
    ?>
  </table>
</body>
</html>


