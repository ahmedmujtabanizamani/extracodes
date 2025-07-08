<!DOCTYPE html>

<?php
	if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
	}
?>
<html>
	<head>
		 <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>php demo</title>
	
		<style>
			#tab1{
				border: solid 2px red;
				border-radius: 10px;
				background-color: cyan;
				padding: 20px;
				
			}
			#txt{
				box-shadow: 1px 2px 4px blue;
				padding:5px;
			}
			#txt:focus{
				box-shadow: 1px 2px 4px red;
				background-color:yellow;
			}
			#btn{
				background-color:skyblue;
				color: red;
				border-radius: 50%;
				height: 60px;
				
			}
		
		</style>
	
	</head>
	<body>
		<p><?php echo "php current version/ linux-version: ".PHP_VERSION; ?></p>
		<br>
		<center>
			<form method="GET" action="#">
				<table id="tab1">
					<tr>
						<td>Int to Binary</td>
					</tr>
					<tr>
						<td><input id="txt" type="text" name="numx"></td>
					</tr>
					<tr>
						<td>
								<input id="btn" type="submit" value="convert">
						</td>
					</tr>
				</table>
			</form>
		</center>
		<h1>
			<?php
			$n = 0;
			$n = $_GET["numx"];
			$k = $n;
			$b = "";
			$i = 0;
			
				
			while($n>0){
				if($n%2==0){
					$b = "0".$b;
				}else{
					$b = "1".$b;
				}
				$i++;
				$n=(int)($n/2);
			}
			if($i>0){
				echo $k." in binary = ";
				echo "(".$b.")<sub>2<sup>".$i."</sup></sub>";
			}
			?>
		</h1>
		
		<h1>
			<?php
			$s = "";
			$n = $_GET["numx"];
			$len = strlen($n)-((int)log($n,10))-1;
			
            $i = 0;
            $n/=pow(10,(int)log($n,10)+1);
            
             $n/=pow(10,$len);   //handling precedance zero after decimal
            echo $n. " in binary = ";
           
            while($n!=0 && $i<23)
            {
                $i++;
                $n *= 2;
                $s = $s . ((int)$n);
                if ($n >= 1)
                {
                    $n -= 1;
                }
            }
			echo "(".$s.")<sub>2<sup>".-$i."</sup></sub>";
			
			?>
		</h1>
	
	</body>

</html>
