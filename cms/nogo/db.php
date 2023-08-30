<?php

// void dbconnect ([string database name [, string user name [, string password [, string server name]]]])

// This function will connect to a MySQL database. If the attempt to connect
// fails, an error message prints out and the script will exit.

function dbconnect ()
{
	global $morpheus, $mylink;

	$dbname		= $morpheus["dbname"];
	$user		= $morpheus["user"];
	$password	= $morpheus["password"];
	$server		= $morpheus["server"];


	$mylink = mysqli_connect($server,$user,$password,$dbname);
	$mylink->set_charset("utf8");
	// print_r($mylink);
	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
}

function dbclose ()
{
	mysqli_close($mylink);
}

// int safe_query ([string query])

// This function will execute an SQL query against the currently open
// MySQL database. If the global variable $query_debug is not empty,
// the query will be printed out before execution. If the execution fails,
// the query and any error message from MySQL will be printed out, and
// the function will return FALSE. Otherwise, it returns the MySQL
// result set identifier.

function safe_query ($query = "")
{
	global	$mylink;
	// echo $query."<br>";
	if (empty($query)) { return FALSE; }
	
	// if (!empty($query_debug)) { print "<pre>$query</pre>\n"; }
	$result = mysqli_query($mylink, $query);
	
	return $result;
}