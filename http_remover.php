<? 
/*
 Copyright (c) 2019 Fernando
 Url: https://github.com/dlfernando/
 License: MIT

 This PHP script displays a form where you can submit string and will remove
 first ocurrence of substring (http:// , http:// , http://www. , https://www.)
*/
if(isset($_POST['downloadtxt'])){
	header('Content-type: text/plain');
	header('Cache-Control: must-revalidate');	
	header('Expires: 0');
	header('Pragma: public');	
	header('Content-Disposition: attachment; filename="output.txt"');
	readfile('output.txt');	
} else{

	if(isset($_POST['submitForm'])){
	    $output=""; $type="";
		$textAr = explode("\r\n", $_POST['mystring']);		
		foreach ($textAr as $line) {
			preg_match('%(https?://www\.)|(https?://)%i', $line, $result);
			if (isset($result[1])&&($result[1]!="")){ // TYPE 0: http?:// or TYPE 1: http?://www.
				$type=1;
			} else if(isset($result[0])&&($result[0]!="")){$type=0;}
			if(($type==0)||($type==1)){
				$remove=substr( $result[$type], 0, strlen($result[$type]) );
				$remove = '/'.preg_quote($remove, '/').'/';
				$output .= preg_replace($remove, "", $line, 1)."\r\n";
			}	
		}
		if(($type==0)||($type==1)){
			$fp = fopen('output.txt', 'w');
			fwrite($fp, $output);
			fclose($fp);
		}		
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}

.container {
  border-radius: 15px;
  font-family: Verdana, Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  background-color: #f2f2f2;
  padding: 20px;
  align-content: center;
}

input[type=text], textarea {
  width: 100%;
  padding: 12px;
  border: 2px solid #cccccc;
  border-radius: 14px;
  resize: vertical;
}


input[type=submit] {
  background-color: #2e31f2;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  padding: 12px 20px;
}

input[type=submit]:hover {
  background-color: #00a4fc;
}

@media screen and (max-width: 600px) {
   input[type=submit] {
    margin-top: 0;	   
    width: 80%;
  }
}
</style>
</head>
<body>
<div class="container" align=center>
<br><br>
<form action="" method="post">
<div class="row">Batch urls editor. Paste one url per line.</div><br>
<font size=1>Removes first ocurrence per line of (http:// , https:// , http://www. , https://www.)</font>
<br>
<textarea rows="8" style="width:80%;" name="mystring">
<?php if(isset($_POST['mystring'])){echo $_POST['mystring'];}?>
</textarea>
<br><input type="submit" name=submitForm value="Modify">
</form>
<br><br>
<? if(isset($_POST['submitForm'])){ ?>
<form action="" method="post">
<input type="hidden" id="downloadtxt" value="1" name="downloadtxt"> 
<div class="row">Output result:</div>
<br>
<textarea rows="8" style="width:80%;">
<?php echo $output;?>
</textarea>
<br><input type="submit" name=submitForm value="Download">
</form>
<? } else{ ?>
<b>Example input:</b>
<br><br>
<font color=blue>https://</font>google.com/?goto=http://www.disneylandparis.com<br>
<font color=blue>http://www.</font>bbc.com/?link=https://bing.com<br><br>
<b>Example output:</b>
<br><br>
google.com/?goto=http://www.disneylandparis.com<br>
bbc.com/?link=https://bing.com
<? } ?>
</div>
</body>
</html>
<? } ?>