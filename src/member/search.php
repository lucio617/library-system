<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_member.php";
	require "header_member.php";
?>

<html>
	<head>
		<title>Welcome</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="css/home_style.css">
		<link rel="stylesheet" type="text/css" href="../css/custom_radio_button_style.css">
		<link rel="stylesheet" type="text/css" href="C:\xampp\htdocs\Library-master\src\member\css\home_style.css">
	</head>
	<body>
		<!--Search event form-->
	<div class="search" style="text-align: center">
		<form action="search.php" method="POST" >
			<input type="text" style="border-radius:12px; background-color:lightcyan; height: 25px;" size="50" name="searchevent" placeholder="Enter book name to search book" style="font-size: 16px;padding: 10px" />
			<input type="submit" style=" border-radius: 12px;" class="search_button" name="search" value="Search"/>
		</form>
	</div>
	<hr width="auto" size="4" style="background: #808000">
	
	<?php
			$searchkey='-';
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			if(((isset($_POST['search'])) && ($_POST['searchevent'] != ""))){
				$searchkey=$_POST['searchevent'];
			}
			
			$query = $con->prepare("SELECT * FROM book where title like '$searchkey%'");
			$query->execute();
			$result = $query->get_result();
			if(!$result)
				die("ERROR: Couldn't fetch books");
			$rows = mysqli_num_rows($result);
			
			
				echo "<form class='cd-form' method='POST' action='search.php'>";
				echo "<legend>Available books</legend>";
				echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
				echo "<table width='100%' cellpadding=10 cellspacing=10>";
				echo "<tr>
						<th></th>
						<th>ISBN<hr></th>
						<th>Title<hr></th>
						<th>Author<hr></th>
						<th>Category<hr></th>
						<th>Price<hr></th>
						<th>Copies available<hr></th>
					</tr>";
				for($i=0; $i<$rows; $i++)
				{
					$row = mysqli_fetch_array($result);
					echo "<tr>
							<td>
								<label class='control control--radio'>
									<input type='radio' name='rd_book' value=".$row[0]." />
								<div class='control__indicator'></div>
							</td>";
					for($j=0; $j<6; $j++)
						if($j == 4)
							echo "<td>$".$row[$j]."</td>";
						else
							echo "<td>".$row[$j]."</td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<br /><br /><input type='submit' name='m_request' value='Request book' />";
				echo "</form>";
			
			
			if(isset($_POST['m_request']))
			{
				if(empty($_POST['rd_book']))
					echo error_without_field("Please select a book to issue");
				else
				{
					$query = $con->prepare("SELECT copies FROM book WHERE isbn = ?;");
					$query->bind_param("s", $_POST['rd_book']);
					$query->execute();
					$copies = mysqli_fetch_array($query->get_result())[0];
					if($copies == 0)
						echo error_without_field("No copies of the selected book are available");
					else
					{
						$query = $con->prepare("SELECT request_id FROM pending_book_requests WHERE member = ?;");
						$query->bind_param("s", $_SESSION['username']);
						$query->execute();
						if(mysqli_num_rows($query->get_result()) == 1)
							echo error_without_field("You can only request one book at a time");
						else
						{
							$query = $con->prepare("SELECT book_isbn FROM book_issue_log WHERE member = ?;");
							$query->bind_param("s", $_SESSION['username']);
							$query->execute();
							$result = $query->get_result();
							if(mysqli_num_rows($result) >= 3)
								echo error_without_field("You cannot issue more than 3 books at a time");
							else
							{
								$rows = mysqli_num_rows($result);
								for($i=0; $i<$rows; $i++)
									if(strcmp(mysqli_fetch_array($result)[0], $_POST['rd_book']) == 0)
										break;
								if($i < $rows)
									echo error_without_field("You have already issued a copy of this book");
								else
								{
									$query = $con->prepare("SELECT balance FROM member WHERE username = ?;");
									$query->bind_param("s", $_SESSION['username']);
									$query->execute();
									$memberBalance = mysqli_fetch_array($query->get_result())[0];
									
									$query = $con->prepare("SELECT price FROM book WHERE isbn = ?;");
									$query->bind_param("s", $_POST['rd_book']);
									$query->execute();
									$bookPrice = mysqli_fetch_array($query->get_result())[0];
									if($memberBalance < $bookPrice)
										echo error_without_field("You do not have sufficient balance to issue this book");
									else
									{
										$query = $con->prepare("INSERT INTO pending_book_requests(member, book_isbn) VALUES(?, ?);");
										$query->bind_param("ss", $_SESSION['username'], $_POST['rd_book']);
										if(!$query->execute())
											echo error_without_field("ERROR: Couldn\'t request book");
										else
											echo success("Book successfully requested. You will be notified by email when the book is issued to your account");
									}
								}
							}
						}
					}
				}
			}
		?>