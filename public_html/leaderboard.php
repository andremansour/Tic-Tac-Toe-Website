<?php
include('../inc/head.php');
?>

<div class="container">
    <div class="bg-light w-100 mt-5 px-4 pt-4 pb-2 rounded">
        <p class="h3"><i class="fas fa-star fa-fw mr-2"></i>Highest Scores Leaderboard</p>
        <p>Find out who has the most wins and who's dominating the Tic Tac Toe space!</p>
        <ul class="list-group">
            <?php
            $leaderboard = $conn->prepare("select username,wins,pfp from users ORDER BY wins DESC LIMIT 10");
            $leaderboard->execute();
            $leaderboardData = $leaderboard->fetchAll(PDO::FETCH_ASSOC);

            foreach ($leaderboardData as $key => $value) {
                $username = $value['username'];
                $wins = $value['wins'];
                $pfp = $value['pfp'];
                echo <<<HTML
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <img width="30px" class="mr-3" src="/assets/img/pfp/$pfp.png">$username
                        </div>
                        <div class="col-6">
                            $wins wins
                        </div>
                    </div>
                </li>
HTML;
            }
            ?>
        </ul>
        <p class="mt-3">
            <i class="fas fa-info-circle fa-fw"></i> A maximum of 10 users with top scores are shown on this leaderboard.
        </p>
    </div>
</div>
    
</body>