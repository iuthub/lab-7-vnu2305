<?php
include('connection.php');
error_reporting(1);
session_start();


if (isset($_GET['logout'])&&$_GET['logout']=='1')
{
        session_unset();
        session_destroy();
}

$db = new PDO('mysql:host=localhost;dbname=blog', 'vnu', 'xTKBw1M1tDPMuG/E');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$get_accounts_stmt = $db->prepare('SELECT id,username,password FROM users');
$get_accounts_stmt->execute();
$rows = $get_accounts_stmt->fetchAll();

$username = '';
$password = '';
$rememberME = isset($_POST['remember'])?$_POST['remember']:'';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $authName = isset($_POST["username"])?$_POST["username"]:'';
    $authPwd = isset($_POST["pwd"])?$_POST["pwd"]:'';

    foreach ($rows as $row) {
    if ($row["username"] == $authName && $row["password"] == $authPwd) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['ID'] = $row['id'];
    }
    else
    {
        print_r("The user does not exist,try again at: /index.php");
        error_reporting(0);
    }
    }
    if($rememberME)
    {
        setcookie("username",isset($_POST["username"])?$_POST["username"]:'',time()+60*60*24*365);
        setcookie("password",isset($_POST["pwd"])?$_POST["pwd"]:'',time()+60*60*24*365);
    }
    else
    {
        setcookie("username",isset($_POST["username"])?$_POST["username"]:'',time()-1);
        setcookie("password",isset($_POST["pwd"])?$_POST["pwd"]:'',time()-1);
    }


    $foreignKey = isset($_SESSION['ID'])?$_SESSION['ID']:'';
    $title = isset($_POST['title'])?$_POST['title']:'';
    $body = isset($_POST['body'])?$_POST['body']:'';
    $date = date("Y-m-d");

    setcookie("id",$foreignKey,time()+3600);

    if($body!=''&&$title!='')
    {
        $make_post_stmt = $db->prepare('INSERT INTO posts(title,body,publishDate,userId) VALUES(?,?,?,?)');
        $make_post_stmt->execute(array($title, $body, $date,$foreignKey));
    }


    $get_posts_stmt = $db->prepare('SELECT u.fullname,p.publishDate,p.title,p.body FROM posts p JOIN users u on p.userId=u.id WHERE u.id ='.isset($_SESSION['ID'])?$_SESSION['ID']:'');
    $get_posts_stmt->execute();
    $rows1 = $get_posts_stmt->fetchAll();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>My Personal Page</title>
		<link href="style.css" type="text/css" rel="stylesheet" />
	</head>
	
	<body>
		<?php include('header.php'); ?>
        <?php
        if(!isset($_SESSION['username']))
        { ?>
		<!-- Show this part if user is not signed in yet -->
		<div class="twocols">
			<form action="index.php" method="post" class="twocols_col">
				<ul class="form">
					<li>
						<label for="username">Username</label>
						<input type="text" name="username" id="username" />
					</li>
					<li>
						<label for="pwd">Password</label>
						<input type="password" name="pwd" id="pwd" />
					</li>
					<li>
						<label for="remember">Remember Me</label>
						<input type="checkbox" name="remember" id="remember" value="true" checked />
					</li>
					<li>
						<input type="submit" value="Submit" /> &nbsp; Not registered? <a href="register.php">Register</a>
					</li>
				</ul>
			</form>
			<div class="twocols_col">
				<h2>About Us</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur libero nostrum consequatur dolor. Nesciunt eos dolorem enim accusantium libero impedit ipsa perspiciatis vel dolore reiciendis ratione quam, non sequi sit! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio nobis vero ullam quae. Repellendus dolores quis tenetur enim distinctio, optio vero, cupiditate commodi eligendi similique laboriosam maxime corporis quasi labore!</p>
			</div>
		</div>
		<?php } ?>
        <?php
        if(isset($_SESSION['username']))
        { ?>
            <!-- Show this part after user signed in successfully -->
            <div class="logout_panel"><a href="register.php">My Profile</a>&nbsp;|&nbsp;<a href="index.php?logout=1">Log Out</a></div>
            <h2 class="newPost">New Post</h2>
            <form action="index.php" method="post">
                <ul class="form">
                    <li>
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" />
                    </li>
                    <li>
                        <label for="body">Body</label>
                        <textarea name="body" id="body" cols="30" rows="10"></textarea>
                    </li>
                    <li>
                        <input type="submit" value="Post" />
                    </li>
                </ul>
            </form>
            <div class="onecol">
                <div class="card">
                    <?php
                    if (isset($rows1)) {
                        foreach ($rows1 as $row1)
                        {
                        ?>
                        <h2><?=$row1['title']?></h2>
                        <h5><?=$row1['fullname']?>, <?=$row1['publishDate']?></h5>
                        <p>Some text..</p>
                        <p><?=$row1['body']?></p>
                    <?php }
                    } ?>
                </div>
            </div>
        <?php } ?>
	</body>
</html>