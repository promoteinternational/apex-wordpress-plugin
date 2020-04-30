<?php
/**
 *  @package   PromoteApex
 */

namespace Apex\Base;

class Enqueue extends BaseController
{
    public function register() {
        //Connect scripts and styles
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFront']);
        add_action('init',[$this,'initCPT']);
        add_filter('single_template', [$this,'register_courses_template']);
        add_filter('archive_template', [$this,'register_courses_template_archive'] ) ;

        // Shortcodes
        add_shortcode( 'Apex-courses-list', [$this,'apex_courses_list'] );
        add_shortcode( 'Apex-courses-list-before', [$this,'apex_courses_list_before'] );
        add_shortcode( 'Apex-courses-list-after', [$this,'apex_courses_list_after'] );
    }

    function enqueueAdmin() {
        wp_enqueue_style('apexpluginstyle', $this->plugin_url .'assets/admin.css');
        wp_enqueue_script('apexpluginscript',$this->plugin_url . 'assets/admin.js');
    }

    function enqueueFront() {
        global $post;

        wp_enqueue_style('apexpluginstyle', $this->plugin_url .'assets/courses.css');

        if ( $post->post_type === $this->plugin_slug ) {
            wp_enqueue_script('apexpluginscript',$this->plugin_url . 'assets/main.js');
            wp_enqueue_script('bootstrap-util',$this->plugin_url . 'assets/util.js',['jquery'],null,true);
            wp_enqueue_script('bootstrap-modal',$this->plugin_url . 'assets/modal.js',['jquery', 'bootstrap-util'],null,true);
        }
    }

    /**
     * Register the CPT Courses
     */
    function initCPT() {
        $translation = _(ucfirst($this->plugin_slug));

        register_post_type($this->plugin_slug, [
                'public' => true,
                'label' => $translation,
                'capabilities' => array(
                    'create_posts' => 'do_not_allow'
                ),
                'map_meta_cap' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-book-alt',
            ]
        );

        register_taxonomy('Apex-areas', $this->plugin_slug, [
            'label' => 'Areas',
            'capabilities' => array(
                'manage_terms' => 'do_not_allow'
            ),
        ]);

        flush_rewrite_rules();
    }

    function register_courses_template($single) {
        global $post;

        if ( $post->post_type === $this->plugin_slug ) {
            if ( file_exists( $this->plugin_path . 'templates/courses.php' ) ) {
                return $this->plugin_path . 'templates/courses.php';
            }
        }
        return $single;
    }

    /**
     * Register archive template for the courses CPT
     * @param $archive_template
     * @return string
     */
    function register_courses_template_archive( $archive_template ) {
        if ( is_post_type_archive ( $this->plugin_slug ) ) {
            if ( file_exists( $this->plugin_path . 'templates/courses-archive.php' ) ) {
                $archive_template = $this->plugin_path . 'templates/courses-archive.php';
            }
        }
        return $archive_template;
    }

    /**
     * Courses listing shortcode
     * @param $atts
     * @param string $content
     * @return false|string
     */
    function apex_courses_list($atts, $content = "") {
        ob_start();

        $courses_slug = get_option('apex_plugin_slug', 'courses');
        $courses_sort = get_option('apex_courses_listing_sort', 'alphabetic');
        $taxonomy =  'Apex-areas';
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC'
        ]);

        if ($courses_sort == 'numeric') {
            $number_of_courses = array();
            foreach($terms as $term => $row) {
                $number_of_courses[$term] = get_term_meta($row->term_id, 'number_of_courses');
            }

            array_multisort($number_of_courses, SORT_DESC, $terms);
        }

        ?>
        <div class="content-area apex-courses apex-bootstrap">
        <section class="container">
        <div class="row">
        <?php
        foreach($terms as $term):
            $courses = get_posts(array(
                'post_type' => $courses_slug,
                'numberposts' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'name',
                        'terms' => $term->name,
                        'include_children' => false
                    )
                )
            ));

            if (!count($courses)) continue;
            echo '<div class="col-lg-4 Apex-area">';
            echo '<h2>'.$term->name.'</h2>';

            foreach ($courses as $course):
                echo '<a href="'.get_post_permalink($course->ID).'">' . $course->post_title . '</a><br>';
            endforeach;

            echo '</div>';
        endforeach;
        ?>
        </div>
        </section>
        </div>
        <?php
        return ob_get_clean();
    }

    function apex_courses_list_before($atts, $content = "") {
        $content_start = get_option('apex_courses_listing_start', 'courses');

        if ($content_start) {
            ob_start();
            echo '<div class="col-12 Apex-area-content">';
            echo apply_filters('the_content', $content_start);
            echo '</div>';
            return ob_get_clean();
        } else {
            return '';
        }

    }

    function apex_courses_list_after($atts, $content = "") {
        $content_end = get_option('apex_courses_listing_end', 'courses');

        if ($content_end) {
            ob_start();
            echo '<div class="col-12 Apex-area-content">';
            echo apply_filters('the_content', $content_end);
            echo '</div>';
            return ob_get_clean();
        } else {
            return '';
        }

    }
}