<?php
include('../inc/head.php');

// Check if already logged in
if(isset($_SESSION['uid'])) {
	header('Location: /');
	exit;
}

$noaccount = false;
$badPass = false;
if(isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = $conn->prepare("select id,hash from users where email = :email");
    $check->bindParam(":email",$email);
	$check->execute();
	$arr = $check->fetch(PDO::FETCH_ASSOC);

	if(!isset($arr)||!$arr||count($arr)==0) {
		$noaccount = true;
	}
	
	if(isset($arr) && isset($arr['id']) && isset($arr['hash']) &&password_verify($password, $arr['hash'])) {
		$_SESSION['uid'] = $arr['id'];
		header('Location: /');
	} else {
		$badPass = true;
	}
}
?>

<div class="container">
	<div class="bg-light w-100 mt-5 px-4 py-4 rounded">
		<p class="h3 text-center mb-3">Log In</p>
		<form id="login" action="" method="post">
			<div class="form-group">
				<label for="email">Email address</label>
				<input type="email" value="<?php if(isset($_POST['email'])) echo($_POST['email']); ?>" name="email" class="form-control <?php if($noaccount) echo("is-invalid"); ?>" id="email" aria-describedby="email">
				<div id="email" class="invalid-feedback">
					<?php if($noaccount) {echo("This email is not associated with an account.");} ?>
				</div>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" value="<?php if(isset($_POST['password'])) echo($_POST['password']); ?>" name="password" class="form-control <?php if($noaccount||$badPass) echo("is-invalid"); ?>" id="password" area-describedby="password">
				<div id="password" class="invalid-feedback">
					<?php
					if($noaccount){
						echo('This password is not associated with an account.');
					} else if($badPass) {
						echo('You entered an invalid password.');
					}
					?>
				</div>
			</div>
			<button type="submit" class="btn btn-primary">Log In</button>
		</form>
		<a href="/signup.php" class="mt-4 d-block text-dark">
            <i class="fas fa-info-circle fa-fw"></i> Don't have an account? Sign Up!
		</a>
	</div>
</div>

</body>