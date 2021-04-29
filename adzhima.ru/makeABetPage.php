<?php
    require_once 'include/database.php';
    require_once 'include/functions.php';
?>


<!DOCTYPE html>

<head>
    <title>Сделать прогноз</title>
    <meta charset="utf-8">

    <link rel="stylesheet" href="css/global.css" type="text/css">
</head>

<body>
    <script src="js/nav-bar.js"></script>
    <script src="js/highlight-links.js"></script>

    <main>
        <h2 class="title nameOfTable">Сделать прогноз</h2>
        <div style="display: flex; flex-direction: column">
            <div style="height: 150px" class="twoObjectsInLine">
                <form class="twoObjectsInLine" action="makeABetPage.php" method="POST">
                    <p class="mid">
                        <?php $users = get_users(); ?>
                        <?php
                            if (isset($_GET['userName'])) {
                                $bet_user = $_GET['userName'];
                            } elseif (isset($_POST['bet_user'])) {
                                $bet_user = $_POST['bet_user'];
                            } else {
                                $bet_user = $users[0]['userName'];
                            }
                        ?>
                        <select type="bet_user" name="bet_user" value="" style="width: 200px">
                            <option><?=$bet_user?></option>
                            <?php foreach($users as $user): ?>
                                <tr>
                                    <option><?=$user["userName"]?></option>
                                </tr>
                            <?php endforeach ?>
                        </select>
                    </p>
                    <p class="mid" style="font-size: 30px">
                        <?php
                            if (isset($_POST['bet_round'])) {
                                $bet_round = $_POST['bet_round'];
                            } elseif (isset($_GET['round_id'])) {
                                $bet_round = $_GET['round_id'];
                            } else {
                                $bet_round = 1;
                            }
                        ?>
                        <select type="bet_round" name="bet_round" value="" style="width: 100px">
                            <option><?=$bet_round?></option>
                            <?php for ($round = 1; $round <= 30; $round++): ?>
                                <option><?=$round?></option>
                            <?php endfor ?>
                        </select> тур
                    </p>
                   
                    <p class="mid">
                        <button><img src="img/tick.png" /></button>
                    </p>
                </form>
            </div>
            <div class="twoObjectsInLine">
                <?php $games = get_games_by_round_id_and_userName($bet_round, $bet_user); ?>
                <?php if(isset($_GET['madeBets'])): ?>
                    <?php 
                        $userName = $_GET['userName'];
                        $password = get_users_by_user_name($userName);
                        $round_id = $_GET['round_id'];
                    ?>
                    <?php if($_POST['password'] == $password[0]["userPassword"]): ?>
                        <?php $games = get_games_by_round_id($round_id); ?>
                        <?php foreach($games as $game): ?>
                            <?php 
                                $homeTeamGoals = trim($_POST['homeTeamGoals'.$game['game_id']]);
                                $awayTeamGoals = trim($_POST['awayTeamGoals'.$game['game_id']]);
                                $game_id = $game['game_id'];
                                $match = get_match_by_game_id($game_id);
                                $homeGoals = $match[0]["homeTeamGoals"];
                                $awayGoals = $match[0]["awayTeamGoals"];
                                $points = 0;
                                /*if ($homeGoals == $homeTeamGoals and $awayGoals == $awayTeamGoals) {
                                    $points = 3;
                                } elseif ($homeTeamGoals - $awayTeamGoals == $homeGoals - $awayGoals) {
                                    $points = 2;
                                } elseif (($homeGoals > $awayGoals and $homeTeamGoals > $awayTeamGoals) or
                                            ($homeGoals < $awayGoals and $homeTeamGoals < $awayTeamGoals)) {
                                    $points = 1;
                                } else {
                                    $points = 0;
                                }*/
                                
        
                                $insert_result = insert_bet($userName, $game_id, $round_id, $homeTeamGoals, $awayTeamGoals, $points);
                                
                            ?>
                        <?php endforeach ?>
                        <h2 class="title nameOfTable">Прогнозы приняты!</h2>
                    <?php else: ?>
                        <h2 class="title nameOfTable">Неверный пароль!</h2>
                    <?php endif ?>
                <?php elseif (count($games)): ?>
                    <div style="display: flex; flex-direction: column">
                        <div class="twoObjectsInLine">
                            <h1 class="title nameOfTable">Прогнозы на этот тур уже сделаны!</h1>   
                        </div>
                        
                    </div>
                <?php else: ?>
                    <?php $games = get_games_by_round_id($bet_round); ?>
                    <form action="makeABetPage.php?userName=<?=$bet_user?>&round_id=<?=$bet_round?>&madeBets=true" method="POST">
                        <div>    
                            <table>
                                <tr>
                                    <td>Хозяева</td>
                                    <td colspan="2">Счет</td>
                                    <td>Гости</td>
                                </tr>
                                <p class="twoObjectsInLine">
                                    <?php foreach($games as $game): ?>
                                        <tr>
                                            <td><?=$game["homeTeam"]?></td>
                                            <td><input class="betInput" type="homeTeamGoals<?=$game['game_id']?>" name="homeTeamGoals<?=$game['game_id']?>" value=""></td>
                                            <td><input class="betInput" type="awayTeamGoals<?=$game['game_id']?>" name="awayTeamGoals<?=$game['game_id']?>" value=""></td>
                                            <td><?=$game["awayTeam"]?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </p>
                            </table>
                            <div class="bottom">
                                <h3 class="title nameOfTable">Пароль:  <input type="password" name="password" value="" style="width: 250px"></h3>
                                <div></div><button class="buttonBet"><img src="img/tick.png"/></button></div>
                            </div>
                        <div class="twoObjectsInLine">
                    </form>
                <?php endif ?>
            </div>
        </div>
    </main>
</body>
