<?php
    require_once 'include/database.php';
    require_once 'include/functions.php';
?>

<!DOCTYPE html>

<head>
    <title>Статистика</title>
    <meta charset="utf-8">

    <link rel="stylesheet" href="css/global.css" type="text/css">
    <link rel="stylesheet" href="css/nav-bar.css" type="text/css">
    <script src="js/newJsFile.js"></script>
</head>

<body>
    <script src="js/nav-bar.js"></script>
    <script src="js/highlight-links.js"></script> 

    <main>
        <h4 class="title nameOfTable">Общая статистика</h4>
        <table id="mainStatistics"  style="margin-bottom: 150px">
            <tr>
                <td>Место</td>
                <td>Участник</td>
                <td>Очки</td>
                <td>3</td>
                <td>2</td>
                <td>1</td>
                <td>Тотал</td>
            </tr>
            <?php $users = get_users(); ?>
            <?php $place = 0; foreach($users as $user): $place++ ?>
                <tr>
                    <td><?=$place?></td>
                    <td><?=$user["userName"]?></td>
                    <td><strong><?=$user["points"]?></strong></td>
                    <td><?=$user["games3"]?></td>
                    <td><?=$user["games2"]?></td>
                    <td><?=$user["games1"]?></td>
                    <td><?=$user["games1"]  + $user["games2"] + $user["games3"]?></td>
                </tr>
            <?php endforeach ?>
        </table>
        

        <h4 class="nameOfTable mid" style="margin: 40px">Прогнозы пользователей<br></h4>
        <h6 class="nameOfTable mid" style="margin: 10px">Выбрать тур</h6>
        <div style="height: 150px" class="twoObjectsInLine">
            <form class="twoObjectsInLine" action="mainStatisticsPage.php" method="POST">
                <p class="mid" style="font-size: 30px">
                    <?php
                        if (isset($_POST['cur_round'])) {
                            $cur_round = $_POST['cur_round'];
                        } else {
                            $cur_round = 1;
                        }
                    ?>
                    <select type="cur_round" name="cur_round" value="" style="width: 100px">
                        <option><?=$cur_round?></option>
                        <?php for ($round = 1; $round <= 30; $round++): ?>
                            <option><?=$round?></option>
                        <?php endfor ?>
                    </select> тур
                </p>
                <p class="mid">
                    <?php
                        $users = get_users();
                        if (isset($_POST['cur_user'])) {
                            $cur_user = $_POST['cur_user'];
                        } else {
                            $cur_user = $users[0]['userName'];
                        }
                    ?>
                    <select type="cur_user" name="cur_user" value="" style="width: 200px">
                        <option><?=$cur_user?></option>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <option><?=$user["userName"]?></option>
                            </tr>
                        <?php endforeach ?>
                    </select>
                </p>
                <p class="mid" style="font-size: 30px">Пароль:  <input type="password" name="password" value="" style="width: 250px"></p>
                <p class="mid">
                    <button><img src="img/tick.png" /></button>
                </p>
            </form>
        </div>
        <?php if (isset($_POST['cur_user'])): ?>
            <?php $password = get_users_by_user_name($_POST['cur_user']); ?>
            <?php if ($_POST['password'] == $password[0]["userPassword"]): ?>
                <?php $games = get_games_by_round_id_and_userName($_POST['cur_round'], $_POST['cur_user']); ?>
                <?php if (count($games) == 0): ?>
                    <h3 class="title nameOfTable" style="margin-bottom: 150px">Вы еще не ставили на этот тур<br>и не можете увидеть ставки других</h3>
                <?php else: ?>
                    <div class="mid nameOfTable"><?=$cur_round?> тур:</div>
                    <table  style="margin-bottom: 150px; font-size: 20px">
                        <tr>
                            <td>Матч</td>
                            <?php $games = get_games_by_round_id($cur_round); ?>
                            <?php foreach($games as $game): ?>
                                <td><?=$game["homeTeam"]?> - <?=$game["awayTeam"]?></td>
                            <?php endforeach ?>
                            <td>Сумма за тур</td>
                        </tr>
                        <tr>
                            <td>Счет</td>
                            <?php foreach($games as $game): ?>
                                <?php if ($game['homeTeamGoals'] == -1): ?>
                                    <td><strong>-</strong></td>
                                <?php else: ?>
                                    <td style="width: 70px"><strong><?=$game["homeTeamGoals"]?> - <?=$game["awayTeamGoals"]?></strong></td>
                                <?php endif?>
                            <?php endforeach ?>
                            <td></td>
                        </tr> 
                        <?php
                            for ($i = 0; $i < count($users); $i++) {
                                $users[$i]["games3"] = $cur_round;
                            }
                        ?>
                        <?php uasort($users, "cmp_sort_by_points_per_round"); ?>
                        <?php foreach($users as $user): ?>
                            <p><?php $_POST['cur_user']?> <?php  $user["userName"] ?></p>
                            <?php if ($_POST['cur_user'] == $user["userName"]): ?>
                                <tr style="font-weight: 1000">
                            <?php else: ?>
                                <tr>
                            <?php endif ?>
                                <td><?=$user["userName"]?></td>
                                <?php $games = get_games_by_round_id_and_userName($cur_round, $user["userName"]); ?>
                                <?php if (count($games) == 0): ?>
                                    <?php for ($i = 0; $i < 8;$i++): ?>
                                        <td style="width: 70px">-</td>
                                    <?php endfor ?>
                                    <td><strong>0</strong></td>
                                <?php else: ?>
                                    <?php $sum_points = 0; foreach($games as $game): $sum_points += $game["points"]?>
                                        <td style="width: 70px"><?=$game["homeTeamGoals"]?> - <?=$game["awayTeamGoals"]?></td>
                                    <?php endforeach ?> 
                                    <td><strong><?=$sum_points?></strong></td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach ?> 
                    </table>
                <?php endif ?>
            <?php else: ?>
                <h3 class="title nameOfTable" style="margin-bottom: 150px">Неверный пароль</h3>
            <?php endif ?>
        <?php endif ?>
    </main>

</body>