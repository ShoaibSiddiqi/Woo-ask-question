<?php
//	print_r($_POST);
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
	global $wpdb;
    $table_name = $wpdb->prefix . 'ask_question_on_contact';

	$wpdb->insert( 
		$table_name, 
		array( 
			'name' => $_POST['name'], 
			'email' => $_POST['email'],
			'message' => $_POST['message'],
			'product' => $_POST['product'],
			'created_at' => current_time( 'mysql' ),
		) 
	);
	
	if(empty($_POST['name'])) {
		header("Location: /");
	   die();
	}

	$to = 'matt@mansports.com';
	$from = 'hello@shoaibsiddiqi.com';

	$subject = 'Ask Question About Product';
	$successMessage = 'Thank you '.$_POST['name'].' for Getting in Touch, I will get back to you ASAP!';

	$replyTo = $_POST['email'];
	$name = $_POST['name'];
	$message = $_POST['message'];

	$message = '<html><body>';
	$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
	$message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . strip_tags($_POST['name']) . "</td></tr>";
	$message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($_POST['email']) . "</td></tr>";
	$message .= "<tr><td><strong>Product:</strong> </td><td>" . strip_tags($_POST['product']) . "</td></tr>";
	$message .= "<tr><td><strong>NEW Message:</strong> </td><td>" . htmlentities($_POST['message']) . "</td></tr>";
	$message .= "</table>";
	$message .= "</body></html>";

	$headers = "From: ManSports Ask Question Product\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$mail = mail($to, $subject, $message, $headers);

	if ($mail) {
		echo($successMessage);
	} else {
		echo 'Error';
	}
?>