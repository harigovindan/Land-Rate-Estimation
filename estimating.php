<meta http-equiv="refresh" content="3;url=list.php" />
<style>
.loader {
  border: 16px solid #49A006;
  border-radius: 50%;
  border-top: 16px solid #D1FAB1;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<?php
$array=$_COOKIE['cookie-places'];
$temp=explode("@",$array);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://www.99acres.com/property-rates-and-price-trends-in-chennai');
//curl_setopt($ch, CURLOPT_URL, 'http://www.livechennai.com/Propertyratesinchennai.asp?cls=mymenu1');
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
//echo $data;
file_put_contents("text.txt", $data);
curl_close($ch);
$land_rate = array();
	for($i=1;$i <count($temp); $i++)
		{
			$search_address= $temp[$i];
$myArray = explode(',', $search_address);
//echo $myArray[sizeof($myArray) - 4];
$search      = ltrim($myArray[sizeof($myArray) - 4]);
$line_number = false;
if ($handle = fopen("text.txt", "r")) {
   $count = 0;
   while (($line = fgets($handle,4096)) !== FALSE and !$line_number) {
      $count++;
      $line_number = (strpos($line, $search) !== FALSE) ? $count : $line_number;
   }
   fclose($handle);
}
$file = "text.txt";
$lines = file($file); 
$rate=0;
$price=$lines[$line_number];
$one=$lines[$line_number+5];
$two=$lines[$line_number+6];
$three=$lines[$line_number+7];
	if($price=="-")
		{
			if($one!="-")
			{	
				$rate = $one;
			}
			else if($two!="-")
			{	
				$rate = $two;
			}
			else if($three!="-")
			{	
				$rate = $three;
			}
		}
	else
		{
			$rate=$price;
		}
$land_rate[$i]=$rate;	
}
$final_rate=implode("@",$land_rate);
//echo $final_rate;
setcookie("cookie-places", "", time() - 3600);
setcookie("final_rate",$final_rate,time()+3600);
//echo $_COOKIE['final_rate'];
?>
<body bgcolor="#ECFDDF">
<center><font size="5"><b>Please wait while we are estimating the land rate...</b></font></center><br/><br/>
<center><div class="loader"></div></center>
</body>