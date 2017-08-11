<?php
/*
Plugin Name: Credible Time
Plugin URI:  https://localhost/suman/plugin/pseudo-time.php
Description: Plugin to display shortcode
Version:     1.0.0
Author:      Suman Dhar
Author URI:  codepen.io/sumand
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: credible-time
Domain Path: /languages
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The code that runs during plugin activation.
 */
function activate_credible_time() {
	CredibleTime_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_credible_time() {
	CredibleTime_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_credible_time' );
register_deactivation_hook( __FILE__, 'deactivate_credible_time' );


/**********
 *
 * The code that runs add menu page in the admin dashboard
 *
 **********/

add_action('admin_menu', 'my_plugin_create_menu');

function my_plugin_create_menu() {

	add_menu_page( 'Test Plugin Page', 'Credible Time', 'manage_options', 'credible-time', 'test_init', 'dashicons-clock', 6 );

	//add_options_page( 'Test Plugin Page', 'Credible Time', 'manage_options', 'credible-time', 'test_init', 'dashicons-clock', __FILE__ );
	
	//call register settings function
	add_action( 'admin_init', 'register_my_plugin_settings' );
}

/***************
 *
 * Below is the function which gives you the options page on selecting the plugin
 *
 ***************/


function register_my_plugin_settings() {
	//register our settings
	register_setting( 'my-cool-plugin-settings-group', 'settings' );
	
}

function test_init() {
?>
<div class="wrap">
<h1>Options Page</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr>
        <th><label for="settings">Enter Name</label></th>
        <td><input type="text" name="settings" value="<?php echo esc_attr( get_option('settings') ); ?>" /></td>
        </tr>
       
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php 

echo '<h4>' . _e('Paste the below short-code in any page or posts to display your name in the following Format: ' , 'credible-time') . '<br>'; 
echo '<h4>' . _e('Hello "Your Name Entered from the above box" Today is August 10th, 2017 1:45 PM', 'credible-time') .'<br>';
echo "[my_credible_time]"; //[my_credible_time];

} 


function shortcode_time() {
    $name = esc_attr( get_option('settings') );
    $date = date('F jS, Y g:i A');
    return 'Hello '.$name.' Today Is '.$date;
}
add_shortcode('my_credible_time','shortcode_time');

/**********
 *
 * Internationalizing the code
 *
 **********/


function custom_plugin_setup() {
 
    // Retrieve the directory for the internationalization files
    $languages_dir = get_template_directory() . '/languages';
     
    // Set the theme's text domain using the unique identifier from above
    load_plugin_textdomain('credtime', $languages_dir);
 
} // end custom_theme_setup
add_action('after_plugin_setup', 'custom_plugin_setup');

load_plugin_textdomain('credtime', false, dirname(plugin_basename(__FILE__)) . '/languages/');

/***************
 *
 * To add a shortcode in the text editor
 *
 ****************/

function shortcode_insert_button_script() 
{
    if(wp_script_is("quicktags"))
    {
        ?>
            <script type="text/javascript">
                
                //this function is used to retrieve the selected text from the text editor
                function getSel()
                {
                    var txtarea = document.getElementById("content");
                    var start = txtarea.selectionStart;
                    var finish = txtarea.selectionEnd;
                    return txtarea.value.substring(start, finish);
                }

                //QTags.addButton( id, display, arg1, arg2, access_key, title, priority, instance );

                QTags.addButton( 
                    "code_shortcode", 
                    "credible", 
                    callback
                );

                function callback()
                {
                    var selected_text = getSel();
                    QTags.insertContent("[my_credible_time]" +  selected_text + "[/my_credible_time]");
                }
            </script>
        <?php
    }
}

add_action("admin_print_footer_scripts", "shortcode_insert_button_script");