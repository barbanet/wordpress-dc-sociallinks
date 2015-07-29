<?php
/*
Plugin Name: DC Social links
Description: Social links to be used into your template.
Version: 0.3.0
Author: Damián Culotta
Author URI: http://www.damianculotta.com.ar
License: GPL3
*/

/* WIDGET */

class DcSocialLinks extends WP_Widget
{
    
    public function __construct()
    {
        parent::__construct('dc_sociallinks', 'Social Links', array('description' => 'Social links to be used into your template'));
    }

    public function form($instance)
    {
        $default = array(
                            'title' => 'Social',
                            'size' => ''
                        );
        $instance = wp_parse_args((array)$instance, $default);
        $title = $instance['title'];
        $size = $instance['size'];
        ?>
        <p><?php _e('Title'); ?><input class="widefat" type="text" name="<?php echo $this->get_field_name('title');?>" value="<?php echo esc_attr($title);?>"/></p>
        <p>
            <label for="dc_sociallinks_<?php echo $this->get_field_name('size');?>"><?php _e('Size:') ?></label>
            <select class="widefat" id="dc_sociallinks_<?php echo $this->get_field_name('size');?>" name="<?php echo $this->get_field_name('size');?>">
                <option value="s" <?php selected('s', $size) ?>><?php _e('Small') ?></option>
                <option value="m" <?php selected('m', $size) ?>><?php _e('Medium') ?></option>
                <option value="l" <?php selected('l', $size) ?>><?php _e('Large') ?></option>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['size'] = $new_instance['size'];
        return $instance;
    }

    public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $size = $instance['size'];
        switch ($size) {
            case 's':
                $size = ' small';
                break;
            case 'l':
                $size = ' large';
                break;
            default:
                $size = '';
        }
        
        $_sociallinks = unserialize(get_option('sociallinks'));
        $_sociallinks_bitbucket = $_sociallinks['bitbucket'];
        $_sociallinks_facebook = $_sociallinks['facebook'];
        $_sociallinks_github = $_sociallinks['github'];
        $_sociallinks_googleplus = $_sociallinks['googleplus'];
        $_sociallinks_linkedin = $_sociallinks['linkedin'];
        $_sociallinks_twitter = $_sociallinks['twitter'];
        $_sociallinks_newsletter = $_sociallinks['newsletter'];
        $_sociallinks_rss = $_sociallinks['rss'];
        
        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;
        echo '<div class="sociallinks-container">';
        if ($_sociallinks_bitbucket) {
            echo '<a rel="me" title="Bitbucket" href="' . $_sociallinks_bitbucket . '"><i class="fa fa-bitbucket-square' . $size . '"></i></a>';
        }
        if ($_sociallinks_facebook) {
            echo '<a rel="me" title="Facebook" href="' . $_sociallinks_facebook . '"><i class="fa fa-facebook-square' . $size . '"></i></a>';
        }
        if ($_sociallinks_github) {
            echo '<a rel="me" title="GitHub" href="' . $_sociallinks_github . '"><i class="fa fa-github-square' . $size . '"></i></a>';
        }
        if ($_sociallinks_googleplus) {
            echo '<a rel="me" title="Goolge+" href="' . $_sociallinks_googleplus . '"><i class="fa fa-google-plus-square' . $size . '"></i></a>';
        }
        if ($_sociallinks_linkedin) {
            echo '<a rel="me" title="LinkedIn" href="' . $_sociallinks_linkedin . '"><i class="fa fa-linkedin-square' . $size . '"></i></a>';
        }
        if ($_sociallinks_twitter) {
            echo '<a rel="me" title="Twitter" href="' . $_sociallinks_twitter . '"><i class="fa fa-twitter-square' . $size . '"></i></a>';
        }
        if ($_sociallinks_newsletter) {
            echo '<a title="Newsletter" href="' . $_sociallinks_newsletter . '"><i class="fa fa-envelope-square' . $size . '"></i></a>';
        }
        if ($_sociallinks_rss) {
            echo '<a title="RSS" href="' . $_sociallinks_rss . '"><i class="fa fa-rss-square' . $size . '"></i></a>';
        }
        echo '</div>';
        echo $after_widget;
    }

}

function registerDcSocialLinksWidget()
{
    register_widget('DcSocialLinks');
}

add_action('widgets_init', 'registerDcSocialLinksWidget');

function getDcSocialLinksStyles()
{
    wp_enqueue_style('dc-social-links', plugin_dir_url(__FILE__) . 'css/social-links.css', false, getDcSocialLinksVersion());
}

add_action('wp_enqueue_scripts', 'getDcSocialLinksStyles');

/* ADMIN */

add_action('admin_head', 'getDcSocialLinksStyles');

function getDcSocialLinksAdmin()
{
    
    if (!is_plugin_active('dc-fontawesome/fontawesome.php')) {
        ?>
        <div id="message" class="error"><p><strong><?php _e('Plugin Dc FontAwesome is required.') ?></strong></p></div>
        <?php
    }
    if ($_POST['sociallinks_form']) {
        update_option('sociallinks', serialize($_POST['sociallinks']));
        ?>
        <div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
        <?php
    }
    $_sociallinks = unserialize(get_option('sociallinks'));
    ?>
    <div class="wrap">
        <h2><?php _e('DC Social Links'); ?></h2>
        <p><?php _e('Select which profiles you want to show.'); ?></p>
        <h3><?php _e('Social Networks'); ?></h3>
        <form method="post" action="<?php echo admin_url('options-general.php?page=sociallinks'); ?>">
            <input type="hidden" name="sociallinks_form" value="1" />
            <table class="form-table" id="dc-social-links">
                <tr>
                    <td class="dc-social-links">
                        <i class="fa fa-bitbucket-square"></i>
                    </td>
                    <td>
                        <input type="text" class="regular-text" name="sociallinks[bitbucket]" value="<?php echo sanitize_text_field($_sociallinks['bitbucket']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="dc-social-links">
                        <i class="fa fa-facebook-square"></i>
                    </td>
                    <td>
                        <input type="text" class="regular-text" name="sociallinks[facebook]" value="<?php echo sanitize_text_field($_sociallinks['facebook']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="dc-social-links">
                        <i class="fa fa-github-square"></i>
                    </td>
                    <td>
                        <input type="text" class="regular-text" name="sociallinks[github]" value="<?php echo sanitize_text_field($_sociallinks['github']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="dc-social-links">
                        <i class="fa fa-google-plus-square"></i>
                    </td>
                    <td>
                        <input type="text" class="regular-text" name="sociallinks[googleplus]" value="<?php echo sanitize_text_field($_sociallinks['googleplus']); ?>" />
                    </td>
                </tr> 
                <tr>
                    <td class="dc-social-links">
                        <i class="fa fa-linkedin-square"></i>
                    </td>
                    <td>
                        <input type="text" class="regular-text" name="sociallinks[linkedin]" value="<?php echo sanitize_text_field($_sociallinks['linkedin']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="dc-social-links">
                        <i class="fa fa-twitter-square"></i>
                    </td>
                    <td>
                        <input type="text" class="regular-text" name="sociallinks[twitter]" value="<?php echo sanitize_text_field($_sociallinks['twitter']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="dc-social-links">
                        <i class="fa fa-envelope-square"></i>
                    </td>
                    <td>
                        <input type="text" class="regular-text" name="sociallinks[newsletter]" value="<?php echo sanitize_text_field($_sociallinks['newsletter']); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="dc-social-links">
                        <i class="fa fa-rss-square"></i>
                    </td>
                    <td>
                        <input type="text" class="regular-text" name="sociallinks[rss]" value="<?php echo sanitize_text_field($_sociallinks['rss']); ?>" placeholder="<?php echo bloginfo('rss2_url'); ?>" />
                    </td>
                </tr>
            </table>
            <p class="submit"><input class="button" type="submit" name="submit" value="<?php _e('Update &raquo;'); ?>" /></p>
        </form>
    </div>
    <?php
}

function getDcSocialLinksAdminMenu()
{
    add_options_page('Social Links', 'Social Links', 8, 'sociallinks', 'getDcSocialLinksAdmin');
}

add_action('admin_menu', 'getDcSocialLinksAdminMenu');

function getDcSocialLinksVersion()
{
    if (!function_exists('get_plugins')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
    $plugin_file = basename((__FILE__));
    return $plugin_folder[$plugin_file]['Version'];
}

?>
