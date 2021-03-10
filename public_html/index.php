<?php
include('../inc/head.php');



//check if game is valid
$isMultiplayer = false;
if(isset($_GET['session'])&&isset($_GET['ourSymbol'])&&isset($_GET['ourColor'])) {
  $id = $_GET['session'];
  $ourSymbol = $_GET['ourSymbol'];
  $ourColor = $_GET['ourColor'];

  if($ourSymbol == "x")
    $ourSymbol = "o";
  else
    $ourSymbol = "x";
  if($ourColor == "red")
    $ourColor = "black";
  else
    $ourColor = "red";

  $GameInfo = $conn->prepare("select * from games where id = :id ");
  $GameInfo->bindParam(":id",$id);
  $GameInfo->execute();

  $count = $GameInfo->rowCount();

  if($count > 0 ){
    $isMultiplayer = true;

    echo <<<HTML
    <script type="text/javascript">
      $(document).ready(()=>{
        ourSymbol = "$ourSymbol"
        ourColor = "$ourColor"
        curMode = 2
        gameID = $id

        $('#thinkText').html("Waiting for other player...")

        function checkForMove() {
            setTimeout(function () {
                // do a get request $.get to server to ask for move variable from SQL
                if (allowWaitForMove) {
                    $.get("/api.php?getCurrentMove_=" + gameID, (move) => {
                        if (move != lastMove) {
                            var theirSymbol = "x"
                            if (ourSymbol == "x")
                                theirSymbol = "o"
                            var theirColor = "red"
                            if (ourColor == "red")
                                theirColor = "black"

                            var arr = move.split("")
                            $(`img[data-cell="`+arr[0]+arr[1]+`"]`).attr("src", `/assets/img/`+theirSymbol+`_`+theirColor+`.png`)
                            lastMove = move

                            checkGameWon()

                            $('#thinking').addClass("d-none")
                        }
                    })
                }

                checkForMove()
            }, 1000)
        }
        checkForMove()
      })
    </script>
HTML;
  }
}

?>

<div class="container">
  <div class="bg-light w-100 mt-5 p-4 py-3 rounded">
    <p class="h1 text-center mb-0 font-weight-bold mt-3">Welcome to Tic Tac Toe</p>
    
    <div id="symbol" <?php if($isMultiplayer) {?>class="d-none"<?php } ?>> 
      <p class="h3 text-center mb-4">Select your Piece!</p>

      <div class="d-flex align-items-center justify-content-center">
        <div id="select_x" class="btn btn-light cursor-pointer px-4 mr-4">
          <p class="display-1 mb-1">X</p>
        </div>
        <div id="select_o" class="btn btn-light cursor-pointer px-4">
          <p class="display-1 mb-1">O</p>
        </div>
      </div>
    </div>
    
    <div id="method" class="d-none">
      <p class="h3 text-center mb-4">Go up against your friend or a robot?</p>

      <div class="row">
        <div class="col-md-4 col-12">
          <div id="pvp" class="btn btn-light cursor-pointer px-4 w-100">
            <p class="h1 mb-1 text-center">Local Play</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div id="robot" class="btn btn-light cursor-pointer px-4 w-100">
            <p class="h1 mb-1 text-center">vsRobot</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div id="multiplayer" class="btn btn-light cursor-pointer px-4 w-100">
            <p class="h1 mb-1 text-center">Multiplayer</p>
          </div>
        </div>
      </div>
    </div>

    <div id="difficulty" class="d-none">
      <p class="h3 text-center mb-4">What difficulty would you like to play at?</p>

      <div class="row">
        <div class="col-md-4 col-12">
          <div id="easy" class="btn btn-light cursor-pointer px-4 w-100">
            <p class="h1 mb-1 text-center">Easy</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div id="medium" class="btn btn-light cursor-pointer px-4 w-100">
            <p class="h1 mb-1 text-center">Medium</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div id="hard" class="btn btn-light cursor-pointer px-4 w-100">
            <p class="h1 mb-1 text-center">Hard</p>
          </div>
        </div>
      </div>
    </div>
  </div>
<div id="gameboard" class="bg-light w-100 my-4 px-4 pt-5 pb-3 rounded align-items-center flex-column <?php if(!$isMultiplayer) {?>d-none<?php } else { ?>d-flex<?php } ?>">
    <div class="bg-danger w-75 position-relative rounded">
      <div id="thinking" class="spinner-wrapper d-none">
        <div class="w-100 d-flex justify-content-center align-items-center flex-column">
        <p id="thinkText" class="text-white h2 font-weight-bold mb-4">Thinking...</p>
          <div class="spinner-grow text-white" role="status" style="width:3rem;height:3rem;"></div>
        </div>
      </div>
      <div class="row">
          <div class="col-4 mb-4 noselect" id="cell00">
              <div class="bg-light p-4 cursor-pointer">
                  <img class="w-100 cell" data-cell="00" src="/assets/img/empty.png">
              </div>
          </div>
          <div class="col-4 mb-4 noselect" id="cell01">
              <div class="bg-light p-4 cursor-pointer">
                  <img class="w-100 cell" data-cell="01" src="/assets/img/empty.png">
              </div>
          </div>
          <div class="col-4 mb-4 noselect" id="cell02">
              <div class="bg-light p-4 cursor-pointer">
                  <img class="w-100 cell" data-cell="02" src="/assets/img/empty.png">
              </div>
          </div>
          <div class="col-4 mb-4 noselect" id="cell10">
              <div class="bg-light p-4 cursor-pointer">
                  <img class="w-100 cell" data-cell="10" src="/assets/img/empty.png">
              </div>
          </div>
          <div class="col-4 mb-4 noselect" id="cell11">
              <div class="bg-light p-4 cursor-pointer">
                  <img class="w-100 cell" data-cell="11" src="/assets/img/empty.png">
              </div>
          </div>
          <div class="col-4 mb-4 noselect" id="cell12">
              <div class="bg-light p-4 cursor-pointer">
                  <img class="w-100 cell" data-cell="12" src="/assets/img/empty.png">
              </div>
          </div>
          <div class="col-4 noselect" id="cell20">
              <div class="bg-light p-4 cursor-pointer">
                  <img class="w-100 cell" data-cell="20" src="/assets/img/empty.png">
              </div>
          </div>
          <div class="col-4 noselect" id="cell21">
              <div class="bg-light p-4 cursor-pointer">
                  <img class="w-100 cell" data-cell="21" src="/assets/img/empty.png">
              </div>
          </div>
          <div class="col-4 noselect" id="cell22">
              <div class="bg-light p-4 cursor-pointer">
                  <img class="w-100 cell" data-cell="22" src="/assets/img/empty.png">
              </div>
          </div>
      </div>
    </div>
    <?php
      if(!isset($_SESSION['uid'])) {
        echo("<p class='text-center mt-4'><i class='fas fa-info-circle fa-fw'></i> You're not logged in, so your stats are not being saved.</p>");
      } else {
        echo("<p class='text-center mt-4'><i class='fas fa-check-double fa-fw'></i> You are logged in and your stats are being saved.</p>");
      }
      ?>
  </div>
  <div class="bg-light w-100 mt-3 mb-5 p-5 py-3 rounded">
    <p class="h2">Rules & Info</p>
    <p>Tic Tac Toe is one of the most basic games in human history and the rules are very simple.
      <ol class="pl-3">
        <li>Each player makes one move at a time.</li>
        <li>
          The following are ways to win:
          <ul>
            <li>3 pieces of the same color across one row</li>
            <li>3 pieces of the same color across one column</li>
            <li>3 pieces of the same color across a diagonal</li>
          </ul>
        </li>
      </ol>
    </p>
  </div>
</div>











<div id="enemy-screen" class="center hidden">
  <h2>Play against:</h2>
  <button type="button" id="choose-human" class="choose">Human</button>
  <button type="button" id="choose-cpu" class="choose">CPU</button>
</div>







</body>