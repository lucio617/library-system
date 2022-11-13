<?php
	//Connect database
	
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_member.php";
	require "header_member.php";
?>

	
<?php
	if(isset($_POST['editpicture'])){
		//mySQL (Object-oriented)
		$conn = $con;
		$uid=$_SESSION['id'];
		//get uploaded image
		$newpicture=addslashes(file_get_contents($_FILES['picture']['tmp_name']));
		//get uploaded image name
		$newpicture_name=$_FILES['picture']['name'];
		if($newpicture==false){
			echo "<script>alert('Empty field. No update made.')</script>";		
		}
		else{
			$update_picture = "UPDATE member SET user_image='".$newpicture."' WHERE id='$uid'";
			$result_update_picture = $conn->query($update_picture); 
			//$update_picture_name = "UPDATE member SET user_image='".$newpicture_name."' WHERE id='$uid'";
			//$result_update_picture_name = $conn->query($update_picture_name); 
			if($result_update_picture==true)
			{
				$message="Update profile picture success.";
				echo "<script type='text/javascript'>alert('$message');</script>";
				header("Refresh: 0, my_profile.php");				
			}
			else
			{
				$message="Fail to update profile picture. Please try again.";
				echo "<script type='text/javascript'>alert('$message');</script>";
			}
		}
	}		
	if(isset($_POST['editname'])){
		$conn = $con;
		$uid=$_SESSION['id'];
		$newname=$_POST['username'];
		$update_name = "UPDATE member SET name='$newname' WHERE id='$uid'";
		$result_update_name = mysqli_query($conn, $update_name);
		if($result_update_name){
			$_SESSION['name']=$newname;
			$message="Update name success.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			header("Refresh: 0, my_profile.php");
		}
		else{
			$message="Fail to update name. Please try again.";
			echo "<script type='text/javascript'>alert('$message');</script>";
		}
	}
	else if(isset($_POST['editemail'])){
		$conn = $con;
		$uid=$_SESSION['id'];
		$newemail=$_POST['email'];
		$update_email = "UPDATE member SET email='$newemail' WHERE id='$uid'";
		$result_update_email = mysqli_query($conn, $update_email);
		if($result_update_email){
			$message="Update e-mail success.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			header("Refresh: 0, my_profile.php");
		}
		else{
			$message="Fail to update e-mail. Please try again.";
			echo "<script type='text/javascript'>alert('$message');</script>";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Event</title>
	<style type="text/css">
		a:hover{
			font-size: 24px;
		}
		a{
			color: blue;
		}
		form{
			margin-left: 60px;
			margin-top: 15px;
			margin-right: 60px;
		}
		table{
			max-width:750px;
			margin-top:50px;
			margin-bottom:50px;
			margin-left:auto;
			margin-right:auto;
			background-color: silver;
		}
		th{
			font-size: 28px;
			text-align: center;
			padding-top: 20px ;
			width: 50%;
		}
		td, input[type=text], input[type=email]{
			font-family: Times New Roman;
			font-size: 22px;
			text-align: center;
			padding-top: 2px ;
			padding-bottom: 2px ;
		}
		input[type=text]:focus{
            border-color:dodgerBlue;
            box-shadow:0 0 8px 0 dodgerBlue;
		}
		input[type=file]{
			font-family: Times New Roman;
			font-size: 16px;
			text-align: center;
			padding-top: 2px;
			padding-bottom: 2px;
		}
		input[type=text]:focus{
            border-color:dodgerBlue;
            box-shadow:0 0 8px 0 dodgerBlue;
		}
		input[type=submit], input[type=reset]{
			padding: 10px;
			color: black;
			border: none;
			background-color: #66CDAA;
			font-weight: 900;
			font-family: Times New Roman;
			font-size: 20px;
			border-radius: 100px;
			text-align: center;
			width: 120px;
		}
		input[type=text]:focus{
            border-color:dodgerBlue;
            box-shadow:0 0 8px 0 dodgerBlue;
		}
		input[type=submit]:hover, input[type=reset]:hover{
			background-color: #20B2AA;
		}
	</style>
</head>
<body background="image\bg.png">

	<button onclick="topFunction()" id="myBtn" title="Go to top"></button>
	<div id="epicture">
		<form action="edit_profile.php" method="POST" enctype="multipart/form-data">
			<table align="center" cellspacing="20px">
				<tr><th style="text-decoration:wavy ;font-size:40px; font-family:'Times New Roman', Times, serif cursive;background-color:burlywood; border-radius: 20px;"> Edit Profile Picture  </th></tr>
				<!-- <tr><td><img src="image/divide.jpg" height="40%" width="100%" style="opacity: 0.6"></td></tr> -->
				<tr><td>New Profile Picture: <input type="file" name="picture"></td></tr>
				<tr><td colspan="2"><input type="submit" name="editpicture" value="Save">&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" name="cancel" value="Cancel"></td></tr>
			</table>
		</form>
	</div>
	<hr width="auto" size="10" style="background: #808000">
	<div id="ename">
		<form action="edit_profile.php" method="POST">
			<table align="center" cellspacing="20px">
				<tr><th style="text-decoration:crimson ;font-size:40px; font-family:'Times New Roman', Times, serif cursive;background-color:burlywood;border-radius:20px;"> Edit Name </th></tr>
				<!-- <tr><td><img src="image/divide.jpg" height="40%" width="100%" style="opacity: 0.6"></td></tr> -->
				<tr><td>New Name: <input type="text" name="username" size="30" required ></tr>
				<tr><td colspan="2"><input type="submit" name="editname" value="Save">&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" name="cancel" value="Cancel"></td></tr>
			</table>
		</form>
	</div>
	<hr width="auto" size="10" style="background: #808000">
	<div id="eemail">
		<form action="edit_profile.php" method="POST">
			<table align="center" cellspacing="20px">
				<tr><th style="text-decoration:wavy ;font-size:40px; font-family:'Times New Roman', Times, serif cursive;background-color:burlywood;border-radius:20px">  Edit E-mail </th></tr>
				<!-- <tr><td><img src="image/divide.jpg" height="40%" width="100%" style="opacity: 0.6"></td></tr> -->
				<tr><td>New E-mail: <input type="email" name="email" size="30" required ></tr>
				<tr><td colspan="2"><input type="submit" name="editemail" value="Save">&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" name="cancel" value="Cancel"></td></tr>
			</table>
		</form>
	</div>
</body>
</html>