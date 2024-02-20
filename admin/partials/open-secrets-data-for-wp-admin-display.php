<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.reignitionllc.com/
 * @since      1.0.0
 *
 * @package    Open_Secrets_Data_For_Wp
 * @subpackage Open_Secrets_Data_For_Wp/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<section>
<h1>Configure the plugin</h1>
<h4>To obtain an API key go to <a href="https://www.opensecrets.org/open-data/api" target="_blank">https://www.opensecrets.org/open-data/api</a></h4>

<form method="post" action="options.php">
    <?php
    settings_fields( 'osd_api_settings' );
    do_settings_sections( 'osd_api_settings' );
    ?>
	<fieldset>
	<label for="osd_api_key">API Key</label>
	<input type="text" id="osd_api_key" name="osd_api_key" value="<?php echo get_option( 'osd_api_key' ) ?>" maxlength="32">
	<label for="osd_base_url">Base URL</label>
	<input type="text" id="osd_base_url" name="osd_base_url" value="<?php echo get_option( 'osd_base_url' ) ?>" maxlength="128" value="https://www.opensecrets.org/api/">
	<label for="osd_cycle">Election cycle</label>
	<input type="number" id="osd_cycle" name="osd_cycle" value="<?php echo get_option( 'osd_cycle' ) ?>" maxlength="4" placeholder="Enter a year">
<!--	<p>Output Type</p>-->
<!--	<input type="radio" name="osd_output_type" --><?php //echo get_option( 'osd_output_type' ) == 'json' ? 'checked="checked"' : '' ?><!-- id="osd_output_type_json" value="json">-->
<!--		<label for="output_type_json">JSON</label>-->
<!--	<input type="radio" name="osd_output_type" --><?php //echo get_option( 'osd_output_type' ) == 'xml' ? 'checked="checked"' : '' ?><!-- id="osd_output_type_xml" value="xml">-->
<!--		<label for="output_type_xml">XML</label>-->
	<input type="submit" value="Save">
	</fieldset>
</form>
<br>
	<p>Read more about <a href="https://www.opensecrets.org/" target="_blank">Open Secrets</a>.</p>
	<p>Have a comment. Let me know <a href="mailto:info@reignition.net?Subject=From%20plugin" target="_blank">info@reignition.net</a>.</p>

</section>