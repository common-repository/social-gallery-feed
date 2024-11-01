<?php

/**
 * Feed Widget Class
 *
 * Creates the Feed Widget
 *
 * @package insta-feed-widget
 */
require_once( SI_PLUGIN_DIR . '/inc/class-simple-instagram.php' );

class SI_Feed_Widget extends WP_Widget {

    public function SI_Feed_Widget() {
        $widget_ops  = array( 'classname' => 'si_feed_widget', 'description' => __( 'A widget to display your Instagram Feed', 'insta-feed-widget' ) );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'insta-feed-widget' );
        
        $this->WP_Widget( 'si_feed_widget', __( 'Insta Feed Widget', 'insta-feed-widget' ), $widget_ops, $control_ops );
    }

    public function widget( $args, $instance ) {

        extract( $args );

        $title   = apply_filters( 'widget_title', $instance['title'] );

        $profile = $instance['profile'];
        $profile_picture = $instance['profile_picture'];
        $username        = $instance['username'];
        $full_name       = $instance['full_name'];
        $bio             = $instance['bio'];
        $website         = $instance['website'];
        $user_meta       = $instance['user_meta'];

        $count   = $instance['count'];
        $columns = $instance['columns'];
        $user    = isset( $instance['user'] ) ? $instance['user'] : '';
        $user    = $user == '' ? 'self' : $user;

        echo $before_widget;

        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        $instagram = new Simple_Instagram();
        $feed      = $instagram->get_user_media( $user, $count );

        $return    = '';

        if ($profile == 'true') {
            $userobj = $instagram->get_user($user);
            $return .= '<div class="si_profile_widget">';

            if ( 'true' == $profile_picture && $userobj->profile_picture != '' ) {
                
                $url     = str_replace( 'http://', '//', $userobj->profile_picture );
                $return .= '<div class="si_profile_picture">';
                $return .= '<img src="' . $url . '">';
                $return .= '</div>';
            }

            $return .= $username == 'true' && $userobj->username != '' ? '<div class="si_username"><a href="https://www.instagram.com/'.$userobj->username.'">'.$userobj->username.'</a>'.($full_name == 'true' && $userobj->full_name != '' ? ' <span class="si_full_name">(' . $userobj->full_name . ')</span>' : null).'</div>' : null;
            $return .= $bio == 'true' && $userobj->bio != '' ? '<div class="si_bio">' . $userobj->bio . '</div>' : null;
            $return .= $website == 'true' && $userobj->website != '' ? '<div class="si_website"><a href="' . $userobj->website . '">View Website</a></div>' : null;
            $return .= $user_meta == 'true' && $userobj->counts->media != '' ? '<div class="si_meta"><span class="si_meta_num">'.$userobj->counts->media.'</span> posts, <span class="si_meta_num">'.$userobj->counts->followed_by.'</span> followers, <span class="si_meta_num">'.$userobj->counts->follows.'</span> following</div>' : null;
            $return .= '</div>';
        }


        if ( $feed && count( $feed ) > 0 ) {

            $return .= '<div class="si_feed_widget">';
            
            $return .= '<script>function update_size(element) {'.
                'var width = element.offsetWidth;'.
                'var overlay = element.children[0].children[1];'.
                'if (width > 250) {'.
                '  overlay.children[0].setAttribute(\'style\',\'font-size:14px\');'.
                '} else if (width < 251 && width > 170) {'.
                '  overlay.children[0].setAttribute(\'style\',\'font-size:11px\');'.
                '} else if (width < 171 && width > 84) {'.
                '  overlay.children[0].setAttribute(\'style\',\'display:none\');'.
                '  overlay.children[1].setAttribute(\'style\',\'font-size:13px; margin-top:\'+(width/2 - 20)+\'px\');'.
                '  if (overlay.children[1].children.length < 5) {'.
                '    overlay.children[1].insertBefore(document.createElement("br"), overlay.children[1].children[2]);'.
                '  }'.
                '} else if (width < 85) {'.
                '  overlay.children[0].setAttribute(\'style\',\'display:none\');'.
                '  overlay.children[1].setAttribute(\'style\',\'font-size:12px; margin-top:10px\');'.
                '  if (overlay.children[1].children.length < 5) {'.
                '    overlay.children[1].insertBefore(document.createElement("br"), overlay.children[1].children[2]);'.
                '  }'.
                '} '.
                '}</script>';

            if ($columns != '' && $columns > 1 ) {
                $rows = ceil(count($feed) / $columns);
                for ($row=0; $row < $rows; $row++) {

                    $return .= '<ul class="si_feed_list">';

                    for ($index=0; $index < $columns; $index++) {
                        $image = $feed[$index + ($row * $columns)];

                        $url = $image->images->low_resolution->url;

                        // Fix https
                        $url = str_replace( 'http://', '//', $url );

                        $return .= '<li class="si_item" style="width:'.(100/$columns).'%; height: auto;" onmouseover="update_size(this); this.children[0].children[1].setAttribute(\'style\',\'display:block\');" onmouseout="this.children[0].children[1].setAttribute(\'style\',\'display:none\');">';

                        $return .= '<a href="' . $image->link . '" target="_blank">';

                        $image_caption = is_object( $image->caption ) ? $image->caption->text : '';
                        $return .= '<img alt="'. $image_caption . '" src="' . $url . '" style="width:100%; height: auto;">';
                        $return .= '<div class="si_item_overlay">';
                        $return .= '<div class="si_item_overlay_caption">'. $image_caption . '</div>';
                        $return .= '<div class="si_item_overlay_likes_comments"><img src="'.SI_PLUGIN_URL . 'public/assets/hearth.png"/><span>&nbsp;'. $image->likes->count;
                        $return .= '&nbsp;&nbsp;&nbsp;</span><img src="'.SI_PLUGIN_URL . 'public/assets/bubble.png"/><span>&nbsp;'. $image->comments->count . '</span></div>';
                        $return .= '</div>';
                        $return .= '</a>';
                        $return .= '</li>';
                    }

                    $return .= '</ul>';
                }
            } else {
                $return .= '<ul class="si_feed_list">';

                foreach ( $feed as $image ) {

                    $url = $image->images->standard_resolution->url;

                    // Fix https
                    $url = str_replace( 'http://', '//', $url );

                    $return .= '<li class="si_item">';

                    $return .= '<a href="' . $image->link . '" target="_blank">';

                    $image_caption = is_object( $image->caption ) ? $image->caption->text : '';
                    $return .= '<img alt="'. $image_caption . '" src="' . $url . '">';
                    $return .= '</a>';
                    $return .= '</li>';
                }

                $return .= '</ul>';
            }
            $return .= '</div>';

        } 

        echo $return;

        echo $after_widget;
    }

    public function update( $new_instance, $old_instance ) {

        check_admin_referer('admin-update-nonce');
        $instance = $old_instance;

        $instance['title']           = strip_tags( $new_instance['title'] );
        $instance['profile']         = isset( $new_instance['profile'] ) ? 'true' : 'false';
        $instance['profile_picture'] = isset( $new_instance['profile_picture'] ) ? 'true' : 'false';
        $instance['username']        = isset( $new_instance['username'] ) ? 'true' : 'false';
        $instance['full_name']       = isset( $new_instance['full_name'] ) ? 'true' : 'false';
        $instance['bio']             = isset( $new_instance['bio'] ) ? 'true' : 'false';
        $instance['website']         = isset( $new_instance['website'] ) ? 'true' : 'false';
        $instance['user_meta']       = isset( $new_instance['user_meta'] ) ? 'true' : 'false';

        $instance['count']           = $new_instance['count'];
        $instance['columns']         = $new_instance['columns'];
        $instance['user']            = $new_instance['user'];

        return $instance;

    }

    public function form( $instance ) {

        $defaults = array( 'title' => __( 'From Instagram', 'insta-feed-widget' ), 'count' => __( '0', 'insta-feed-widget' ), 'user' => __( '', 'insta-feed-widget' ) );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $style    = 'width:100%;';

        wp_nonce_field('admin-update-nonce');

        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'insta-feed-widget' ); ?>
            </label>
            <input 
                id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
                value="<?php echo esc_attr( $instance['title'] ); ?>" 
                style="<?php echo esc_attr( $style ); ?>" 
            />
        </p>


        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'user' ) ); ?>">
                <?php _e( 'User ID (leave blank to use your own feed):', 'insta-feed-widget' ); ?>
            </label>
            <input 
                id="<?php echo esc_attr( $this->get_field_id( 'user' ) ); ?>" 
                name="<?php echo esc_attr( $this->get_field_name( 'user' ) ); ?>" 
                value="<?php echo esc_attr( $instance['user'] ); ?>" 
                style="<?php echo esc_attr( $style ); ?>" 
            />
        </p>

        <p>
            <input type="checkbox" 
                name="<?php echo esc_attr( $this->get_field_name( 'profile' ) ); ?>" 
                <?php if ( isset( $instance['profile'] ) && 'true' == $instance['profile'] ) { echo 'checked="checked"'; } ?> /> 
                <?php _e( 'Show User Profile Section', 'insta-feed-widget' ); ?><br />
            <label><?php _e( 'Include In User Profile', 'insta-feed-widget' ); ?></label><br />
            <input type="checkbox" 
                name="<?php echo esc_attr( $this->get_field_name( 'profile_picture' ) ); ?>" 
                <?php if ( isset( $instance['profile_picture'] ) && 'true' == $instance['profile_picture'] ) { echo 'checked="checked"'; } ?> /> 
                <?php _e( 'Profile Picture', 'insta-feed-widget' ); ?><br />
            <input type="checkbox" 
                name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" 
                <?php if ( isset( $instance['username'] ) && 'true' == $instance['username'] ) { echo 'checked="checked"'; } ?> /> 
                <?php _e( 'Username', 'insta-feed-widget' ); ?><br />
            <input type="checkbox" 
                name="<?php echo esc_attr( $this->get_field_name( 'full_name' ) ); ?>" 
                <?php if ( isset( $instance['full_name'] ) && 'true' == $instance['full_name'] ) { echo 'checked="checked"'; } ?> /> 
                <?php _e( 'Full Name', 'insta-feed-widget' ); ?><br />
            <input type="checkbox" 
                name="<?php echo esc_attr( $this->get_field_name( 'bio' ) ); ?>" 
                <?php if ( isset( $instance['bio'] ) && 'true' == $instance['bio'] ) { echo 'checked="checked"'; } ?> /> 
                <?php _e( 'Bio', 'insta-feed-widget' ); ?><br />
            <input type="checkbox" 
                name="<?php echo esc_attr( $this->get_field_name( 'website' ) ); ?>" 
                <?php if ( isset( $instance['website'] ) && 'true' == $instance['website'] ) { echo 'checked="checked"'; } ?> /> 
                <?php _e( 'Website', 'insta-feed-widget' ); ?><br />
            <input type="checkbox" 
                name="<?php echo esc_attr( $this->get_field_name( 'user_meta' ) ); ?>" 
                <?php if ( isset( $instance['user_meta'] ) && 'true' == $instance['user_meta'] ) { echo 'checked="checked"'; } ?> /> 
                <?php _e( 'User meta info', 'insta-feed-widget' ); ?><br />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
                <?php _e( 'Number of Images (0 for Unmlimited):', 'insta-feed-widget' ); ?>
            </label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" 
                name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" 
                value="<?php echo esc_attr( $instance['count'] ); ?>" 
                style="<?php echo esc_attr( $style ); ?>" 
            />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>">
                <?php _e( 'Number of Columns:', 'insta-feed-widget' ); ?>
            </label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" 
                name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" 
                value="<?php echo esc_attr( $instance['columns'] ); ?>" 
                style="<?php echo esc_attr( $style ); ?>" 
            />
        </p>

      <?php
    }

}