<?php

function get_standings() {
    global $link;
    $sql = "SELECT * FROM teams21 ORDER BY points DESC, goalsDiff DESC";
    $result = mysqli_query($link, $sql);
    $standings = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $standings;
}


function get_games_by_round_id($round_id) {
    global $link;
    $sql = "SELECT * FROM fixtures21 WHERE round_id = ".$round_id;
    $result = mysqli_query($link, $sql);
    $games = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $games;
}

function get_usersbets_by_game_id($game_id) {
    global $link;
    $sql = "SELECT * FROM usersbets21 WHERE game_id = ".$game_id;
    $result = mysqli_query($link, $sql);
    $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $bets;
}

function get_games_by_round_id_and_userName($round_id, $userName) {
    global $link;
    $sql = "SELECT * FROM usersbets21 WHERE round_id = ".$round_id." AND userName = '".$userName."'";
    $result = mysqli_query($link, $sql);
    $games = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $games;
}

function get_users() {
    global $link;
    $sql = "SELECT * FROM users21 ORDER BY points DESC, games3 DESC, games2 DESC";
    $result = mysqli_query($link, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $users;
}

function get_users_by_user_name($bet_user) {
    global $link;
    $sql = "SELECT * FROM users21 WHERE userName = '".$bet_user."'";
    $result = mysqli_query($link, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $users;
}

function get_match_by_game_id($game_id) {
    global $link;
    $sql = "SELECT * FROM fixtures21 WHERE game_id = ".$game_id;
    $result = mysqli_query($link, $sql);
    $match = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $match;
}

function insert_user($userName, $userPassword) {
    global $link;
    $userName = mysqli_real_escape_string($link, $userName);
    $userPassword = mysqli_real_escape_string($link, $userPassword);
    
    $insert_query = "INSERT INTO users21(userName, games3, games2, games1, points, userPassword) VALUES ('$userName', 0, 0, 0, 0, '$userPassword')";
    $result = mysqli_query($link, $insert_query);
    return 'Все хорошо';
}

function insert_game($round_id, $homeTeam, $awayTeam, $date) {
    global $link;
    $round_id = mysqli_real_escape_string($link, $round_id);
    $homeTeam = mysqli_real_escape_string($link, $homeTeam);
    $awayTeam = mysqli_real_escape_string($link, $awayTeam);
    $date = mysqli_real_escape_string($link, $date);
    
    if ($round_id == 0) {
        return 'sth wrong with it, man';
    }
    
    $query = "SELECT * FROM fixtures21 WHERE homeTeam = '$homeTeam' AND awayTeam = '$awayTeam' AND round_id = '$round_id'";
    
    $result = mysqli_query($link, $query);
    if (!mysqli_num_rows($result)) {
        $insert_query = "INSERT INTO fixtures21 (round_id, homeTeam, awayTeam, homeTeamGoals, awayTeamGoals, date)
                        VALUES ('$round_id', '$homeTeam', '$awayTeam', '-1', '-1', '$date')";
        $result = mysqli_query($link, $insert_query);
        if ($result) {
            return 'Тур добавлен!';
        } else {
            return 'Что-то пошло не так...';
        }
    } else {
        return 'Уже создано!';
    }
}

function insert_result($game_id, $homeTeamGoals, $awayTeamGoals) {
    global $link;
    $game_id = mysqli_real_escape_string($link, $game_id);
    $homeTeamGoals = mysqli_real_escape_string($link, $homeTeamGoals);
    $awayTeamGoals = mysqli_real_escape_string($link, $awayTeamGoals);
    
    $update_query = "UPDATE fixtures21 SET homeTeamGoals = '$homeTeamGoals' WHERE game_id = '$game_id'";
    $result = mysqli_query($link, $update_query);
    $update_query = "UPDATE fixtures21 SET awayTeamGoals = '$awayTeamGoals' WHERE game_id = '$game_id'";
    $result = mysqli_query($link, $update_query);
    if ($result) {
        return 'Ставки сделаны!';
    } else {
        return 'Что-то пошло не так...';
    }
}

function insert_points_by_bet_id($bet_id, $points) {
    global $link;
    $bet_id = mysqli_real_escape_string($link, $bet_id);
    $points = mysqli_real_escape_string($link, $points);
    
    $update_query = "UPDATE usersbets21 SET points = '$points' WHERE bet_id = '$bet_id'";
    $result = mysqli_query($link, $update_query);
    if ($result) {
        return 'Очки добавлены';
    } else {
        return 'Что-то пошло не так...';
    }
}

function insert_points_by_user_name($user_name, $points) {
    global $link;
    $user_name = mysqli_real_escape_string($link, $user_name);
    $points = mysqli_real_escape_string($link, $points);
    
    $update_query = "UPDATE users21 SET games".$points." = games".$points." + 1 WHERE userName = '$user_name'";

    $result = mysqli_query($link, $update_query);

    $update_query = "UPDATE users21 SET points = games3 * 3 + games2 * 2 + games1 WHERE userName = '$user_name'";

    $result = mysqli_query($link, $update_query);
    if ($result) {
        return 'Очки добавлены';
    } else {
        return 'Что-то пошло не так...';
    }
}

function insert_result_by_game_id($game_id, $homeTeam, $awayTeam, $homeTeamGoals, $awayTeamGoals) {
    global $link;
    $game_id = mysqli_real_escape_string($link, $game_id);
    $homeTeam = mysqli_real_escape_string($link, $homeTeam);
    $awayTeam = mysqli_real_escape_string($link, $awayTeam);
    $homeTeamGoals = mysqli_real_escape_string($link, $homeTeamGoals);
    $awayTeamGoals = mysqli_real_escape_string($link, $awayTeamGoals);
    
    $update_query = "UPDATE teams21 SET matchsPlayed = matchsPlayed + 1 WHERE teamName = '$homeTeam'";
    $result = mysqli_query($link, $update_query);
    $update_query = "UPDATE teams21 SET matchsPlayed = matchsPlayed + 1 WHERE teamName = '$awayTeam'";
    $result = mysqli_query($link, $update_query);
    if ($homeTeamGoals > $awayTeamGoals) {
        $update_query = "UPDATE teams21 SET win = win + 1 WHERE teamName = '$homeTeam'";
        $result = mysqli_query($link, $update_query);
        $update_query = "UPDATE teams21 SET lose = lose + 1 WHERE teamName = '$awayTeam'";
        $result = mysqli_query($link, $update_query);
        $update_query = "UPDATE teams21 SET points = points + 3 WHERE teamName = '$homeTeam'";
        $result = mysqli_query($link, $update_query);
    } else if ($homeTeamGoals < $awayTeamGoals) {
        $update_query = "UPDATE teams21 SET win = win + 1 WHERE teamName = '$awayTeam'";
        $result = mysqli_query($link, $update_query);
        $update_query = "UPDATE teams21 SET lose = lose + 1 WHERE teamName = '$homeTeam'";
        $result = mysqli_query($link, $update_query);
        $update_query = "UPDATE teams21 SET points = points + 3 WHERE teamName = '$awayTeam'";
        $result = mysqli_query($link, $update_query);
    } else {
        $update_query = "UPDATE teams21 SET draw = draw + 1 WHERE teamName = '$homeTeam'";
        $result = mysqli_query($link, $update_query);
        $update_query = "UPDATE teams21 SET draw = draw + 1 WHERE teamName = '$awayTeam'";
        $result = mysqli_query($link, $update_query);
        $update_query = "UPDATE teams21 SET points = points + 1 WHERE teamName = '$homeTeam'";
        $result = mysqli_query($link, $update_query);
        $update_query = "UPDATE teams21 SET points = points + 1 WHERE teamName = '$awayTeam'";
        $result = mysqli_query($link, $update_query);
    }
    $update_query = "UPDATE teams21 SET goalsFor = goalsFor + ".$homeTeamGoals." WHERE teamName = '$homeTeam'";
    $result = mysqli_query($link, $update_query);
    $update_query = "UPDATE teams21 SET goalsAgainst = goalsAgainst + ".$awayTeamGoals." WHERE teamName = '$homeTeam'";
    $result = mysqli_query($link, $update_query);
    $update_query = "UPDATE teams21 SET goalsDiff = goalsDiff + ".$homeTeamGoals." - ".$awayTeamGoals." WHERE teamName = '$homeTeam'";
    $result = mysqli_query($link, $update_query);
    
    $update_query = "UPDATE teams21 SET goalsFor = goalsFor + ".$awayTeamGoals." WHERE teamName = '$awayTeam'";
    $result = mysqli_query($link, $update_query);
    $update_query = "UPDATE teams21 SET goalsAgainst = goalsAgainst + ".$homeTeamGoals." WHERE teamName = '$awayTeam'";
    $result = mysqli_query($link, $update_query);
    $update_query = "UPDATE teams21 SET goalsDiff = goalsDiff + ".$awayTeamGoals." - ".$homeTeamGoals." WHERE teamName = '$awayTeam'";
    $result = mysqli_query($link, $update_query);
    
    if ($result) {
        return 'Результат добавлен';
    } else {
        return 'Что-то пошло не так...';
    }
}

function insert_bet($userName, $game_id, $round_id, $homeTeamGoals, $awayTeamGoals, $points) {
    global $link;
    $userName = mysqli_real_escape_string($link, $userName);
    $game_id = mysqli_real_escape_string($link, $game_id);
    $round_id = mysqli_real_escape_string($link, $round_id);
    $homeTeamGoals = mysqli_real_escape_string($link, $homeTeamGoals);
    $awayTeamGoals = mysqli_real_escape_string($link, $awayTeamGoals);
    $points = mysqli_real_escape_string($link, $points);

    $query = "SELECT * FROM usersbets21 WHERE userName = '$userName' AND game_id = '$game_id'";

    $result = mysqli_query($link, $query);

    if (!mysqli_num_rows($result)) {

        $insert_query = "INSERT INTO usersbets21 (userName, game_id, round_id, homeTeamGoals, awayTeamGoals, points) 
                            VALUES ('$userName', '$game_id', '$round_id', '$homeTeamGoals', '$awayTeamGoals', '$points')";
        $result = mysqli_query($link, $insert_query);

        if ($result) {
            return 'Ставки сделаны!';
        } else {
            return 'Что-то пошло не так...';
        }
    } else {
        return 'Уже создано';
    }
}

function cmp_sort_by_points_per_round($user1, $user2) {
    $points1 = 0;
    $points2 = 0;
    $cur_round = $user1["games3"];
    $games1 = get_games_by_round_id_and_userName($cur_round, $user1["userName"]);
    $games2 = get_games_by_round_id_and_userName($cur_round, $user2["userName"]);
    foreach($games1 as $game) {
        $points1 += $game["points"];
    }
    foreach($games2 as $game) {
        $points2 += $game["points"];
    }
    
    if ($points1 > $points2) {
        return -1;
    } elseif ($points1 == $points2) {
        return 0;
    } else {
        return 1;
    }
}