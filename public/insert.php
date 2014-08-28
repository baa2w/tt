<?php
    // prepare a function to return html markup for selecting a player, team etc
    $playerOptions = function ($players) {
        $html = '';
        foreach ($players as $player) {
            $html .= '<option value="' . $player['player_id'] . '">'
                   . $player['first_name'] . ' ' . $player['last_name']
                   . '</option>';
        }

        return $html;
    };
    $teamOptions = function ($teams) {
        $html = '';
        foreach ($teams as $team) {
            $html .= '<option value="' . $team['team_id'] . '">'
                   . $team['team_name']
                   . '</option>';
        }

        return $html;
    };

    $seasonOptions = function ($seasons) {
        $html = '';
        foreach ($seasons as $season) {
            $html .= '<option value="' . $season['season_id'] . '">'
                   . $season['season']
                   . '</option>';
        }

        return $html;
    };

    // prepare score options
    $scoreOptions = function ($bestOfSets) {
        $max = floor((int) $bestOfSets / 2);
        $html1 = '';
        $html2 = '';
        for ($i = 0; $i < $max; $i++) {
            $score  = array($max, $i);
            $html1 .= '<option value="' . implode('-', $score) . '">' . implode(' - ', $score) . '</options>';
            $score  = array_reverse($score);
            $html2 .= '<option value="' . implode('-', $score) . '">' . implode(' - ', $score) . '</options>';
        }

        return $html1 . $html2;
    };
?>
<div class="row">
    <h1 class="page-header">Enter Match Results</h1>

    <div class="col-md-2">
        <form role="form">
        <div class="form-group">Player A: <select name="p_a"><?php echo $playerOptions($players) ?></select></div>
        <div class="form-group">Player B: <select name="p_b"><?php echo $playerOptions($players) ?></select></div>
        <div class="form-group">Player C: <select name="p_c"><?php echo $playerOptions($players) ?></select></div>
        <div class="form-group">Player 1: <select name="p_1"><?php echo $playerOptions($players) ?></select></div>
        <div class="form-group">Player 2: <select name="p_2"><?php echo $playerOptions($players) ?></select></div>
        <div class="form-group">Player 3: <select name="p_3"><?php echo $playerOptions($players) ?></select></div>
        <div class="form-group"><button class="btn btn-primary generate-btn" type="button">Seed</button></div>
        </form>
    </div>

    <div class="col-md-10">
        <form role="form" class="results-form" method="post">

        <?php
            // prepare singles and doubles combinations
            $singles = array(
                array('A', '1'),
                array('B', '2'),
                array('C', '3'),
                array('B', '1'),
                array('A', '3'),
                array('C', '2'),
                array('B', '3'),
                array('C', '1'),
                array('A', '2')
            );
            $doubles = array(
                array(
                    array('B', 'C'),
                    array('2', '3')
                ),
                array(
                    array('A', 'C'),
                    array('1', '3')
                ),
                array(
                    array('A', 'B'),
                    array('1', '2')
                ),
            );
        ?>

        <table class="table results-table">
            <thead>
            <tr>
                <th><select name="season_id"><?php echo $seasonOptions($seasons) ?></select></th>
                <th>Team 1: <select name="team1"><?php echo $teamOptions($teams) ?></select></th>
                <th>Team 2: <select name="team2"><?php echo $teamOptions($teams) ?></select></th>
                <th>Score Result</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($singles as $key => $seed) :?>
                <tr class="game game-singles">
                    <td><?php echo implode('-', $seed) ?></td>
                    <td><select name="match[<?php echo $key ?>][player1_id]"><?php echo $playerOptions($players) ?></select></td>
                    <td><select name="match[<?php echo $key ?>][player2_id]"><?php echo $playerOptions($players) ?></select></td>
                    <td><select name="match[<?php echo $key ?>][score]"><?php echo $scoreOptions(7) ?></select></td>
                </tr>
            <?php endforeach ?>
            <?php foreach ($doubles as $key => $seed) :?>
                <tr class="game game-doubles">
                    <td>
                        <?php echo implode('&amp;', $seed[0]) ?>-<?php echo implode('&amp;', $seed[1]) ?>
                    </td>
                    <td>
                        <select name="match[<?php echo 50 + $key ?>][player1_id]"><?php echo $playerOptions($players) ?></select>
                        <select name="match[<?php echo 50 + $key ?>][player1a_id]"><?php echo $playerOptions($players) ?></select>
                    </td>
                    <td>
                        <select name="match[<?php echo 50 + $key ?>][player2_id]"><?php echo $playerOptions($players) ?></select>
                        <select name="match[<?php echo 50 + $key ?>][player2a_id]"><?php echo $playerOptions($players) ?></select>
                    </td>
                    <td><select name="match[<?php echo 50 + $key ?>][score]"><?php echo $scoreOptions(7) ?></select></td>
                </tr>
            <?php endforeach ?>
                <tr>
                    <td>SINGLES WON</td>
                    <td class="teama-singles-won"></td>
                    <td class="teamb-singles-won"></td>
                    <td></td>
                </tr>
                <tr>
                    <td>DOUBLES WON</td>
                    <td class="teama-doubles-won"></td>
                    <td class="teamb-doubles-won"></td>
                    <td></td>
                </tr>
                <tr>
                    <td>TOTAL SCORE</td>
                    <td class="teama-total-score"></td>
                    <td class="teamb-total-score"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="form-group">
                            <label>Match Date:</label>
                            <input type="text" name="match_date" placeholder="YYYY-MM-DD" />
                            <button type="submit" class="btn btn-primary submit-match-btn">Submit</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        </form>
    </div>

</div>
<script>
    // http://stackoverflow.com/questions/1184624/convert-form-data-to-js-object-with-jquery
    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    // prepare callback to update scores
    var updateScores = function () {
        var table = $('.results-table');

        // calculate metrices
        var singlesWonA = 0,
            singlesWonB = 0,
            doublesWonA = 0,
            doublesWonB = 0;

        table.find('tr.game').each(function(){
            var scores = $(this).find('td:last select').val().split('-');

            if ($(this).is('game-singles')) {
                singlesWonA += scores[0] > scores[1] ? 1 : 0;
                singlesWonB += scores[0] < scores[1] ? 1 : 0;
            } else {
                doublesWonA += scores[0] > scores[1] ? 1 : 0;
                doublesWonB += scores[0] < scores[1] ? 1 : 0;
            }
        });

        table.find('.teama-singles-won').text(singlesWonA);
        table.find('.teamb-singles-won').text(singlesWonB);
        table.find('.teama-doubles-won').text(doublesWonA);
        table.find('.teamb-doubles-won').text(doublesWonB);
        table.find('.teama-total-score').text(singlesWonA + doublesWonA);
        table.find('.teamb-total-score').text(singlesWonB + doublesWonB);
    };

    // prepare function to clear errors on the scores form
    var clearErrors = function () {
        $('.results-form').find('tr, label').removeClass('err');
    };

    // check form before submit
    $('.submit-match-btn').on('click', function (e) {
        var errors = false,
            form   = $(this).closest('form');

        // clear all errors
        clearErrors();

        // check teams (must be different)
        if (form.find('select[name=team1]').val() == form.find('select[name=team2]').val()) {
            form.find('select[name=team1]').closest('tr').addClass('err');
            errors = true;
        }

        // ensure games have distinct players
        form.find('tr.game-singles').each(function() {
            if ($(this).find('select').eq(0).val() == $(this).find('select').eq(1).val()) {
                $(this).addClass('err');
                errors = true;
            }
        });
        form.find('tr.game-doubles').each(function() {
            var p1 = $(this).find('select').eq(0).val(),
                p2 = $(this).find('select').eq(1).val(),
                p3 = $(this).find('select').eq(2).val(),
                p4 = $(this).find('select').eq(3).val();

            if (p1 == p2 || p1 == p3 || p1 == p4 || p2 == p3 || p2 == p4 || p3 == p4) {
                $(this).addClass('err');
                errors = true;
            }
        });

        // ensure date is filled
        if (!form.find('input[name=match_date]').val()) {
            form.find('input[name=match_date]').closest('.form-group').find('label').addClass('err');
            errors = true;
        }

        if (errors) {
            e.preventDefault();
        }
    });

    // populate results table
    $('.btn.generate-btn').on('click', function (e) {
        e.preventDefault();
        clearErrors();

        var configForm    = $(this).closest('form'),
            playersRoster = configForm.serializeObject(),
            table         = $('.results-table tbody'),
            orderSingles  = [
                ['a', '1'],
                ['b', '2'],
                ['c', '3'],
                ['b', '1'],
                ['a', '3'],
                ['c', '2'],
                ['b', '3'],
                ['c', '1'],
                ['a', '2']
            ],
            orderDoubles = [
                [['b', 'c'], ['2', '3']],
                [['a', 'c'], ['1', '3']],
                [['a', 'b'], ['1', '2']]
            ],
            row;

        // seed singles
        for (row = 0; row < orderSingles.length; row++) {
            var selects  = table.find('tr').eq(row).find('select'),
                orderRow = orderSingles[row];

            // team 1
            selects.eq(0).val(playersRoster['p_' + orderRow[0]]);
            // team 2
            selects.eq(1).val(playersRoster['p_' + orderRow[1]]);
        }

        // seed doubles
        for (row = 0; row < orderDoubles.length; row++) {
            var selects  = table.find('tr').eq(orderSingles.length + row).find('select'),
                orderRow = orderDoubles[row];

            // team 1
            selects.eq(0).val(playersRoster['p_' + orderRow[0][0]]);
            selects.eq(1).val(playersRoster['p_' + orderRow[0][1]]);
            // team 2
            selects.eq(2).val(playersRoster['p_' + orderRow[1][0]]);
            selects.eq(3).val(playersRoster['p_' + orderRow[1][1]]);
        }
    });

    // update scores when score select is changed
    $('.results-table select[name*=score]').on('change', updateScores);

    // initial update
    updateScores();
</script>