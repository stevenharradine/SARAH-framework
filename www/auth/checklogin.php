<?php
	session_start();

	$relative_base_path = '../';

	require_once '../global.php';
	require_once '_model.php';
	
	// Connects to your Database
	$link = mysqli_connect($DB_ADDRESS, $DB_USER, $DB_PASS) or die(mysqli_error());
	mysqli_select_db($link, $DB_NAME) or die(mysqli_error());

	// To protect MySQL injection (more detail about MySQL injection)
	$username = mysqli_real_escape_string($link, stripslashes($_POST['myusername']));
	$password = mysqli_real_escape_string($link, stripslashes($_POST['mypassword']));

	$authManager = new AuthManager();

	$checkLogin = $authManager->checkLogin($username, $password);

	// if a single record was found given the username and password
	if ($checkLogin['count'] == 1) {
		// store  nessasary data to the session
		$_SESSION['USER_ID'] = $checkLogin['USER_ID'];
		$_SESSION['usertype'] = $checkLogin['user_type'];
		$_SESSION['username'] = $username;

		$sessionManager = new sessionManager ($checkLogin['USER_ID'], $checkLogin['user_type'], $username);

		$_SESSION['sessionManager'] = serialize ($sessionManager);
		
		// redriect the client to their requested page (prior to require login)
		header("location:login_success.php" . (isset ($_REQUEST['page']) ? '?page=' . $_REQUEST['page'] : ''));
	} else {	// wrong username or password

		$page_title = 'Error: Wrong Username or Password | Login';
		// load views to be used in front end
//		$views_to_load = array();
//		$views_to_load[] = ' ' . '<p></p>';
		
		include 'login.php';
	}
?> 
