<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!--End of scrappy container-->
</div>

<!--Footer top-->
<div id="footerTop"></div>

<!--Footer contain-->
<div id="footerContain">
	
    <div id="footerContent">
    	
        <div class="floatLeft">
        	For information regarding the legal stuff visit <a href="http://www.fastsellfoods.com/legal_stuff" target="_blank">www.fastsellfoods.com/legal_stuff</a>.<br /> You can also contact us at <a href="mailto:info@fastsellfoods.com" target="_blank">info@fastsellfoods.com</a>
        </div>
        
        <div class="floatRight">
    		No images or content can be used outside of a valid FastSell Subscription.<br />This application is strictly the property of Emerge Studio LLC and Data Connect.
    	</div>
        
        <div class="clearFloat"></div>
    
    </div>

</div>

<!--Base URL-->
<input type="hidden" id="hdPath" name="hdPath" value="<?php echo base_url(); ?>" />
<input type="hidden" id="hdReturnUrl" name="hdPath" value="<?php echo current_url(); ?>" />
<?php
// Get the time
$url_time               = 'timezones/.json';
$call_time              = $this->scrap_web->webserv_call($url_time);
$json_time              = $call_time['result'];
?>
<input type="hidden" id="hdCrtTime" name="hdCrtTime" value="<?php echo substr($json_time->time, 0, 10).' '.substr($json_time->time, 11, 2).':'.substr($json_time->time, 13, 2).':'.substr($json_time->time, 15, 2); ?>" />

<form action="<?php echo base_url(); ?>login" method="post" id="frmLogout"></form>

</body>
</html>