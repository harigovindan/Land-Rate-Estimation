<?php
  /*$ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://www.99acres.com/property-rates-and-price-trends-in-chennai');
  //curl_setopt($ch, CURLOPT_URL, 'http://www.livechennai.com/Propertyratesinchennai.asp?cls=mymenu1');
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  //echo $data;
  file_put_contents("text.txt", $data);
  curl_close($ch);*/
$search_address= "3, Velachery Rd, Bhuvaneshwari Nagar, Velachery, Chennai, Tamil Nadu 600042, India";
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
//echo $line_number;
$file = "text.txt";
$lines = file($file); 
$rate=0;
$price=$lines[$line_number];
$one=$lines[$line_number+5];
$two=$lines[$line_number+6];
$three=$lines[$line_number+7];
	if(sizeof($price)==1)
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
echo $search = $rate;
?>