<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Banner
echo open_div('banner');

	echo heading('Move Product Now Before It Drops In Value', 2);
	echo '<p>We\'ve assembled customized metrics for you. Track your sales and gain insight about how your deals are selling</p>';
	echo anchor('signup', 'Sign Up Now', 'class="signup"');

echo close_div();

// Columns
echo open_div('columns');

	echo open_div('column left');

		// Icon
		echo full_div('', 'icon-ticket icon');
		echo heading('Quickly Create FastSells', 3);
		echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nec augue dolor. Duis ac turpis nunc, a sollicitudin velit. Donec nunc nibh, malesuada et faucibus vel, sodales ac arcu. Proin a nunc purus. Mauris vel dolor in neque cursus facilisis. Etiam vel lacus massa, ut rhoncus turpis. Phasellus ligula lectus, iaculis vitae interdum et, convallis pulvinar arcu.</p>';

	echo close_div();

	echo open_div('column middle');

		// Icon
		echo full_div('', 'icon-users icon');
		echo heading('Notify Your Customers', 3);
		echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nec augue dolor. Duis ac turpis nunc, a sollicitudin velit. Donec nunc nibh, malesuada et faucibus vel, sodales ac arcu. Proin a nunc purus. Mauris vel dolor in neque cursus facilisis. Etiam vel lacus massa, ut rhoncus turpis. Phasellus ligula lectus, iaculis vitae interdum et, convallis pulvinar arcu.</p>';

	echo close_div();

	echo open_div('column right');

		// Icon
		echo full_div('', 'icon-basket icon');
		echo heading('Process Orders Online', 3);
		echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nec augue dolor. Duis ac turpis nunc, a sollicitudin velit. Donec nunc nibh, malesuada et faucibus vel, sodales ac arcu. Proin a nunc purus. Mauris vel dolor in neque cursus facilisis. Etiam vel lacus massa, ut rhoncus turpis. Phasellus ligula lectus, iaculis vitae interdum et, convallis pulvinar arcu.</p>';

	echo close_div();

echo close_div();

// iPad iPhone Shit
echo open_div('pricing').open_div('inner');

	// Heading
	echo heading('How Much Does It Cost?', 3);

	// Pricing block
	echo open_div('pricingBlock one');

    	echo full_div('A Price', 'price');

		echo full_div('Lorem ipsum dolor sit amet, consectetur adipiscing elit mauris nec augue dolor duis ac turpis nunc.', 'content');

	echo close_div();

	// Pricing block
	echo open_div('pricingBlock two');

		echo full_div('A Price', 'price');

		echo full_div('Lorem ipsum dolor sit amet, consectetur adipiscing elit mauris nec augue dolor duis ac turpis nunc.', 'content');

	echo close_div();

	echo clear_float();

echo close_div().close_div();

// Sign up block
echo open_div('signupBlock');

	echo anchor('signup', 'Sign Up Now', 'class="signup"');

echo close_div();
?>