<?php

$ch = curl_init("https://api.clashofclans.com/v1/clans/%23P9LC29LV/members");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Accept: application/json',
  'authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjQ2NzEyZDQ2LTRhZmEtNGNkNi1iODMzLWMwNmRkNzY3ZjZjOSIsImlhdCI6MTYxMjY0MDg1Mywic3ViIjoiZGV2ZWxvcGVyL2FiZTVhNDAyLWU5MWMtMzMwMS0wOTRlLTRmMmFlNzgwMzhmYyIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjM0LjcyLjIyMy4xNDEiXSwidHlwZSI6ImNsaWVudCJ9XX0.vFkadhiwxyBoAtisPvo43NJ_JPNcFFKN1fKuIj-bIUL-ywwotmLsiFQgQ68MFNUUuEEpliU9bs_YDgA5gpXIxw'
]);

$data = json_decode(curl_exec($ch), true);

curl_close($ch);

$host = "localhost";
$uname = "mujtaba";
$pass = "Mybro.12";
$database = "coc";

if($conn = mysqli_connect($host, $uname, $pass, $database)){
	echo "*we are live*!";
}else{echo "fail error in system please contact administrator!";}


$date = date('Y-m-d');
$datei = substr($date,0,4).substr($date,5,2).substr($date,8,2);

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
?>
