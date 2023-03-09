<?php

/*
Plugin Name:  WPSST Minecraft Server Status Widget
Plugin URI:   https://www.syriasmart.net/
Description:  A widget for displaying the status of a Minecraft server.
Version:      1.0
Author:       Syria Smart Technology 
Author URI:   https://www.syriasmart.net
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpsst-minecraft-server-status-widget
Domain Path:  /languages
*/

class WPSST_Minecraft_Server_Status_Widget extends WP_Widget
{

    // Set up the widget name, description, and other options
    public function __construct()
    {
        $widget_options = array(
            'classname' => 'wpsst-minecraft-server-status-widget',
            'description' => 'Display the status of a Minecraft server with a join button.',
        );
        parent::__construct('wpsst-minecraft-server-status-widget', 'Minecraft Server Status', $widget_options);
    }

    // Output the widget HTML
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        $description = apply_filters('widget_text', $instance['description']);
        $server_ip = $instance['server_ip'];
        $server_port = $instance['server_port'];
        $server_name = $instance['server_name'];
        $join_button_text = $instance['join_button_text'];

        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        if (!empty($description)) {
            echo '<p>' . $description . '</p>';
        }

        // Get the Minecraft server status using the server IP and port
        $status = json_decode(file_get_contents("https://api.mcsrvstat.us/2/$server_ip:$server_port"), true);
        if (isset($status['online']) && $status['online']) {
            $players = $status['players']['online'];
            $max_players = $status['players']['max'];
            $version = $status['version'];
            echo "<p><strong>$server_name</strong> is online with $players/$max_players players on $version.</p>";
            echo "<a href='minecraft://$server_ip:$server_port'><button>$join_button_text</button></a>";
        } else {
            echo "<p><strong>$server_name</strong> is currently offline.</p>";
        }

        echo $args['after_widget'];
    }

    // Output the widget settings form
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $server_ip = !empty($instance['server_ip']) ? $instance['server_ip'] : '';
        $server_port = !empty($instance['server_port']) ? $instance['server_port'] : '';
        $server_name = !empty($instance['server_name']) ? $instance['server_name'] : '';
        $join_button_text = !empty($instance['join_button_text']) ? $instance['join_button_text'] : '';

        // Display the widget settings form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>">Description:</label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>"
                name="<?php echo $this->get_field_name('description'); ?>"><?php echo esc_attr($description); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('server_ip'); ?>">Server IP Address:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('server_ip'); ?>"
                name="<?php echo $this->get_field_name('server_ip'); ?>" type="text"
                value="<?php echo esc_attr($server_ip); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('server_port'); ?>">Server Port:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('server_port'); ?>"
                name="<?php echo $this->get_field_name('server_port'); ?>" type="text"
                value="<?php echo esc_attr($server_port); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('server_name'); ?>">Server Name:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('server_name'); ?>"
                name="<?php echo $this->get_field_name('server_name'); ?>" type="text"
                value="<?php echo esc_attr($server_name); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('join_button_text'); ?>">Join Button Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('join_button_text'); ?>"
                name="<?php echo $this->get_field_name('join_button_text'); ?>" type="text"
                value="<?php echo esc_attr($join_button_text); ?>" />
        </p>
        <?php
    }

    // Save the widget settings
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = strip_tags($new_instance['description']);
        $instance['server_ip'] = strip_tags($new_instance['server_ip']);
        $instance['server_port'] = strip_tags($new_instance['server_port']);
        $instance['server_name'] = strip_tags($new_instance['server_name']);
        $instance['join_button_text'] = strip_tags($new_instance['join_button_text']);

        return $instance;
    }
}

// Register the widget
function register_wpsst_minecraft_server_status_widget()
{
    register_widget('WPSST_Minecraft_Server_Status_Widget');
}
add_action('widgets_init', 'register_wpsst_minecraft_server_status_widget');

?>