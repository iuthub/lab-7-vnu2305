<?php  

include('connection.php');
$db = new PDO('mysql:host=localhost;dbname=blog', 'vnu', 'xTKBw1M1tDPMuG/E');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$add_account_stmt = $db->prepare('INSERT INTO users(username, fullname, email,password) VALUES(?,?,?,?)');


$notConfirmed = false;
$username = '';
$fullName = '';
$password = '';
$password2 = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_REQUEST['username'];
    $fullName = $_REQUEST['fullname'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['pwd'];
    $password2 = $_REQUEST['confirm_pwd'];
    $passwordIsConfirmed = $password==$password2;
    if(!$passwordIsConfirmed)
    {
        $notConfirmed = !$passwordIsConfirmed;
    }
    else
    {
       $add_account_stmt->execute(array($username, $fullName, $email,$password));
       header('Location: index.php');
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>My Blog - Registration Form</title>
		<link href="style.css" type="text/css" rel="stylesheet" />
	</head>
	
	<body>
		<?php include('header.php'); ?>

		<h2>User Details Form</h2>
		<h4>Please, fill below fields correctly</h4>
		<form action="register.php" method="post">
				<ul class="form">
					<li>
						<label for="username">Username</label>
						<input type="text" name="username" id="username" required/>
					</li>
					<li>
						<label for="fullname">Full Name</label>
						<input type="text" name="fullname" id="fullname" required/>
					</li>
					<li>
						<label for="email">Email</label>
						<input type="email" name="email" id="email" />
					</li>
					<li>
						<label for="pwd">Password</label>
						<input type="password" name="pwd" id="pwd" required/>
					</li>
					<li>
						<label for="confirm_pwd">Confirm Password</label>
						<input type="password" name="confirm_pwd" id="confirm_pwd" required />
                        <p class="error">
                            <?php
                        if($notConfirmed)
                        {
                            print_r("Please confirm password carefully");
                        }
                        ?>
                        </p>
					</li>
					<li>
						<input type="submit" value="Submit" /> &nbsp; Already registered? <a href="index.php">Login</a>
					</li>
				</ul>
		</form>
	</body>
</html>