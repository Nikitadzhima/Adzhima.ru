<?php
    require_once 'include/database.php';
    require_once 'include/functions.php';
?>


<!DOCTYPE html>

<head>
    <title>Создать аккаунт</title>
    <meta charset="utf-8">

    <link rel="stylesheet" href="css/global.css" type="text/css">
</head>

<body>
    <script src="js/nav-bar.js"></script>
    <script src="js/highlight-links.js"></script>

    <main>
        <?php if (isset($_GET['doneResults'])): ?>
            <?php 
                $round_id = $_GET['round_id'];
                $games = get_games_by_round_id($round_id);
                $i = 0;
                foreach ($games as $game) {
                    if ($game['homeTeamGoals'] == -1 && strlen($_POST['homeTeamGoals'.$game['game_id']]) > 0 && strlen($_POST['awayTeamGoals'.$game['game_id']]) > 0) {
                        $homeTeamGoals = trim($_POST['homeTeamGoals'.$game['game_id']]);
                        $awayTeamGoals = trim($_POST['awayTeamGoals'.$game['game_id']]);
    
                        $insert_result = insert_result($game['game_id'], $homeTeamGoals, $awayTeamGoals);
                        $usersbets = get_usersbets_by_game_id($game['game_id']);
                        foreach ($usersbets as $bet) {
                            $homeGoals = $bet['homeTeamGoals'];
                            $awayGoals = $bet['awayTeamGoals'];
                            
                            if ($homeGoals == $homeTeamGoals and $awayGoals == $awayTeamGoals) {
                                $points = 3;
                            } elseif ($homeTeamGoals - $awayTeamGoals == $homeGoals - $awayGoals) {
                                $points = 2;
                            } elseif (($homeGoals > $awayGoals and $homeTeamGoals > $awayTeamGoals) or
                                        ($homeGoals < $awayGoals and $homeTeamGoals < $awayTeamGoals)) {
                                $points = 1;
                            } else {
                                $points = 0;
                            }
                            $insert_result = insert_points_by_bet_id($bet['bet_id'], $points);
                            $insert_result = insert_points_by_user_name($bet['userName'], $points);
                        }
                        
                        $i = $game['game_id'];
                        $j = $game['homeTeam'];
                        $p = $game['awayTeam'];
                        $insert_result = insert_result_by_game_id($game['game_id'], $game['homeTeam'], $game['awayTeam'], $homeTeamGoals, $awayTeamGoals);
                    }
                }
            ?>
            <h2 class="title nameOfTable">Результаты успешно добавлены</h2>
        <?php elseif (isset($_GET['newRound'])): ?>
            <?php 
                $round_id = $_POST['round_id'];
                for ($i = 1; $i <= 8; $i++) {
                    $homeTeam = trim($_POST['homeTeam'.$i]);
                    $awayTeam = trim($_POST['awayTeam'.$i]);
                    $date = trim($_POST['date'.$i]);
                    
                    $insert_result = insert_game($round_id, $homeTeam, $awayTeam, $date);
                }
            ?>
            <h2 class="title nameOfTable">Тур успешно добавлен</h2>
        <?php elseif (isset($_GET['newResults'])): ?>
            <h2 class="title nameOfTable">Внести результаты</h2>
            <form action="addProfile.php?&doneResults=true&round_id=<?=$_POST['round_id']?>" method="POST">
                <div class="twoObjectsInLine">
                    <div style="font-size: 60px; display: flex; flex-direction: column">
                        <h2 class="title nameOfTable"><?=$_POST['round_id']?> тур</h2>
                        <table>
                            <tr>
                                <td>Хозяева</td>
                                <td colspan="2">Счет</td>
                                <td>Гости</td>
                            </tr>
                            <?php $games = get_games_by_round_id($_POST['round_id']); ?>
                            <?php foreach($games as $game): ?>
                                <tr>
                                    <td><?=$game["homeTeam"]?></td>
                                    <?php if ($game["homeTeamGoals"] == '-1'): ?>
                                        <td><input class="betInput" type="homeTeamGoals<?=$game['game_id']?>" name="homeTeamGoals<?=$game['game_id']?>" value=""></td>
                                        <td><input class="betInput" type="awayTeamGoals<?=$game['game_id']?>" name="awayTeamGoals<?=$game['game_id']?>" value=""></td>
                                    <?php else: ?>
                                        <td><?=$game["homeTeamGoals"]?></td>
                                        <td><?=$game["awayTeamGoals"]?></td>
                                    <?php endif ?>
                                    <td><?=$game["awayTeam"]?></td>
                                </tr>
                            <?php endforeach ?>
                        </table>
                        <div><button class="buttonBet"><img src="img/tick.png"/></button></div>
                    </div>
                </div>
            </form>
        <?php elseif (isset($_GET['addRound'])): ?>
            <h2 class="title nameOfTable">Добавить тур</h2>
            <form action="addProfile.php?&newRound=true" method="POST">
                <div class="twoObjectsInLine">
                    <div style="font-size: 60px; display: flex; flex-direction: column">
                        <p>Номер тура:  <input style="width: 50px" type="round_id" name="round_id" value=""></p>
                        <table>
                            <tr>
                                <td>Дома</td>
                                <td>В гостях</td>
                                <td>Дата</td>
                            </tr>
                            <?php for ($game = 1; $game <= 8; $game++): ?>
                                <tr>
                                    <td><input style="width: 200px" type="homeTeam<?=$game?>" name="homeTeam<?=$game?>" value=""></td>
                                    <td><input style="width: 200px" type="awayTeam<?=$game?>" name="awayTeam<?=$game?>" value=""></td>
                                    <td><input style="width: 150px" type="date<?=$game?>" name="date<?=$game?>" value=""></td>
                                </tr>
                            <?php endfor ?>
                        </table>
                        <div><button class="buttonBet"><img src="img/tick.png"/></button></div>
                    </div>
                </div>
            </form>
        <?php elseif (isset($_GET['addResults'])): ?>
            <h2 class="title nameOfTable">Внести результаты</h2>
            <form action="addProfile.php?&newResults=true" method="POST">
                <div class="twoObjectsInLine">
                    <div style="font-size: 60px; display: flex; flex-direction: column">
                        <p>Номер тура:  <input style="width: 50px" type="round_id" name="round_id" value=""></p>
                        <div><button class="buttonBet"><img src="img/tick.png"/></button></div>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <?php if (isset($_GET['madeProfile']) && $_POST['newName'] == "iamadmin" && strlen($_POST['newPassword']) == 0): ?>
                <div style="display: flex; flex-direction: column">
                    <div class="twoObjectsInLine">
                        <form action="addProfile.php?&addRound=true" method="POST">
                            <h1>Добавить матчи</h1>
                            <div><button class="buttonBet"><img src="img/tick.png"/></button></div>
                        </form>
                        <form action="addProfile.php?&addResults=true" method="POST">
                            <h1>Добавить результаты</h1>
                            <div><button class="buttonBet"><img src="img/tick.png"/></button></div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <h2 class="title nameOfTable">Создать аккаунт</h2>
                <div class="twoObjectsInLine">
                    <?php if (isset($_GET['madeProfile'])): ?>
                        <?php $user_name = $_POST['newName']; ?>
                    <?php else: ?>
                        <?php $user_name = ""; ?>
                    <?php endif ?>
                    <?php $oldusers = get_users_by_user_name($user_name); ?>
                    <?php if (isset($_GET['madeProfile']) && count($oldusers) == 0): ?>
                        <?php if (strlen($_POST['newName']) == 0): ?>
                            <h2 class="title nameOfTable">Введите имя пользователя</h2>
                        <?php elseif (strlen($_POST['newPassword']) == 0): ?>
                            <h2 class="title nameOfTable">Введите пароль</h2>
                        <?php elseif (strlen($_POST['newName']) > 11): ?>
                            <h2 class="title nameOfTable">Слишком длинное имя пользователя</h2>
                        <?php else: ?>
                            <?php $insert_result = insert_user($_POST['newName'], $_POST['newPassword']); ?>
                            <div style="display: flex; flex-direction: column">
                                <h2 class="title nameOfTable">Вы успешно зарегистрированы!</h2>
                                <h4 class="title nameOfTable">Ваше имя: <?=$_POST['newName']?></h4>
                                <h4 class="title nameOfTable">Ваш пароль: <?=$_POST['newPassword']?></h4>
                                <h4>Запомните свой пароль!<br>Его нужно будет вводить каждый раз, делая прогноз</h4>
                            </div>
                        <?php endif ?>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column">
                            <?php if (isset($_GET['madeProfile'])): ?>
                                <p class="title nameOfTable">Пользователь с таким именем уже существует!</p>
                                <p class="title nameOfTable">Придумайте другое имя</p>
                            <?php endif ?>
                            <form action="addProfile.php?&madeProfile=true" method="POST">
                                <div class="twoObjectsInLine">
                                    <div style="font-size: 60px; display: flex; flex-direction: column">
                                        <div class="bottom">Имя пользователя:</div>
                                        <div class="bottom"><input style="width: 300px" type="newName" name="newName" value=""></div>
                                        <div class="bottom">Пароль:</div>
                                        <div class="bottom"><input style="width: 300px" type="newPassword" name="newPassword" value=""></div>
                                        <div class="bottom"><button class="buttonBet"><img src="img/tick.png"/></button></div>
                                        <h6>Придумайте не очень легкий, но и не очень сложный пароль<br>Его нужно будет вводить каждый раз, делая прогноз<br>
                                        Имя пользователя не должно содержать более 11 символов</h6>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif ?>
                </div>
            <?php endif ?>
        <?php endif ?>
    </main>
</body>
