<?php
//ini_set('memory_limit','-1');
// create array from text file
//$array = file('newfile.txt');
// shuffle the array

//echo count($array);
//shuffle ($array);
// overwrite original with new shuffled text file
//file_put_contents('list.txt', implode(PHP_EOL, $array));

$fileName = "newfile.txt";

$fileSize = 6729715200;
$dataLength = 12;

$randomSeed = 10;
$randomIterations = 1000000 * $randomSeed;
$randomPercent = $randomIterations / 100;

$totalRows = $fileSize / $dataLength;

$lastRowPosition = ($totalRows - 1) * $dataLength;

$fileStream = fopen($fileName, 'r+');

for($i = 0; $i < $randomIterations; $i++){

	$randNum1 = rand(0, $totalRows - 1);
	$randNum2 = rand(0, $totalRows - 1);
	$randomPosition1 = $randNum1 * $dataLength;
	$randomPosition2 = $randNum2 * $dataLength;
	
	
	fseek($fileStream, $randomPosition1);
	$tempDataRow1 = fread($fileStream, $dataLength);
	
	fseek($fileStream, $randomPosition2);
	$tempDataRow2 = fread($fileStream, $dataLength);
	
	fseek($fileStream, $randomPosition1);
	fwrite($fileStream, $tempDataRow2);
	
	fseek($fileStream, $randomPosition2);
	fwrite($fileStream, $tempDataRow1);
	
	if( $i % $randomPercent == 0){
		echo "#";
	}
}

echo ftell($fileStream)."\n";
echo fread($fileStream, $dataLength);
//fwrite($fileStream, "Hi");

fclose($fileStream);

?> 