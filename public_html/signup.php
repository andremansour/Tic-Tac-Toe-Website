<?php
include('../inc/head.php');

// Check if already logged in
if(isset($_SESSION['uid'])) {
	header('Location: /');
	exit;
}

$doValidation = false;
if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $doValidation = true;

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pfp = $_POST['pfp'];
    if(strlen($username) > 30 || strlen($username) < 1) return;
    if(strlen($email)>255 || strlen($email) < 1) return;
    if(strlen($password)>16 || strlen($password)<8) return;
    if(!isset($pfp)) return;

    $pfp = intval($pfp);

    if($pfp>4||$pfp<1) return;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate random 4 digit username ID
    $randomID = strval(rand(pow(10, 4-1), pow(10, 4)-1));
    $username = trim($username)."#".$randomID;

    $insert = $conn->prepare("insert into users (username,hash,created,email,pfp) values(:username,:hash,NOW(),:email,:pfp)");
    $insert->bindParam(":username",$username);
    $insert->bindParam(":hash",$hashed_password);
    $insert->bindParam(":email",$email);
    $insert->bindParam(":pfp",$pfp);
    $insert->execute();

    $uid = $conn->lastInsertId();

    $_SESSION['uid'] = $uid;

    header('Location: /');
}
?>

<div class="container">
	<div class="bg-light w-100 mt-5 px-4 py-4 rounded">
		<p class="h3 text-center mb-3">Sign Up</p>
		<form action="" method="post">
            <label class="mb-3" for="pfp">Select your favorite profile picture</label>
            <div class="row">
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfp" id="pfp1" value="1" checked required>
                        <label class="form-check-label" for="pfp1">
                            <img src="/assets/img/pfp/1.png" class="w-100">
                        </label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfp" id="pfp2" value="2" required>
                        <label class="form-check-label" for="pfp2">
                            <img src="/assets/img/pfp/2.png" class="w-100">
                        </label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfp" id="pfp3" value="3" required>
                        <label class="form-check-label" for="pfp3">
                            <img src="/assets/img/pfp/3.png" class="w-100">
                        </label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfp" id="pfp4" value="4" required>
                        <label class="form-check-label" for="pfp4">
                            <img src="/assets/img/pfp/4.png" class="w-100">
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group mt-3">
				<label for="username">Username</label>
				<input value="<?php if(isset($_POST['password'])) echo($_POST['username']); ?>" required type="text" name="username" maxlength="30" minlength="1" class="form-control <?php if($doValidation) echo("is-valid"); ?>" id="username">
            </div>
			<div class="form-group">
				<label for="email">Email address</label>
                <input value="<?php if(isset($_POST['password'])) echo($_POST['email']); ?>" required type="email" maxlength="255" minlength="1" name="email" class="form-control <?php if($doValidation) echo("is-valid"); ?>" id="email" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input value="<?php if(isset($_POST['password'])) echo($_POST['password']); ?>" required type="password" minlength="8" maxlength="16" name="password" class="form-control <?php if($doValidation) echo("is-valid"); ?>" id="password">
			</div>
			<button type="submit" class="btn btn-primary">Sign Up</button>
		</form>
		<a href="/login.php" class="mt-4 d-block text-dark">
            <i class="fas fa-info-circle fa-fw"></i> Already have an account? Login!
		</a>
	</div>
</div>

</body>