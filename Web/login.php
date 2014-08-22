<?php
require_once ("defs.php");

$otap_user = unserialize(VALID_OTAP_USER);

if (isset($_POST["username"]) && !empty($_POST["username"])) {
	if (preg_match("/^nusstf\\\\[a-zA-Z].*/", strtolower($_POST["username"])) || preg_match("/^nusstu\\\\[a-zA-Z].*/", strtolower($_POST["username"]))) {
		$username = explode("\\", filter_var(strtolower($_POST["username"]) , FILTER_SANITIZE_STRING));
        
        if (in_array($username[1], $otap_user)) {
            switch ($username[0])
                {
            case "nusstf":
                ldap_login("ldapstf.nus.edu.sg", $username[1], $_POST['password']);
                break;

            case "nusstu":
                ldap_login("ldapstu.nus.edu.sg", $username[1], $_POST['password']);
                break;
                }
            } else {
                echo "Please input the corret user name";
            }
	} else {
        echo "Please use the correct domain name";
    }
} else {
    echo "Wrong username: " . $_POST["username"];
}

function ldap_login($ldapUrl, $username, $password)
	{
	require_once ("adLDAP.php");

	// connect to ldap server

	$ldapconn = ldap_connect($ldapUrl) or die("Could not connect to LDAP server.");
	function exception_error_handler($errno, $errstr, $errfile, $errline)
		{
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		}

	set_error_handler("exception_error_handler");
	try
		{
		if ($ldapconn)
			{

			// binding to ldap server

			$ldapbind = ldap_bind($ldapconn, $_POST['username'], $_POST['password']);

			// verify binding

			if ($ldapbind)
				{ //log them in!
				echo "true";
                session_start();
                $_SESSION['secret_id']=SECRET_ID;
				}
			}
		}

	catch(ErrorException $e)
		{

		// echo "Message: ".$e->getMessage();

		echo "Login Failed...";
		}
	}
