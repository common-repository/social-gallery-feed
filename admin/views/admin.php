<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 * <?php echo esc_url( admin_url( 'options-general.php?page=insta-feed-widget' ) ); ?>
 *
 * @package   Insta Feed Widget
 * @author    Daniel Dupal <daniel@dendroid.sk>
 * @license   GPL-2.0+
 * @copyright 2016 Daniel Dupal
 */

if ( isset( $_GET['token'] ) ) {
    $token = sanitize_text_field( $_GET['token'] );
    update_option( 'si_access_token', $_GET['token'] );
}

$token = get_option( 'si_access_token' ) ? get_option( 'si_access_token' ) : null;

?>

<div class="wrap">
    <h2><?php _e( 'Insta Feed Widget Settings', 'insta-feed-widget' ); ?></h2>
    <section id="authorize">
        <div class="section_title">
            <?php _e( 'Authorize Your Instagram Account', 'insta-feed-widget' ); ?>
        </div>
        <div class="section_content active">
            <?php if ( is_null( $token ) ): ?>
                <h3><?php _e( 'Account not yet Authorized', 'insta-feed-widget' ); ?></h3>
                <p><?php _e( 'Before you can display your Instagram feeds, you will need to authorize your Instagram account.', 'insta-feed-widget' ); ?></p>
                <p><?php _e( 'Use the button below to begin the Authorization process. You will be redirected to Instagram to sign in and authorize this plugin. Once you authorize the plugin, you will be redirected to page about this plugin.', 'insta-feed-widget' ); ?></p>
                <a target="_blank" href="https://api.instagram.com/oauth/authorize/?client_id=f6538a55a3b241e39bf487b74dcebf6a&response_type=code&redirect_uri=http://dendroid.sk/2016/02/25/insta-feed-widget-wordpress/" class="button button-primary"><?php _e( 'Authorize with Instagram', 'insta-feed-widget' ); ?></a>
            <?php else : ?>
                <h3><?php _e( 'Your account has successfully been authorized to use Insta Feed Widget!', 'insta-feed-widget' ); ?></h3>
                <p><?php _e( 'Feeds not displaying? There might be a problem with your current Authorization. Use the button below to try re-authorizing with Instagram.', 'insta-feed-widget' ); ?></p>
                <p><a target="_blank" href="https://api.instagram.com/oauth/authorize/?client_id=f6538a55a3b241e39bf487b74dcebf6a&response_type=code&redirect_uri=http://dendroid.sk/2016/02/25/insta-feed-widget-wordpress/" class="button button-secondary"><?php _e( 'Re-Authorize with Instagram', 'insta-feed-widget' ); ?></a></p>
            <?php endif; ?>
        </div>
    </section>
    <?php if ( ! is_null( $token ) ): ?>
        <section id="search">
            <div class="section_title">
                <?php _e( 'Lookup User ID', 'insta-feed-widget' ); ?>
            </div>
            <div class="section_content active">
                <p><?php _e( "In order to display the feed of another user, you'll need to know their user ID. Use the form below to search for a user by their username.", 'insta-feed-widget' ); ?></p>
                <form>
                    <label><?php _e( 'Username:', 'insta-feed-widget' ); ?></label>
                    <input type="text" name="user_name">
                    <button class="search_user button button-secondary" value="Search"><?php _e( 'Search!', 'insta-feed-widget' ); ?></button>
                    <div id="search_results"></div>
                </form>
            </div>
        </section>
    <?php endif; ?>
</div>