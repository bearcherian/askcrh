<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->

<?php
require_once("validation.php");

session_start();


//This displays your login form

function index(){

/*
echo "<form action='?act=login' method='post'>" 

."Username: <input type='text' name='username' size='30'><br>"

."Password: <input type='password' name='password' size='30'><br>"

."<input type='submit' value='Login'>"

."</form>"; 
*/

}


//This function will find and checks if your data is correct

function login(){


//Collect your info from login form

$username = $_REQUEST['username'];

$password = $_REQUEST['password'];



//Connecting to database

$connect = mysql_connect("host", "username", "password");

if(!$connect){

die(mysql_error());

}


//Selecting database

$select_db = mysql_select_db("database", $connect);

if(!$select_db){

die(mysql_error());

}


//Find if entered data is correct


$result = mysql_query("SELECT * FROM users WHERE username='$username' AND password='$password'");

$row = mysql_fetch_array($result);

$id = $row['id'];


$select_user = mysql_query("SELECT * FROM users WHERE id='$id'");

$row2 = mysql_fetch_array($select_user);

$user = $row2['username'];


if($username != $user){

die("Username is wrong!");

}



$pass_check = mysql_query("SELECT * FROM users WHERE username='$username' AND id='$id'");

$row3 = mysql_fetch_array($pass_check);

$email = $row3['email'];

$select_pass = mysql_query("SELECT * FROM users WHERE username='$username' AND id='$id' AND email='$email'");

$row4 = mysql_fetch_array($select_pass);

$real_password = $row4['password'];


if($password != $real_password){

die("Your password is wrong!");

}




//Now if everything is correct let's finish his/her/its login


session_register("username", $username);

session_register("password", $password);


echo "Welcome, ".$username." please continue on our <a href=index.php>Index</a>";





}


switch($act){


default;

index();

break;


case "login";

login();

break;


}

?> 
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Login Form</title>
	<link rel="stylesheet" href="css/general.css" type="text/css" media="screen" />
</head>
<body>
	
	<div id="container">
		<h1>Login</h1>
		
	<!--	<?if( isset($_POST['send']) && (!validateName($_POST['name']) || !validateEmail($_POST['email']) || !validatePasswords($_POST['pass1'], $_POST['pass2']) || !validateMessage($_POST['message']) ) ):?>
				<div id="error">
					<ul>
						<?if(!validateName($_POST['name'])):?>
							<li><strong>Invalid Name:</strong> We want names with more than 3 letters!</li>
						<?endif?>
						<?if(!validateEmail($_POST['email'])):?>
							<li><strong>Invalid E-mail:</strong> Stop cowboy! Type a valid e-mail please :P</li>
						<?endif?>
						<?if(!validatePasswords($_POST['pass1'], $_POST['pass2'])):?>
							<li><strong>Passwords are invalid:</strong> Passwords doesn't match or are invalid!</li>
						<?endif?>
						<?if(!validateMessage($_POST['message'])):?>
							<li><strong>Ivalid message:</strong> Type a message with at least with 10 letters</li>
						<?endif?>
					</ul>
				</div>
			<?elseif(isset($_POST['send'])):?>
				<div id="error" class="valid">
					<ul>
						<li><strong>Congratulations!</strong> All fields are OK ;)</li>
					</ul>
				</div>
		<?endif?> -->

		<form method="post" id="customForm" action="">
			<div>
				<label for="name">Name</label>
				<input id="name" name="name" type="text" />
				<span id="nameInfo">Enter twitter id</span>
			</div>
			<div>
				<label for="pass1">Password</label>
				<input id="pass1" name="pass1" type="password" />
				<span id="pass1Info">At least 5 characters: letters, numbers and '_'</span>
			</div>
			
			<div>
				<input id="send" name="send" type="submit" value="Send" />
			</div>
		</form>
	</div>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="validation.js"></script>
</body>
</html>