<?php 
if(function_exists('curl_version') == "Enabled" )
{
	echo 'Curl is enabled';
}
else
{
	echo 'Curl is not enabled';
}
phpinfo(); 
?>