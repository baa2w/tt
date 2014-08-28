<?php
require_once('config.php');

ob_start();

$venues  = $db->fetchAll("SELECT * FROM venues;");
$teams   = $db->fetchAll("SELECT * FROM teams;");
$players = $db->fetchAll("SELECT * FROM players;");
$seasons = $db->fetchAll(
    "SELECT s.season_id, s.league_id, l.league_name || ' ' || s.season_name as season "
    . "FROM seasons s INNER JOIN leagues l ON s.league_id = l.league_id;"
);

// prepare page for entering match results
if (isset($_GET['insert'])) {
    // save results if posted
    if (isset($_POST['team1'])) {
        // insert new match
        $db->insert(
            'matches',
            array(
                'season_id'  => $_POST['season_id'],
                'team1_id'   => $_POST['team1'],
                'team2_id'   => $_POST['team2'],
                'match_date' => $_POST['match_date']
            )
        );

        // get match id
        $id = current($db->executeQuery("SELECT last_insert_rowid(); ")->fetch());

        // insert games
        foreach ($_POST['match'] as $match) {
            $result = explode('-', $match['score']);
            $db->insert(
                'games',
                array(
                    'match_id'    => $id,
                    'player1_id'  => $match['player1_id'],
                    'player2_id'  => $match['player2_id'],
                    'player1a_id' => isset($match['player1a_id']) ? $match['player1a_id'] : '',
                    'player2a_id' => isset($match['player2a_id']) ? $match['player2a_id'] : '',
                    'result1'     => $result[0],
                    'result2'     => $result[1]
                )
            );
        }
    } else {
        include 'insert.php';
    }
}

// display stats
if (!isset($_GET['insert'])) {

    // calculate stats for players
    $playerStats = $db->fetchAll(<<<ESQL
        SELECT s.*, CAST(s.singles_won as FLOAT) / (s.singles_won + s.singles_lost) singles_avg
        FROM
        (
          SELECT
            pl.name name,
            SUM(pl.singles_won)  singles_won,
            SUM(pl.singles_lost) singles_lost,
            SUM(pl.doubles_won)  doubles_won,
            SUM(pl.doubles_lost) doubles_lost

          FROM
          (
              SELECT
                p.name name,
                SUM(p.singles_won)  singles_won,
                SUM(p.singles_lost) singles_lost,
                SUM(p.doubles_won)  doubles_won,
                SUM(p.doubles_lost) doubles_lost
              FROM
              (
                  SELECT
                    p1.last_name name,
                    CASE WHEN g.result1 > g.result2 AND g.player1a_id == '' THEN 1 ELSE 0 END singles_won,
                    CASE WHEN g.result1 < g.result2 AND g.player1a_id == '' THEN 1 ELSE 0 END singles_lost,
                    CASE WHEN g.result1 > g.result2 AND g.player1a_id <> '' THEN 1 ELSE 0 END doubles_won,
                    CASE WHEN g.result1 < g.result2 AND g.player1a_id <> '' THEN 1 ELSE 0 END doubles_lost
                  FROM games g
                  JOIN players p1 ON g.player1_id = p1.player_id
              ) p
              GROUP BY p.name

              UNION ALL

              SELECT
                p.name name,
                SUM(p.singles_won)  singles_won,
                SUM(p.singles_lost) singles_lost,
                SUM(p.doubles_won)  doubles_won,
                SUM(p.doubles_lost) doubles_lost
              FROM
              (
                  SELECT
                    p2.last_name name,
                    CASE WHEN g.result1 < g.result2 AND g.player1a_id == '' THEN 1 ELSE 0 END singles_won,
                    CASE WHEN g.result1 > g.result2 AND g.player1a_id == '' THEN 1 ELSE 0 END singles_lost,
                    CASE WHEN g.result1 < g.result2 AND g.player1a_id <> '' THEN 1 ELSE 0 END doubles_won,
                    CASE WHEN g.result1 > g.result2 AND g.player1a_id <> '' THEN 1 ELSE 0 END doubles_lost
                  FROM games g
                  JOIN players p2 ON g.player2_id = p2.player_id
              ) p
              GROUP BY p.name
          ) pl

          GROUP BY name
        ) s
        ORDER BY singles_avg DESC
ESQL
    );

    include 'stats.php';
}

$content = ob_get_contents();
ob_end_clean();

// render page
include 'layout.php';
