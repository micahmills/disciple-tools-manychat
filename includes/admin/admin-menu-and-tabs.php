<?php
/**
 * DT_Manychat_Menu class for the admin page
 *
 * @class       DT_Manychat_Menu
 * @version     0.1.0
 * @since       0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}

/**
 * Initialize menu class
 */
DT_Manychat_Menu::instance();

/**
 * Class DT_Manychat_Menu
 */
class DT_Manychat_Menu {

    public $token = 'dt_manychat';

    private static $_instance = null;

    /**
     * DT_Manychat_Menu Instance
     *
     * Ensures only one instance of DT_Manychat_Menu is loaded or can be loaded.
     *
     * @since 0.1.0
     * @static
     * @return DT_Manychat_Menu instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()


    /**
     * Constructor function.
     * @access  public
     * @since   0.1.0
     */
    public function __construct() {

        add_action( "admin_menu", array( $this, "register_menu" ) );

    } // End __construct()


    /**
     * Loads the subnav page
     * @since 0.1
     */
    public function register_menu() {
        add_menu_page( __( 'Extensions (DT)', 'disciple_tools' ), __( 'Extensions (DT)', 'disciple_tools' ), 'manage_dt', 'dt_extensions', [ $this, 'extensions_menu' ], 'dashicons-admin-generic', 59 );
        add_submenu_page( 'dt_extensions', __( 'ManyChat', 'dt_manychat' ), __( 'ManyChat', 'dt_manychat' ), 'manage_dt', $this->token, [ $this, 'content' ] );
    }

    /**
     * Menu stub. Replaced when Disciple Tools Theme fully loads.
     */
    public function extensions_menu() {}

    /**
     * Builds page contents
     * @since 0.1
     */
    public function content() {

        if ( !current_user_can( 'manage_dt' ) ) { // manage dt is a permission that is specific to Disciple Tools and allows admins, strategists and dispatchers into the wp-admin
            wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.' ) );
        }

        if ( isset( $_GET["tab"] ) ) {
            $tab = sanitize_key( wp_unslash( $_GET["tab"] ) );
        } else {
            $tab = 'general';
        }

        $link = 'admin.php?page='.$this->token.'&tab=';

        ?>
        <div class="wrap">
            <h2><?php esc_attr_e( 'ManyChat', 'dt_manychat' ) ?></h2>
            <h2 class="nav-tab-wrapper">
                <a href="<?php echo esc_attr( $link ) . 'general' ?>" class="nav-tab <?php ( $tab == 'general' || ! isset( $tab ) ) ? esc_attr_e( 'nav-tab-active', 'dt_manychat' ) : print ''; ?>"><?php esc_attr_e( 'General', 'dt_manychat' ) ?></a>
                <a href="<?php echo esc_attr( $link ) . 'second' ?>" class="nav-tab <?php ( $tab == 'second' ) ? esc_attr_e( 'nav-tab-active', 'dt_manychat' ) : print ''; ?>"><?php esc_attr_e( 'Second', 'dt_manychat' ) ?></a>
            </h2>

            <?php
            switch ($tab) {
                case "general":
                    $object = new DT_Manychat_Tab_General();
                    $object->content();
                    break;
                case "second":
                    $object = new DT_Manychat_Tab_Second();
                    $object->content();
                    break;
                default:
                    break;
            }
            ?>

        </div><!-- End wrap -->

        <?php
    }
}

/**
 * Class DT_Manychat_Tab_General
 */
class DT_Manychat_Tab_General
{
    public function content() {
        ?>
        <div class="wrap">
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <!-- Main Column -->

                        <?php $this->main_column() ?>

                        <!-- End Main Column -->
                    </div><!-- end post-body-content -->
                    <div id="postbox-container-1" class="postbox-container">
                        <!-- Right Column -->

                        <?php $this->right_column() ?>

                        <!-- End Right Column -->
                    </div><!-- postbox-container 1 -->
                    <div id="postbox-container-2" class="postbox-container">
                    </div><!-- postbox-container 2 -->
                </div><!-- post-body meta box container -->
            </div><!--poststuff end -->
        </div><!-- wrap end -->
        <?php
    }

    public function main_column() {
        ?>

        <?php
        $post_ids = Site_Link_System::get_list_of_sites_by_type( [ 'manychat' ], 'post_ids' );
        if ( ! empty( $post_ids ) ) {
            foreach ( $post_ids as $post_id ) {
                ?>
                <!-- Box -->
                <table class="widefat striped">
                    <thead>
                    <th>Available Link</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            URL: <?php echo rest_url() . 'dt-public/v1/manychat/'; ?><br><br>
                            Token: <?php echo get_post_meta( $post_id, 'token', true ); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <!-- End Box -->
                <?php
            }

        }
        else {
            ?>
            <!-- Box -->
            <table class="widefat striped">
                <thead>
                <th>Setup Instructions</th>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <?php print '<pre>'; print_r( get_transient('manychat' ) ); print '</pre>'; ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            <!-- End Box -->
            <?php
        }

        print '<pre>';
        print_r(get_transient('manychat'));
        print '</pre>';
    }

    public function right_column() {
        ?>
        <!-- Box -->
        <table class="widefat striped">
            <thead>
            <th>Information</th>
            </thead>
            <tbody>
            <tr>
                <td>
                    Content
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->
        <?php
    }

}

/**
 * Class DT_Manychat_Tab_Second
 */
class DT_Manychat_Tab_Second
{
    public function content() {
        ?>
        <div class="wrap">
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <!-- Main Column -->

                        <?php $this->main_column() ?>

                        <!-- End Main Column -->
                    </div><!-- end post-body-content -->
                    <div id="postbox-container-1" class="postbox-container">
                        <!-- Right Column -->

                        <?php $this->right_column() ?>

                        <!-- End Right Column -->
                    </div><!-- postbox-container 1 -->
                    <div id="postbox-container-2" class="postbox-container">
                    </div><!-- postbox-container 2 -->
                </div><!-- post-body meta box container -->
            </div><!--poststuff end -->
        </div><!-- wrap end -->
        <?php
    }

    public function main_column() {
        ?>
        <!-- Box -->
        <table class="widefat striped">
            <thead>
            <th>Header</th>
            </thead>
            <tbody>
            <tr>
                <td>
                    Content
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->
        <?php
    }

    public function right_column() {
        ?>
        <!-- Box -->
        <table class="widefat striped">
            <thead>
            <th>Information</th>
            </thead>
            <tbody>
            <tr>
                <td>
                    Content
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->
        <?php
    }
}

