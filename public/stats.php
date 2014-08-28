<div class="row">
    <div class="col-md-4 col-md-offset-1">
        <h1 class="page-header">Stats Singles</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Player Name</th>
                    <th>Games Played</th>
                    <th>Games Won</th>
                    <th>Games Lost</th>
                    <th>% AVG</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($playerStats as $player): ?>
                <?php
                    $won  = (int) $player['singles_won'];
                    $lost = (int) $player['singles_lost'];

                    // skip if no singles played
                    if ($won + $lost == 0) {
                        continue;
                    }
                ?>
                <tr>
                    <td><?php echo $player['name'] ?></td>
                    <td><?php echo $won + $lost ?></td>
                    <td><?php echo $won ?></td>
                    <td><?php echo $lost ?></td>
                    <td><?php echo $player['singles_avg'] * 100 ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>