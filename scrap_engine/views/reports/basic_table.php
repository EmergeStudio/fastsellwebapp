<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($report['error'] == FALSE)
{
	// Get the JSON
	$json_report                    = $report['result'];

	// Create the headers
	$ar_headers                     = array();
	foreach($json_report->headers as $header)
	{
		array_push($ar_headers, $header);
	}
	$this->table->set_heading($ar_headers);

	// Create the rows
	foreach($json_report->rows as $row)
	{
		$ar_columns                 = array();

		foreach($row->columns as $column)
		{
			array_push($ar_columns, $column->value);
		}

		$this->table->add_row($ar_columns);
	}

	// Create the table
	echo $this->table->generate();
	if($download_file != FALSE)
	{
		echo hidden_div('yesReport', 'hdReportStatus');
		echo form_open('reports/download_report', 'class="frmDownloadReport"');

			echo form_hidden('hdFilename', $download_file);

		echo form_close();
	}
	else
	{
		echo hidden_div('noReport', 'hdReportStatus');
	}
}
else
{
	echo full_div('', 'messageNoReport');
	echo hidden_div('noReport', 'hdReportStatus');
}
?>