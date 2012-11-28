<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
    
<?php


//This function will display the registration form

function register_form(){


$date = date('D, M, Y');

/*echo "<form action='?act=register' method='post'>"

."Username: <input type='text' name='username' size='30'><br>"

."Password: <input type='password' name='password' size='30'><br>"

."Confirm your password: <input type='password' name='password_conf' size='30'><br>"

."Email: <input type='text' name='email' size='30'><br>"

."<input type='hidden' name='date' value='$date'>"

."<input type='submit' value='Register'>"

."</form>";*/


}


//This function will register users data

function register(){


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


//Collecting info

$username = $_REQUEST['username'];

$password = $_REQUEST['password'];

$pass_conf = $_REQUEST['password_conf'];

$email = $_REQUEST['email'];

$date = $_REQUEST['date'];


//Here we will check do we have all inputs filled


if(empty($username)){

die("Please enter your username!<br>");

}


if(empty($password)){

die("Please enter your password!<br>");

}


if(empty($pass_conf)){

die("Please confirm your password!<br>");

}


if(empty($email)){

die("Please enter your email!");

}


//Let's check if this username is already in use


$user_check = mysql_query("SELECT username FROM users WHERE username='$username'");

$do_user_check = mysql_num_rows($user_check);


//Now if email is already in use


$email_check = mysql_query("SELECT email FROM users WHERE email='$email'");

$do_email_check = mysql_num_rows($email_check);


//Now display errors


if($do_user_check > 0){

die("Username is already in use!<br>");

}


if($do_email_check > 0){

die("Email is already in use!");

}


//Now let's check does passwords match


if($password != $pass_conf){

die("Passwords don't match!");

}



//If everything is okay let's register this user


$insert = mysql_query("INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')");

if(!$insert){

die("There's little problem: ".mysql_error());

}


echo $username.", you are now registered. Thank you!<br><a href=login.php>Login</a> | <a href=index.php>Index</a>";


}


switch($act){


default;

register_form();

break;


case "register";

register();

break;


}



?>
 <form action="" method="post" onsubmit="return validateFormOnSubmit(this)" style="width: 845px; height: 277px">
<p align="center">Register Page</p>
	<table align="center" style="width: 42%" >
		<tr>
			<td style="height: 26px; "  colspan="2">Enter your Email Id&nbsp;&nbsp; </td>
			<td style="height: 26px"  colspan="2"><input name="email" type="text" /></td>
		</tr>
		<tr>
			<td  colspan="2">Twitter&nbsp;&nbsp; User Name</td>
			<td  colspan="2"><input name="username" type="text" /></td>
		</tr>
		<tr>
			<td style="width: 147px; height: 23px">Technology<input name="check1" type="checkbox" /></td>
			<td style="width: 147px; height: 23px"><label id="Label1">Programing</label><input name="check2" type="checkbox" /></td>
			<td style="height: 23px"><label id="Label2">Social</label><input name="check3" type="checkbox" /></td>
			<td style="height: 23px">Mobile<input name="check4" type="checkbox" /></td>
		</tr>
		<tr>
			<td style="width: 141px; height: 23px"><label id="Label3">Movies</label><input name="check5" type="checkbox" /></td>
			<td style="width: 124px; height: 23px"><label id="Label4">Games</label><input name="check6" type="checkbox" /></td>
			<td style="height: 23px"><label id="Label5">Politics</label><input name="check7" type="checkbox" /></td>
			<td style="height: 23px"><label id="Label6">Other</label><input name="check8" type="checkbox" /></td>
		</tr>
		<tr>
			<td style="height: 23px; azimuth:center"  colspan="2">
			<input name="Reset1" type="reset" value="reset" style="width: 70px;alignment-adjust: central" /></td>
			<td style="height: 23px" colspan="2">
			<input name="Submit1" type="submit" value="submit" /></td>
		</tr>
	</table>
</form>

    </body>
</html>
 