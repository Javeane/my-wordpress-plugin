<?php
/**
 * Admin Class
 *
 * @package my-wordpress-plugin
 */

namespace MyWordPressPlugin\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use MyWordPressPlugin\Includes\Views\Avatar_Upload_Form;
use MyWordPressPlugin\Includes\Models\User;
use WP_Error;

/**
 * Admin class.
 */
class Admin {

    /**
     * Class constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    }

    /**
    * Sanitize the input.
     *
     * @param array $input Contains all settings fields as array keys.
     * @return array
     */
    public function sanitize( $input ) {
        $sanitized_input = array();

        // Sanitize custom_avatar_option field.
        if ( isset( $input['custom_avatar_option'] ) ) {
            $sanitized_input['custom_avatar_option'] = sanitize_text_field( $input['custom_avatar_option'] );
        }

      return $sanitized_input;
    }

    /**
     * Add options page.
     */
    public function add_plugin_page() {
        add_users_page(
            'My WordPress Plugin Settings',
            'My WP Plugin',
            'manage_options',
            'my-wordpress-plugin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback.
     */
    public function create_admin_page() {
        // Set class property.
        $this->options = get_option( 'my_option_name' );
        ?>
        <div class="wrap">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            <?php settings_errors(); ?>
            <form method="post" enctype="multipart/form-data" action="options.php">
                <?php
                    settings_fields( 'my_option_group' );
                    do_settings_sections( 'my-wordpress-plugin' );
                    submit_button();
                ?>
            </form>
            <?php
            $avatar_form = new Avatar_Upload_Form( new User() );
            echo $avatar_form->render();
            ?>
        </div>
        <?php
    }

    /**
     * Register and add settings.
     */
    public function page_init() {
        register_setting(
            'my_option_group', // Option group.
            'my_option_name', // Option name.
            array( $this, 'sanitize' ) // Sanitize callback.
        );

        add_settings_section(
            'setting_section_id', // ID.
            'Settings', // Title.
            array( $this, 'print_section_info' ), // Callback.
            'my-wordpress-plugin' // Page.
        );

        add_settings_field(
            'id_number', // ID.
            'ID Number', // Title.
            array( $this, 'id_number_callback' ), // Callback.
            'my-wordpress-plugin', // Page.
            'setting_section_id' // Section.
        );

        add_settings_field(
            'title', // ID.
            'Title', // Title.
            array( $this, 'custom_avatar_settings_callback' ), // Callback function.
            'custom_avatar_settings', // Page to add the setting to.
            'custom_avatar_section' // Section to add the setting to.
        );
        add_action( 'personal_options_update', array( $this, 'save_custom_avatar_settings' ) ); // Save settings when user updates profile.
        }

    /**
     * Custom avatar settings callback function.
    */
    public function custom_avatar_settings_callback() {
    // Code to display the custom avatar settings.
    }

    /**
    * Save custom avatar settings.
    */
    public function save_custom_avatar_settings() {
    // Code to save the custom avatar settings.
    }

}

In the custom_avatar_settings_callback() function, you can use WordPress functions like get_option() and update_option() to retrieve and update the custom avatar settings. For example:

public function custom_avatar_settings_callback() {
$custom_avatar = get_option( 'custom_avatar' );
?>
<input type="text" name="custom_avatar" value="<?php echo esc_attr( $custom_avatar ); ?>" />
<?php
}

In the save_custom_avatar_settings() function, you can use WordPress functions like update_user_meta() to save the custom avatar settings to the user's meta data. For example:

public function save_custom_avatar_settings() {
$custom_avatar = $_POST['custom_avatar'];
update_user_meta( get_current_user_id(), 'custom_avatar', $custom_avatar );
}

Note that this is just an example and you may need to modify it to suit your specific requirements.