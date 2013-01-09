<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!--End of scrappy container-->
</div>

<!--Footer top-->
<div id="footerTop"></div>

<!--Footer contain-->
<div id="footerContain">
	
    <div id="footerContent">
    	
        <div class="floatLeft">
        	For information regarding the legal stuff visit <a href="http://www.fastsell.com/legal_stuff" target="_blank">www.fastsell.com/legal_stuff</a>.<br /> You can also contact us at <a href="mailto:info@fastsell.com" target="_blank">info@fastsell.com</a>
        </div>
        
        <div class="floatRight">
    		No images or content can be used outside of a valid FastSell Subscription.<br />This application is strictly the property of Emerge Studio LLC.
    	</div>
        
        <div class="clearFloat"></div>
    
    </div>

</div>

<!--Base URL-->
<input type="hidden" id="hdPath" name="hdPath" value="<?php echo base_url(); ?>" />
<input type="hidden" id="hdReturnUrl" name="hdPath" value="<?php echo current_url(); ?>" />
<form action="<?php echo base_url(); ?>login" method="post" id="frmLogout"></form>

</body>
</html>