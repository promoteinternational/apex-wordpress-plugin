<?php

/**
 * @package   PromoteApex
 */

namespace Apex\WpApi\Callbacks;

use Apex\Base\BaseController;

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
        _e('Provide the general plugin settings', 'promote-apex');
    }


    public function apexPluginApiSettings()
    {
        _e('Provide the API settings:', 'promote-apex');
    }

    public function apexPluginAdditionalCss()
    {
        _e('Provide additional CSS (optional):', 'promote-apex');
    }

    public function apexPluginCourseExtraInfo()
    {
        _e('Provide extra information for the course information page:', 'promote-apex');
    }

    public function apexPluginListingPage()
    {
        _e('Provide options for the courses page:', 'promote-apex');
        echo '<p>' . __('You can use shortcodes for output this fields: <strong>[Apex-courses-list-before], [Apex-courses-list], [Apex-courses-list-after]</strong>', 'promote-apex') . '</p>';
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
            <option value="SEK" <?= ($value == "SEK") ? 'selected="selected"' : '' ?>><?php _e('Swedish Krona', 'promote-apex') ?></option>
            <option value="USD" <?= ($value == "USD") ? 'selected="selected"' : '' ?> ><?php _e('United States Dollars', 'promote-apex') ?></option>
            <option value="EUR" <?= ($value == "EUR") ? 'selected="selected"' : '' ?> ><?php _e('Euro', 'promote-apex'); ?></option>
            <option value="GBP" <?= ($value == "GBP") ? 'selected="selected"' : '' ?>><?php _e('United Kingdom Pounds', 'promote-apex'); ?></option>
        </select>

        <?php
    }

    public function apexPluginSetSlug()
    {
        $value = esc_attr(get_option('apex_plugin_slug'));
        $translation = __('Enter Apex courses slug', 'promote-apex');
        echo '<input type="text" class="regular-text" name="apex_plugin_slug" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginDisplayVenue()
    {
        $value = esc_attr(get_option('apex_plugin_display_venue'));
        ?>
        <select name="apex_plugin_display_venue" class="apex_plugin_display_venue">
            <option value="dates" <?= ($value == "dates") ? 'selected="selected"' : '' ?> > <?php _e('Show dates', 'promote-apex') ?></option>
            <option value="venues" <?= ($value == "venues") ? 'selected="selected"' : '' ?>> <?php _e('Group by venue', 'promote-apex') ?></option>
        </select>
        <?php
    }


    public function apexPluginVenueOrder()
    {
        $value = esc_attr(get_option('apex_plugin_venue_order'));
        $translation = __('Venue order', 'promote-apex');
        echo '<input type="text" class="regular-text" name="apex_plugin_venue_order" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginVenueReplacement()
    {
        $value = esc_attr(get_option('apex_plugin_venue_replacement'));
        $translation = __('Enter venue replacements', 'promote-apex');
        echo '<input type="text" class="regular-text" name="apex_plugin_venue_replacement" value="' . $value . '" placeholder="' . $translation . '">';
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
        $translation = __('Enter server name', 'promote-apex');
        echo '<input type="text" class="regular-text" name="apex_server_name" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginPublicApiKey()
    {
        $value = esc_attr(get_option('apex_public_api_key'));
        $translation = __('Enter public api key', 'promote-apex');
        echo '<input type="text" class="regular-text" name="apex_public_api_key" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginPrivateApiKey()
    {
        $value = esc_attr(get_option('apex_private_api_key'));
        $translation = __('Enter private api key', 'promote-apex');
        echo '<input type="text" class="regular-text" name="apex_private_api_key" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginPortalId()
    {
        $value = esc_attr(get_option('apex_portal_id'));
        $translation = __('Enter portal ID', 'promote-apex');
        echo '<input type="text" class="regular-text" name="apex_portal_id" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginSendCalendarFile()
    {
        $value = esc_attr(get_option('apex_send_calendar_file'));
        ?>
        <select name="apex_send_calendar_file" class="apex_send_calendar_file">
            <option value="no" <?= ($value == "no") ? 'selected="selected"' : '' ?> > <?php _e('No') ?></option>
            <option value="yes" <?= ($value == "yes") ? 'selected="selected"' : '' ?>> <?php _e('Yes') ?></option>

        </select>
        <?php
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


    public function apexCoursesExtraCss()
    {
        $value = esc_attr(get_option('apex_courses_extra_css'));
        $translation = __('Example (add css styling):
h2 {
  color: #fff;
  background: #222;
}
', 'promote-apex');
        echo '<textarea placeholder="' . $translation . '" rows="10" name="apex_courses_extra_css" class="large-text">' . $value . '</textarea>';
    }

    public function apexCoursesArchiveExtraCss()
    {
        $value = esc_attr(get_option('apex_courses_archive_extra_css'));
        $translation = __('Example (add css styling):
h2 {
  color: #fff;
  background: #222;
}
', 'promote-apex');
        echo '<textarea placeholder="' . $translation . '" rows="10" name="apex_courses_archive_extra_css" class="large-text">' . $value . '</textarea>';
    }

    public function apexCoursesExtraInfo()
    {
        $value = get_option('apex_courses_extra_info');
        wp_editor( $value, 'apex_courses_extra_info', $settings = array('textarea_rows'=> '8', 'textarea_name' => 'apex_courses_extra_info') );
    }

    public function apexPluginExtraBookingInfo()
    {
        $value = get_option('apex_plugin_extra_booking_info');
        wp_editor( $value, 'apex_plugin_extra_booking_info', $settings = array('textarea_rows'=> '8', 'textarea_name' => 'apex_plugin_extra_booking_info') );
    }

    public function apexPluginBookingTerms()
    {
        $value = get_option('apex_plugin_booking_terms');
        wp_editor( $value, 'apex_plugin_booking_terms', $settings = array('textarea_rows'=> '8', 'textarea_name' => 'apex_plugin_booking_terms') );
    }

    public function apexPluginAfterBooking()
    {
        $value = get_option('apex_plugin_after_booking');
        $translation = __('Example (extra javascript):
    alert(course.transactionId);

Valid properties:
course.transactionId = The transactionId
course.price = The price of the course
course.currency = The currency of the price
course.name = The name of the course
course.slug = The slug of the course
', 'promote-apex');
        echo '<textarea placeholder="' . $translation . '" rows="10" name="apex_plugin_after_booking" class="large-text">' . $value . '</textarea>';
    }

    public function apexCoursesListingTitle()
    {
        $value = esc_attr(get_option('apex_courses_listing_title'));
        $translation = __('Enter page title', 'promote-apex');
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

    public function apexCoursesListingSort()
    {
        $value = esc_attr(get_option('apex_courses_listing_sort', 'alphabetic'));
        ?>
        <select name="apex_courses_listing_sort" class="apex_courses_listing_sort">
            <option value="alphabetic" <?= ($value == "alphabetic") ? 'selected="selected"' : '' ?>> <?php _e('Alphabetic', 'promote-apex') ?></option>
            <option value="numeric" <?= ($value == "numeric") ? 'selected="selected"' : '' ?>> <?php _e('Numeric', 'promote-apex') ?></option>
        </select>
        <?php
    }
}