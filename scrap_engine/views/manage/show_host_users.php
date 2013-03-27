<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Main heading
	echo heading('User Accounts', 2, 'class="headingTitle"');

	// Add a user
	echo make_button('Add User', 'blueButton btnAddUser btnHeading', '', 'left');

	// Edit toggle
	echo open_div('editToggle');

		echo form_checkbox('chkboxEdit', 'editStuff', FALSE, 'class="chkboxEdit switch"');

	echo close_div();

	// Clear float
	echo clear_float();

	// White back
	echo open_div('whiteBack singleColumn noPadding');

		if($users['error'] == FALSE)
		{
			// Data
			$json_users                 = $users['result'];

			// Open table
			echo '<table id="flex1">';

				// Each user
				foreach($json_users->show_host_users as $user)
				{
					// User details
					$user_details       = $user->user;

					// Each row
					echo '<tr>';

						// ID
						echo '<td>';

							echo $user_details->id;

						echo '</td>';

						// User image
						echo '<td>';

							$img_properties			= array
							(
								'src'			=> $this->scrap_web->get_profile_image($user_details->id),
								'width'			=> '40',
								'class'			=> 'profileImage'
							);
							echo img($img_properties);

						echo '</td>';

						// Active
						echo '<td class="editIt_state">';

							if($user_details->active == TRUE)
							{
								echo full_span('Active', 'greenTxt');
							}
							else
							{
								echo full_span('Inactive', 'redTxt');
							}

						echo '</td>';

						// First name
						echo '<td id="" class="editIt">';

							echo $user_details->firstname;

						echo '</td>';

						// Last name
						echo '<td class="editIt">';

							echo $user_details->lastname;

						echo '</td class="editIt">';

						// Username
						echo '<td class="editIt">';

							echo $user_details->username;

						echo '</td>';

						// Email address
						echo '<td class="editIt">';

							echo $user_details->user_emails[0]->email;

						echo '</td>';

						// Password
						echo '<td class="editIt password">';

							echo make_link('Change Password');

						echo '</td>';

						// Date created
						echo '<td>';

							echo $this->scrap_string->make_date($user_details->create_date);

						echo '</td>';

						// Delte user
						echo '<td>';

							echo make_link('Delete User', 'btnDeleteUser');
							echo full_span('Are you sure? '.anchor('manage/user_delete/'.$user->id, 'Yes').' / '.make_link('Cancel', 'btnCancel'), 'btnCheck displayNone');

						echo '</td>';

					echo '</tr>';
				}

			// Close table
			echo '</table>';
		}

	// End of white back
	echo close_div();

// End of middle div
echo close_div();
?>