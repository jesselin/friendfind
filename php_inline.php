<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>

<!--Here is all straight php with html tags inside it-->
<?php
	$query_friends = new Process();
	$friends= $query_friends->displayAllRegisteredUsers();

	foreach($friends as $friend)
	{			 
		echo "<td>". $friend['first_name'] ." </td> 
			  <td>". $friend['last_name'] ."</td>
			  <td>
				<form id='subscribers' action = 'process.php' method = 'post'>
					<input type='hidden' name='action' value='add_friend'/>
					<input type='hidden' name='user_id' value='" . $_SESSION['user']['id'] ."' />
					<input type='hidden' name='friend_id' value='". $friend['id'] ."' />
				<input type='submit' value='add as friend'/>
				</form>
			  </td>";
?>

<!--here is with php inline tag where we seperate the opening and closing of foreach into another php tage-->
<?php
	$query_friends = new Process();
	$friends= $query_friends->displayAllRegisteredUsers();

	foreach($friends as $friend)
	{	?>		 
		<td><?= $friend['first_name'] ?></td> 
		<td><?= $friend['last_name'] ?></td>
		<td>
			<form id="subscribers" action = "process.php" method = "post">
			<input type='hidden' name='action' value='add_friend'/>
			<input type='hidden' name='user_id' value="<?= $_SESSION['user']['id'] ?>" />
			<input type='hidden' name='friend_id' value="<?= $friend['id'] ?>" />
			<input type='submit' value='add as friend'/>
			</form>
		</td>
<?php}	?>

<!--
	Basically this would have the same output and results. no difference. however check how we have multiple double quotes 
	and single quotes in 1rst example. this is prone to error as we need to concatinate every single variable to html tags
	in our 2nd example we dont concantenate our variable in html tag,instead we put it into its seperate script(php inline tag <?= ?>)
	no need to put double quotes, php can already read this as single variable.
	
	I think the point is, if you have multiple html tags inside php statement the 2nd approach is less prone to errors. php is very sensitive
	with single and double quotes. mixing html tags with php variable can be troublesome specially when dealing with a lot of html tags inside 
	php scripts, just think of concatenating a lot of variables.
	
	to explain the 2nd approach, we open php script from line 29 to then close it in line 34. then we open it again in line 45, closing it in
	the same line.
 -->
</body>
</html>