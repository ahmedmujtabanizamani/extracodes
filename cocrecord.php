<html>
	<head>
		<meta name="viewport" content="width=device-width, initial scale=1">
		<style>
			body{max-width:800px;margin:0 auto 0 auto;background-color:#E9FCFF; display:block; height: 100vh;}
		
			table,td{text-align:center;border-bottom: solid 1px #C7C7C7; border-collapse:collapse;}
			table{width:100%;}
			tr:nth-child(even){background-color:#D1FFDA;}
			td{text-shadow:1px 1px 4px white;}	
			#footer{font-family: mono; width:auto; margin-top:40px; color:#A6A6A7; text-align:center; font-size:14px;}		
			.header{text-align:center; color:#31FF31; text-shadow: 1px 1px 4px blue; border-bottom: #81FF31 solid 1px;}
		</style>
	</head>
<body>
	<h1 class="header">Pakistan Clan</h1>
<?php

// retreaving data from api...
$ch = curl_init("https://api.clashofclans.com/v1/clans/%23P9LC29LV/members");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Accept: application/json',
  'authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjQ2NzEyZDQ2LTRhZmEtNGNkNi1iODMzLWMwNmRkNzY3ZjZjOSIsImlhdCI6MTYxMjY0MDg1Mywic3ViIjoiZGV2ZWxvcGVyL2FiZTVhNDAyLWU5MWMtMzMwMS0wOTRlLTRmMmFlNzgwMzhmYyIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjM0LjcyLjIyMy4xNDEiXSwidHlwZSI6ImNsaWVudCJ9XX0.vFkadhiwxyBoAtisPvo43NJ_JPNcFFKN1fKuIj-bIUL-ywwotmLsiFQgQ68MFNUUuEEpliU9bs_YDgA5gpXIxw'
]);


$data = json_decode(curl_exec($ch), true);

curl_close($ch);
// api work done now data variable hast assosiative array of member data.

//database variables

$host = "localhost";
$uname = "mujtaba";
$pass = "Mybro.12";
$database = "coc";

//checkpoint database working!
if($conn = mysqli_connect($host, $uname, $pass, $database)){
	echo "*we are live*!";
}else{echo "fail error in system! database could not connected, please contact administrator!";}

//manual data entry using this block, password protected so no one can mess withit
if(isset($_POST["dateitem"]) && isset($_POST["passwd"]) )
{	
	if($_POST["passwd"] == "myclan")
	{
		$date = $_POST["dateitem"];
		$datei = substr($date,0,4).substr($date,5,2).substr($date,8,2);	// encoding date into yyyymmdd formate 
	
		//mysql database stuff below
		if(!mysqli_fetch_assoc(mysqli_query($conn, "select * from donations where date = '".$datei."';")))
		{
			for($i=0; $i<count($data["items"]); $i++)
			{
				$cocname = $data["items"][$i]["name"];
				$cocdonation = $data["items"][$i]["donations"];
				$cocreceived = $data["items"][$i]["donationsReceived"];
				
				if(mysqli_query($conn, 'insert into donations (name, donation, received, date) values ("'.mysqli_real_escape_string($conn,$cocname).'", '.$cocdonation.', ' . $cocreceived .', "'.$datei.'");'))
				{
					echo "record $i added <br>";
				}
				else 
				{ 
					echo "fail";
				}
			}
		}
		else
		{
			echo "already added for this date";
		}
	}
	else
	{
		echo "wronge password! if u are not admin please don't use this.";
	}
}

// todays donation code below.
if( isset($_POST["getDonations"]) )
{
	$date = date('Y-m-d'); 
	$pdate = substr($date,0,4).substr($date,5,2).substr($date,8,2);
	for($i=0; $i<count($data["items"]); $i++)
	{
		$cocname = $data["items"][$i]["name"];
		$cocdonation = $data["items"][$i]["donations"];
		
		//inserting temp data so can calculate current stats.
		if(mysqli_query($conn, 'insert into donations (name, donation, date) values ("'.mysqli_real_escape_string($conn,$cocname).'", '.$cocdonation.', "tmp");'))
		{
			//echo "temp record $i added <br>";
		}
		else 
		{ 
			echo "fail";
		}
	}
	//printing stuff
	if($result = mysqli_query($conn, "SELECT name, Sum(CASE date WHEN 'tmp' THEN donation WHEN '".$pdate."' THEN -donation END) AS today FROM   donations GROUP  BY name order by today desc;"))
	{
		echo "<table>";
		echo "<tr><th>Name</th><th>Donations</th></tr>";
		while($row = mysqli_fetch_assoc($result)){
			if($row["today"] >0)
			{
				echo "<tr><td>".$row["name"]."</td><td>".$row["today"]."</td></tr>";
			}
			elseif($row["today"] <0)
			{
				$sr = mysqli_fetch_assoc(mysqli_query($conn, 'select name, donation from donations where name = "'.$row["name"].'" and date = "tmp";'));
				if($sr["donation"] >0)
				{
					echo "<tr><td>".$sr["name"]."</td><td>".$sr["donation"]."</td></tr>";
				}
			}
		}
		echo "</table>";
	}
	if(mysqli_query($conn, 'delete from donations where date = "tmp";'))
	{
		echo "<br> all done!";
	}
	
}
//================================================================================== get received

if( isset($_POST["getReceived"]) )
{
	$date = date('Y-m-d'); 
	$pdate = substr($date,0,4).substr($date,5,2).substr($date,8,2);
	for($i=0; $i<count($data["items"]); $i++)
	{
		$cocname = $data["items"][$i]["name"];
		$cocdonation = $data["items"][$i]["donationsReceived"];
		
		if(mysqli_query($conn, 'insert into donations (name, received, date) values ("'.mysqli_real_escape_string($conn,$cocname).'", '.$cocdonation.', "tmp");'))
		{
			//echo "temp record $i added <br>";
		}
		else 
		{ 
			echo "fail";
		}
	}
	
	if($result = mysqli_query($conn, "SELECT name, Sum(CASE date WHEN 'tmp' THEN received WHEN '".$pdate."' THEN -received END) AS today FROM   donations GROUP  BY name order by today desc;"))
	{
		echo "<table>";
		echo "<tr><th>Name</th><th>Received</th></tr>";
		while($row = mysqli_fetch_assoc($result)){
			if($row["today"] >0)
			{
				echo "<tr><td>".$row["name"]."</td><td>".$row["today"]."</td></tr>";
			}
			elseif($row["today"] <0)
			{
				$sr = mysqli_fetch_assoc(mysqli_query($conn, 'select name, received from donations where name = "'.$row["name"].'" and date = "tmp";'));
				if($sr["received"] >0)
				{
					echo "<tr><td>".$sr["name"]."</td><td>".$sr["received"]."</td></tr>";
				}
			}
		}
		echo "</table>";
	}
	//deleting temp data from database..
	if(mysqli_query($conn, 'delete from donations where date = "tmp";'))
	{
		echo "<br> all done!";
	}	
}

// ========================================= specific date wise

if(isset($_POST["selecteddate"]) && isset($_POST["getDDonations"]))
{
	$date = $_POST["selecteddate"];
	$date1 = date('Y-m-d', strtotime($date .' +1 day'));
	$cdate = substr($date1,0,4).substr($date1,5,2).substr($date1,8,2);
	
	$date2 = $date;
	$pdate = substr($date2,0,4).substr($date2,5,2).substr($date2,8,2);

	
	if($result = mysqli_query($conn, "SELECT name, Sum(CASE date WHEN '".$cdate."' THEN donation WHEN '".$pdate."' THEN -donation END) AS today FROM   donations GROUP  BY name order by today desc;"))
	{
		echo "<table>";
		echo "<tr><th>Name</th><th>Donations</th></tr>";
		while($row = mysqli_fetch_assoc($result)){
			if($row["today"] >0)
			{
				echo "<tr><td>".$row["name"]."</td><td>".$row["today"]."</td></tr>";
			}
			elseif($row["today"] <0)
			{
				$sr = mysqli_fetch_assoc(mysqli_query($conn, 'select name, donation from donations where name = "'.$row["name"].'" and date = "'.$cdate.'";'));
				if($sr["donation"] >0)
				{
					echo "<tr><td>".$sr["name"]."</td><td>".$sr["donation"]."</td></tr>";
				}
			}
		}
		echo "</table>";
	}
}

if( isset($_POST["selecteddate"]) && isset($_POST["getDReceived"]) )
{
	$date = $_POST["selecteddate"];
	
	$date1 = date('Y-m-d', strtotime($date .' +1 day'));
	$cdate = substr($date1,0,4).substr($date1,5,2).substr($date1,8,2);
	
	$date2 = $date;
	$pdate = substr($date2,0,4).substr($date2,5,2).substr($date2,8,2);
	
	if($result = mysqli_query($conn, "SELECT name, Sum(CASE date WHEN '".$cdate."' THEN received WHEN '".$pdate."' THEN -received END) AS today FROM   donations GROUP  BY name order by today desc;"))
	{
		echo "<table>";
		echo "<tr><th>Name</th><th>Received</th></tr>";
		while($row = mysqli_fetch_assoc($result)){
			if($row["today"] >0)
			{
				echo "<tr><td>".$row["name"]."</td><td>".$row["today"]."</td></tr>";
			}
			elseif($row["today"] <0)
			{
				$sr = mysqli_fetch_assoc(mysqli_query($conn, 'select name, received from donations where name = "'.$row["name"].'" and date = "'.$cdate.'";'));
				if($sr["received"] >0)
				{
					echo "<tr><td>".$sr["name"]."</td><td>".$sr["received"]."</td></tr>";
				}
			}
		}
		echo "</table>";
	}
}
?>

<form action="" method="post">
	<fieldset>
		<legend>Admin Use only: </legend>
		<input name="dateitem" type="date" value="<?php echo date('Y-m-d'); ?>" />
		<input name="passwd" type="password" placeholder="password">
		<input type="submit" value="record">
	</fieldset>
</form>

<form action="" method="post">
	<fieldset>
		<legend>Today's data only! </legend>
		<input type="submit" value="donations" name="getDonations">
		<input type="submit" value="Received" name="getReceived">
	</fieldset>

</form>
<form action="" method="post">
	<fieldset>
		<legend>by specific date:</legend>
		<input name="selecteddate" type="date" min="2021-02-22" max="<?php echo date('Y-m-d', strtotime(date('Y-m-d') .' -1 day')); ?>" value="<?php echo date('Y-m-d', strtotime(date('Y-m-d') .' -1 day')); ?>" />
		<input type="submit" value="donations" name="getDDonations">
		<input type="submit" value="Received" name="getDReceived">
		<br>
		<label>Note! This proccess started from 22th of feb 2021, so before this date no data available.</label>
	</fieldset>
</form>
<div id="footer">
	<div>
		<h4>there is no copy rights,<br>this project is built for Pakistan clan in clash of clans.<br>clan tag ( <b>#P9LC29LV</b>)</h4>
	</div>
</div>
</body>

</html>
