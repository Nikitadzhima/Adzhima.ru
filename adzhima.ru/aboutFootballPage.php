<?php
    require_once 'include/database.php';
    require_once 'include/functions.php';
?>


<!DOCTYPE html>

<head>
    <title>Про футбол</title>
    <meta charset="utf-8">

    <link rel="stylesheet" href="css/global.css" type="text/css">
    <link rel="stylesheet" href="css/grid.css" type="text/css">
</head>

<body>
    <script src="js/nav-bar.js"></script>
    <script src="js/highlight-links.js"></script>

    <main>
        <h4 class="title nameOfTable">Таблица РПЛ</h4>
        <table>
            <tr>
                <td></td> <!-- logo -->
                <td>Место</td>
                <td>Команда</td>
                <td>И</td>
                <td>В</td>
                <td>Н</td>
                <td>П</td>
                <td>З</td>
                <td>П</td>
                <td>РМ</td>
                <td>Очки</td>
            </tr>
            <?php $standings = get_standings(); ?>
            <?php 
                $rank = 1;
                foreach($standings as $team): ?>
                <tr>
                    <td><img class="logo" src=<?=$team["logo"]?> /></td>
                    <td><strong><?=$rank?></strong></td>
                    <td><strong><?=$team["teamName"]?></strong></td>
                    <td><?=$team["matchsPlayed"]?></td>
                    <td><?=$team["win"]?></td>
                    <td><?=$team["draw"]?></td>
                    <td><?=$team["lose"]?></td>
                    <td><?=$team["goalsFor"]?></td>
                    <td><?=$team["goalsAgainst"]?></td>
                    <td>
                    <?php 
                        $rank++;
                        if ($team["goalsDiff"] > 0) {
                            echo '+'.'';
                        }
                    echo $team["goalsDiff"];
                    ?>
                    </td>
                    <td><strong><?=$team["points"]?></strong></td>
                </tr>
            <?php endforeach ?>
        </table>

        <div class="twoObjectsInLine">
            <h4 class="title nameOfTable">Календарь РПЛ</h4>
        </div>
        <div class="twoObjectsInLine">
            <p class="mid twoObjectsInLine">
                <?php
                    if (isset($_POST['fixtures_round'])) {
                        $fixtures_round = $_POST['fixtures_round'];
                    } else {
                        $fixtures_round = 1;
                    }
                ?>
                <div>
                    <form class="twoObjectsInLine" action="aboutFootballPage.php" method="POST">
                        <div style="font-size: 30px" class="mid">Выбрать тур:</div>
                        <div><select type="fixtures_round" name="fixtures_round" value="">
                            <option><?=$fixtures_round?></option>
                            <?php for ($round = 1; $round <= 30; $round++): ?>
                                <option><?=$round?></option>
                            <?php endfor ?>
                        </select></div>
                        <div><button><img src="img/tick.png" /></button></div>
                    </form>
                </div>
            </p>
        </div>
        <div style="height: 90px" class="mid">
            <h4 style="font-size: 50px"><?=$fixtures_round?> тур:</h4>
        </div>
        <div class="mid" style="margin-bottom: 150px">
            
            <table>
                <tr>
                    <td class="long_td">Матч</td>
                    <td style="width: 100px">Счет</td>
                    <td>Дата</td>
                </tr>
                <?php $games = get_games_by_round_id($fixtures_round); ?>
                <?php foreach($games as $game): ?>
                    <tr>
                        <td class="long_td"><?=$game["homeTeam"]?> - <?=$game["awayTeam"]?></td>
                        <?php if ($game["homeTeamGoals"] == -1): ?>
                            <td style="width: 100px"><strong>-</strong></td>
                        <?php else: ?>
                            <td style="width: 100px"><strong><?=$game["homeTeamGoals"]?> - <?=$game["awayTeamGoals"]?></strong></td>
                        <?php endif ?>
                        <td><?=$game["date"]?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    </main>
</body>
