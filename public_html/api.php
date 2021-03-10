<?php
include('../inc/config.php');

// Updat wins
if(isset($_GET['winUpdater_'])) {
    // Check if they're logged in and update their wins by 1
    if(isset($_SESSION['uid'])) {
        // Get wins
        $wins = $conn->prepare("select wins from users where id = :id");
        $wins->bindParam(":id",$_SESSION['uid']);
        $wins->execute();
        $winsNumberFetch = $wins->fetch(PDO::FETCH_ASSOC);
        $winsNumber = $winsNumberFetch['wins'];

        // increment
        $newWins = $winsNumber+1;

        // update the wins with new incremented number
        $addOne = $conn->prepare("update users set wins = :wins where id=:id");
        $addOne->bindParam(":wins",$newWins);
        $addOne->bindParam(":id",$_SESSION['uid']);
        $addOne->execute();
    }
}

// Create session
if(isset($_GET['createSession_'])) {
    //genereate a game id/session
    //insert sql new game
    $create = $conn->prepare("insert into games (move) values('-1')");
    $create->execute();
    
    $id = $conn->lastInsertId();

    echo $id;
}

// Get current move
if(isset($_GET['getCurrentMove_'])) {
    $getMove = $conn->prepare("select move from games where id = :id");
    $getMove->bindParam(":id",$_GET['getCurrentMove_']);
    $getMove->execute();
    $moveArr = $getMove->fetch(PDO::FETCH_ASSOC);

    echo $moveArr['move'];
}

if(isset($_GET['updateMove_']) && isset($_GET['newMove'])) {
    $updateMove = $conn->prepare("update games set move = :move where id = :id");
    $updateMove->bindParam(":id",$_GET['updateMove_']);
    $updateMove->bindParam(":move",$_GET['newMove']);
    $updateMove->execute();
}

?>