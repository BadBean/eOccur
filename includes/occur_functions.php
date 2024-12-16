<?php


function email_exists($email, $conn)
	{
		$result = mysqli_query($conn,"SELECT user_id FROM users WHERE user_email='$email'");
		
		//$numrows = mysqli_num_rows($result);
		//echo $numrows;
		//echo "rows";

		if(mysqli_num_rows($result) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

function logged_in()
{
	if(isset($_SESSION['user_email']))
	{
		return true;
	}
	else
	{
		return false;
	}
}



function rm_access($email,$conn)
{

		$result = mysqli_query($conn,"SELECT user_id FROM users WHERE user_email = '$email' 
				  AND user_access_A LIKE '%risk%'");
		
		//$numrows = mysqli_num_rows($result);
		//echo $numrows;
		//echo "rows";

		if(mysqli_num_rows($result) == 1)

		
		{
			return true;
		}
		else
		{
			return false;
		}
}		
        


?>