<?php
// void dbconnect ([string database name [, string user name [, string password [, string server name]]]])

// This function will connect to a MySQL database. If the attempt to connect
// fails, an error message prints out and the script will exit.

function dbconnect ()
{
	global $morpheus;

	$dbname		= $morpheus["dbname"];
	$user		= $morpheus["user"];
	$password	= $morpheus["password"];
	$server		= $morpheus["server"];

	if (!$mylink = mysql_connect($server,$user,$password))
	{
		print "<h3>could not connect to database</h3>\n";
		exit;
	}
	mysql_select_db($dbname);
	// mysql_query("SET names 'utf8'");
}
function dbclose ()
{
	mysql_close($mylink);
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
	global	$query_debug;
#echo $query."<br>";
	if (empty($query)) { return FALSE; }

	if (!empty($query_debug)) { print "<pre>$query</pre>\n"; }
	$result = mysql_query($query)
		or print("ack! query failed: "
			."<li>errorno = ".mysql_errno()
			."<li>error = ".mysql_error()
			."<li>query=".$query
		);
	return $result;
}

// void set_result_variables (int result identifier)

// This function creates global variables using the field names from a
// MySQL data set, setting the values of those variables to the values
// from the first row from that data set.

function set_result_variables ($result)
{
	if (!$result || !mysql_num_rows($result)) { return; }
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	if (!is_array($row))
	{
print $query."<li>no array returned : result=$result row=$row";
		return $result;
	}
	while (list($key,$value) = each($row))
	{
		global $$key;
		$$key = $value;
	}
}

// int fetch_record (string table name [, mixed key [, mixed value]])

// This function will select values from the MySQL table specified by
// the first argument.  If the optional second and third arguments
// are not empty, the select will get the row from that table where
// the column named in the second argument has the value given by
// the third argument.  The second & third arguments may also be
// arrays, in which case the query builds its WHERE clause using
// the values of the second argument array as the table column names
// and the corresponding values of the third argument array as
// the required values for those table columns. If the second and third
// arguments are not empty, the data from the first row returned
// (if any) is set to global variables by the set_result_variables()
// function (see above).

function fetch_record ($table, $key="", $value="")
{
	$query = "select * from $table ";
	if (!empty($key) && !empty($value))
	{
		if (is_array($key) && is_array($value))
		{
			$query .= " where ";
			$and = "";
			while (list($i,$v) = each($key))
			{
				$query .= "$and $v = ".$value[$i];
				$and = " and";
			}
		}
		else
		{
			$query .= " where $key = $value ";
		}
	}
	$result = safe_query($query);
	if (!empty($key) && !empty($value))
	{
		set_result_variables($result);
	}
	return $result;
}

// array db_values_array ([string table name [, string value field [, string label field [, string sort field [, string where clause]]]]])

// This function builds an associative array out of the values in
// the MySQL table specified in the first argument. The data from the column
// named in the second argument will be set to the keys of the array.
// If the third argument is not empty, the data from the column it names
// will be the values of the array; otherwise, the values will be equal
// to the keys. If the third argument is not empty, the data will be
// ordered by the column it names; otherwise, it will be ordered by
// the key column. The optional fourth argument specifies any additional
// qualification for the query against the database table; if it is empty,
// all rows in the table will be retrieved.

// If either the first or second argument is empty, no query is run and
// an empty array is returned.

// The function presumes that whoever calls it knows what they're about -
// e.g., that the table exists, that all the column names are correct, etc.

function db_values_array ($table="", $value_field="", $label_field=""
	, $sort_field="", $where=""
)
{
	$values = array();

	if (empty($table) || empty($value_field)) { return $values; }

	if (empty($label_field)) { $label_field = $value_field; }
	if (empty($sort_field)) { $sort_field = $label_field; }
	if (empty($where)) { $where = "1=1"; }

	$query = "select $value_field
		, $label_field
		from $table
		where $where
		order by $sort_field
	";
	$result = safe_query($query);
	if ($result)
	{
		while (list($value,$label) = mysql_fetch_array($result))
		{
			$values[$value] = $label;
		}
	}
	return $values;
}

?>
