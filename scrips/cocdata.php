<!--curl -X GET 
--header 'Accept: application/json' 
--header "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjQ2NzEyZDQ2LTRhZmEtNGNkNi1iODMzLWMwNmRkNzY3ZjZjOSIsImlhdCI6MTYxMjY0MDg1Mywic3ViIjoiZGV2ZWxvcGVyL2FiZTVhNDAyLWU5MWMtMzMwMS0wOTRlLTRmMmFlNzgwMzhmYyIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjM0LjcyLjIyMy4xNDEiXSwidHlwZSI6ImNsaWVudCJ9XX0.vFkadhiwxyBoAtisPvo43NJ_JPNcFFKN1fKuIj-bIUL-ywwotmLsiFQgQ68MFNUUuEEpliU9bs_YDgA5gpXIxw" 
'https://api.clashofclans.com/v1/players/%23YLVUP8J2Q'
-->





<?php

$ch = curl_init("https://api.clashofclans.com/v1/clans/%23P9LC29LV/members");
$fp = fopen("images/gm.json", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Accept: application/json',
  'authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjQ2NzEyZDQ2LTRhZmEtNGNkNi1iODMzLWMwNmRkNzY3ZjZjOSIsImlhdCI6MTYxMjY0MDg1Mywic3ViIjoiZGV2ZWxvcGVyL2FiZTVhNDAyLWU5MWMtMzMwMS0wOTRlLTRmMmFlNzgwMzhmYyIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjM0LjcyLjIyMy4xNDEiXSwidHlwZSI6ImNsaWVudCJ9XX0.vFkadhiwxyBoAtisPvo43NJ_JPNcFFKN1fKuIj-bIUL-ywwotmLsiFQgQ68MFNUUuEEpliU9bs_YDgA5gpXIxw'
]);

$dataaa = curl_exec($ch);
if(curl_error($ch)) {
    fwrite($fp, $dataaa);
}
curl_close($ch);
fclose($fp);

?>

<html>
   <head>
      <meta content = "text/html; charset = ISO-8859-1" http-equiv = "content-type">
		
      <script type = "application/javascript">
         function loadJSON() {
            var data_file = "images/gm.json";
            var http_request = new XMLHttpRequest();
            try{
               // Opera 8.0+, Firefox, Chrome, Safari
               http_request = new XMLHttpRequest();
            }catch (e) {
               // Internet Explorer Browsers
               try{
                  http_request = new ActiveXObject("Msxml2.XMLHTTP");
					
               }catch (e) {
				
                  try{
                     http_request = new ActiveXObject("Microsoft.XMLHTTP");
                  }catch (e) {
                     // Something went wrong
                     alert("Your browser broke!");
                     return false;
                  }
					
               }
            }
			
            http_request.onreadystatechange = function() {
			
               if (http_request.readyState == 4  ) {
                  // Javascript function JSON.parse to parse JSON data
                  var jsonObj = JSON.parse(http_request.responseText).items;

                  // jsonObj variable now contains the data structure and can
                  // be accessed as jsonObj.name and jsonObj.country.
               //   document.getElementById("Name").innerHTML = jsonObj[0].name;
                  //document.getElementById("Country").innerHTML = jsonObj[0].trophies;
                 // document.getElementById("badge").innerHTML = '<img src="' + jsonObj[0].league.iconUrls.small + '" >';
                  var len = jsonObj.length;
                  var active = 0;
                  var tdonations=0;
                  var tco = 0;
                  var telder = 0;
                  var leaderName = "";
                  var tmember = 0;
                  
                 var table = document.createElement("table");
					table.id="table1";	
					var i=0;
					
				var row = document.createElement("tr");
							var data = document.createElement("th");
							//data.classList.add("data1");
							data.innerHTML = "League";
							row.appendChild(data);
							var data = document.createElement("th");
							//data.classList.add("data1");
							data.innerHTML = "Name";
							row.appendChild(data);
							
							var data = document.createElement("th");
							//data.classList.add("data1");
							data.innerHTML = "Trophies";
							row.appendChild(data);
							
							var data = document.createElement("th");
							//data.classList.add("data1");
							data.innerHTML = "Donations";
							row.appendChild(data);
							
							table.appendChild(row);				
                  for(i=0; i<len; i++){
						if(jsonObj[i].donations != 0 || jsonObj[i].donationsReceived != 0){
							
							active++;
							var row = document.createElement("tr");
							var data = document.createElement("td");
							//data.classList.add("data1");
							data.innerHTML = '<img width=50% src="' + jsonObj[i].league.iconUrls.small + '" >';
							row.appendChild(data);
							var data = document.createElement("td");
							//data.classList.add("data1");
							if(jsonObj[i].league.name == "Unranked"){
								data.innerHTML = jsonObj[i].name+" <sub>(please join league)</sub>";
							}else{
								data.innerHTML = jsonObj[i].name;
							}
							row.appendChild(data);
							
							var data = document.createElement("td");
							//data.classList.add("data1");
							data.innerHTML = jsonObj[i].trophies;
							row.appendChild(data);
							
							var data = document.createElement("td");
							//data.classList.add("data1");
							data.innerHTML = jsonObj[i].donations;
							
							row.appendChild(data);
							table.appendChild(row);	
							
							//header data
							tdonations += jsonObj[i].donations;
							if( jsonObj[i].role == "admin"){
								telder++;
							}else if(jsonObj[i].role == "coLeader"){
								tco++;
							}else if(jsonObj[i].role == "leader"){
								leaderName = jsonObj[i].name;
							}else if(jsonObj[i].role == "member"){
								tmember++;
							}			
						}
					}
				document.getElementById("totaldonations").innerHTML = tdonations;
				document.getElementById("totalcoleaders").innerHTML = tco;
				document.getElementById("totalelders").innerHTML = telder;
				document.getElementById("leaderName").innerHTML = leaderName;
				document.getElementById("totalmembers").innerHTML = tmember;
				
				document.getElementById("container").removeChild(document.getElementById("container").firstChild);
				document.getElementById("container").appendChild(table); 
				alert("this clan has "+len+" members, but "+(len-active)+" are inactive so those are "+active+" active members showed in this list.");
                 
               }
            }
			
            http_request.open("GET", data_file, true);
            http_request.send();
         }
         
         loadJSON();
         
		
      </script>
      <style>
		body{max-width:800px;margin:0 auto 0 auto;background-color:#E9FCFF;}
		
		table,td{text-align:center;border-bottom: solid 1px #C7C7C7; border-collapse:collapse;}
		table{width:100%;}
		tr:nth-child(even){background-color:#D1FFDA;}
		h1{text-align:center;}
		td{text-shadow:1px 1px 4px white;}
		#header{height:50vh;}
		#header tr:nth-child(even){background-color:#FFFED1;}
		#container{border:white solid 5px;}
      </style>
	
      <title>Pakistan Clan</title>
      <meta name="viewport" content="width=device-width, initial scale=1">
   </head>
	
   <body>
	   
	   <div id="container">
      <table id="header">
		  <tr>
			<td>Total Clan Donations</td><td id="totaldonations"></td>
		  </tr>
		  <tr>
			<td>Leader</td><td id="leaderName"></td>
		  </tr>
		  <tr>
			<td>Co Leaders</td><td id="totalcoleaders"></td>
		  </tr>
		  <tr>
			<td>elders</td><td id="totalelders"></td>
		  </tr>
		  <tr>
			<td>members </td><td id="totalmembers"></td>
		  </tr>
			
      </table>
      <br>
	
     
		
      </div>
		
   </body>
		
</html>





