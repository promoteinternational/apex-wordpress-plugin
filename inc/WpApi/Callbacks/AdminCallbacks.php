<?php

/**
 * @package  ApexWordpressPlugin
 */

namespace Inc\WpApi\Callbacks;

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
    public function adminDashboard()
    {
        return require_once("$this->plugin_path/templates/admin.php");
    }

    public function updateManager()
    {
        return require_once("$this->plugin_path/templates/updateManager.php");
    }

    public function apexPluginGroup($input)
    {
        return $input;
    }

    public function apexPluginSlug($input)
    {
        global $wpdb;
        $old_slug = $this->plugin_slug;
        $wpdb->update($wpdb->posts, ['post_type' => $input], ['post_type' => $old_slug]);

        return $input;
    }

    public function apexPluginGeneralSettings()
    {
        _e('Provide the general plugin settings', 'apex-wordpress-plugin');
    }


    public function apexPluginApiSettings()
    {
        _e('Provide the API settings:', 'apex-wordpress-plugin');
    }

    public function apexPluginAdditionalCss()
    {
        _e('Provide additional CSS (optional):', 'apex-wordpress-plugin');
    }

    public function apexPluginCourseExtraInfo()
    {
        _e('Provide extra information for the course information page:', 'apex-wordpress-plugin');
    }

    public function apexPluginListingPage()
    {
        _e('Provide options for the courses page:', 'apex-wordpress-plugin');
        echo '<p>' . __('You can use shortcodes for output this fields: <strong>[apex-courses-list-before], [apex-courses-list], [apex-courses-list-after]</strong>', 'apex-wordpress-plugin') . '</p>';
    }

    public function apexPluginUpdateFrequency()
    {
        $value = esc_attr(get_option('apex_update_frequency'));
        echo '<input type="number" min="1" class="regular-text" name="apex_update_frequency" value="' . $value . '" placeholder="">';
    }

    public function apexPluginSetCurrency()
    {
        $value = esc_attr(get_option('apex_currency'));
        ?>

        <select name="apex_currency" class="apex_currency">
            <option value="SEK" <?= ($value == "SEK") ? 'selected="selected"' : '' ?>><?php _e('Swedish Krona', 'apex-wordpress-plugin') ?></option>
            <option value="USD" <?= ($value == "USD") ? 'selected="selected"' : '' ?> ><?php _e('United States Dollars', 'apex-wordpress-plugin') ?></option>
            <option value="EUR" <?= ($value == "EUR") ? 'selected="selected"' : '' ?> ><?php _e('Euro', 'apex-wordpress-plugin'); ?></option>
            <option value="GBP" <?= ($value == "GBP") ? 'selected="selected"' : '' ?>><?php _e('United Kingdom Pounds', 'apex-wordpress-plugin'); ?></option>
        </select>

        <?php
    }

    public function apexPluginSetSlug()
    {
        $value = esc_attr(get_option('apex_plugin_slug'));
        $translation = __('Enter Apex courses slug', 'apex-wordpress-plugin');
        echo '<input type="text" class="regular-text" name="apex_plugin_slug" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginDisplayVenue()
    {
        $value = esc_attr(get_option('apex_plugin_display_venue'));
        ?>
        <select name="apex_plugin_display_venue" class="apex_plugin_display_venue">
            <option value="dates" <?= ($value == "dates") ? 'selected="selected"' : '' ?> > <?php _e('Show dates', 'apex-wordpress-plugin') ?></option>
            <option value="venues" <?= ($value == "venues") ? 'selected="selected"' : '' ?>> <?php _e('Group by venue', 'apex-wordpress-plugin') ?></option>
        </select>
        <?php
    }

    public function apexPluginAddHeaders()
    {
        $value = esc_attr(get_option('apex_plugin_add_headers'));
        ?>
        <select name="apex_plugin_add_headers" class="apex_plugin_add_headers">
            <option value="no" <?= ($value == "no") ? 'selected="selected"' : '' ?> > <?php _e('No') ?></option>
            <option value="yes" <?= ($value == "yes") ? 'selected="selected"' : '' ?>> <?php _e('Yes') ?></option>
        </select>
        <?php
    }

    public function apexPluginServerName()
    {
        $value = esc_attr(get_option('apex_server_name'));
        $translation = __('Enter server name', 'apex-wordpress-plugin');
        echo '<input type="text" class="regular-text" name="apex_server_name" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginPublicApiKey()
    {
        $value = esc_attr(get_option('apex_public_api_key'));
        $translation = __('Enter public api key', 'apex-wordpress-plugin');
        echo '<input type="text" class="regular-text" name="apex_public_api_key" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginPrivateApiKey()
    {
        $value = esc_attr(get_option('apex_private_api_key'));
        $translation = __('Enter private api key', 'apex-wordpress-plugin');
        echo '<input type="text" class="regular-text" name="apex_private_api_key" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginPortalId()
    {
        $value = esc_attr(get_option('apex_portal_id'));
        $translation = __('Enter portal ID', 'apex-wordpress-plugin');
        echo '<input type="number" min="1" class="regular-text" name="apex_portal_id" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginDisplaySeats()
    {
        $value = esc_attr(get_option('apex_display_seats'));
        ?>
        <select name="apex_display_seats" class="apex_display_seats">
            <option value="no" <?= ($value == "no") ? 'selected="selected"' : '' ?> > <?php _e('No') ?></option>
            <option value="yes" <?= ($value == "yes") ? 'selected="selected"' : '' ?>> <?php _e('Yes') ?></option>

        </select>

        <?php
    }

    public function apexPluginDisplayTitle()
    {
        $value = esc_attr(get_option('apex_display_title'));
        ?>
        <select name="apex_display_title" class="apex_display_title">
            <option value="no" <?= ($value == "no") ? 'selected="selected"' : '' ?> > <?php _e('No') ?></option>
            <option value="yes" <?= ($value == "yes") ? 'selected="selected"' : '' ?>> <?php _e('Yes') ?></option>
        </select>
        <?php
    }

    public function apexPluginDisplaySector()
    {
        $value = esc_attr(get_option('apex_display_sector'));
        ?>
        <select name="apex_display_sector" class="apex_display_sector">
            <option value="no" <?= ($value == "no") ? 'selected="selected"' : '' ?> > <?php _e('No') ?></option>
            <option value="yes" <?= ($value == "yes") ? 'selected="selected"' : '' ?>> <?php _e('Yes') ?></option>
        </select>
        <?php
    }

    public function apexCoursesTitleStyles()
    {
        $value = esc_attr(get_option('apex_courses_title_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_title_styles" class="regular-text">' . $value . '</textarea>';
    }


    public function apexCoursesSectionStyles()
    {
        $value = esc_attr(get_option('apex_courses_section_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_section_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesContentStyles()
    {
        $value = esc_attr(get_option('apex_courses_content_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_content_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesPriceTitleStyles()
    {
        $value = esc_attr(get_option('apex_courses_price_title_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_price_title_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesPriceStyles()
    {
        $value = esc_attr(get_option('apex_courses_price_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_price_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesDayStyles()
    {
        $value = esc_attr(get_option('apex_courses_day_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_day_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventTitleStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_title_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_title_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventDateStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_date_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_date_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventTextStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_text_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_text_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventButtonStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_button_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_button_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventFewPlacesStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_few_places_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_few_places_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesExtraInfo()
    {
        $value = get_option('apex_courses_extra_info');
        wp_editor( $value, 'apex_courses_extra_info', $settings = array('textarea_rows'=> '8', 'textarea_name' => 'apex_courses_extra_info') );
    }

    public function apexCoursesExtraInfoStyles()
    {
        $value = esc_attr(get_option('apex_courses_extra_info_styles'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_extra_info_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesModalContent()
    {
        $value = esc_attr(get_option('apex_courses_modal_content'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_modal_content" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesModalButton()
    {
        $value = esc_attr(get_option('apex_courses_modal_button'));
        $translation = __('Example (add any another css styling):
color: #fff;
background: #222;
', 'apex-wordpress-plugin');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_modal_button" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesListingTitle()
    {
        $value = esc_attr(get_option('apex_courses_listing_title'));
        $translation = __('Enter page title', 'apex-wordpress-plugin');
        echo '<input type="text" class="regular-text" name="apex_courses_listing_title" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexCoursesListingStart()
    {
        $value = get_option('apex_courses_listing_start');
        wp_editor( $value, 'apex_courses_listing_start_block', $settings = array('textarea_rows'=> '8', 'textarea_name' => 'apex_courses_listing_start') );
    }

    public function apexCoursesListingEnd()
    {
        $value = get_option('apex_courses_listing_end');
        wp_editor( $value, 'apex_courses_listing_end_block', $settings = array('textarea_rows'=> '8', 'textarea_name' => 'apex_courses_listing_end') );
    }

}