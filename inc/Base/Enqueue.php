<?php
/**
 *  @package  ApexWordpressPlugin
 */

namespace Inc\Base;

use Inc\Base\BaseController;


class Enqueue extends BaseController
{


    public function register(){
        //Connect scripts and styles
            add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
            add_action('wp_enqueue_scripts', [$this, 'enqueueFront']);
            add_action('init',[$this,'initCPT']);
            add_filter('single_template', [$this,'register_courses_template']);

    }

    function enqueueAdmin()
        {
            wp_enqueue_style('apexpluginstyle', $this->plugin_url .'assets/admin.css');
            wp_enqueue_script('apexpluginscript',$this->plugin_url . 'assets/admin.js');
        }

    function enqueueFront()
    {
        global $post;

        if ( $post->post_type == 'courses' ) {
            wp_enqueue_style('apexpluginstyle', $this->plugin_url .'assets/courses.css');
            wp_enqueue_script('apexpluginscript',$this->plugin_url . 'assets/main.js');
            wp_enqueue_style('bootstrap', "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css");
            wp_enqueue_script('bootstrap',"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js",['jquery'],null,true);

        }

    }



    function initCPT(){
        // Register the CPT Courses
        $translation = _('Courses');
        register_post_type('courses', ['public' => true, 'label' => $translation, 'capabilities' => array(
            'create_posts' => 'do_not_allow'), 'map_meta_cap' => true]);
        flush_rewrite_rules();
    }




    function register_courses_template($single) {

        global $post;

        if ( $post->post_type == 'courses' ) {
            if ( file_exists( $this->plugin_path . 'templates/courses.php' ) ) {
                return $this->plugin_path . 'templates/courses.php';
            }
        }
        return $single;
    }
}