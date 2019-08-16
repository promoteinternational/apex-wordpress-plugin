<?php
//Determining the number of available seats - TODO: Rewrite to use portal participant count.
$availableSeats = $event->max_participants - $event->booked_participant_count;
?>
<div class="apex-courses__event">
    <div class="col-6">
        <div class="apex-courses__event-date">
            <?= date_i18n('d M', strtotime($event->start_date)) ?><?php if ($days > 1): ?> - <?= date_i18n('d M', strtotime($event->end_date)) ?><?php endif; ?>
        </div>
        <?php if (!empty($venue_city)): ?>
            <div class="apex-courses__event-place">
                <?= $venue_city ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($availableSeats) && $availableSeats >= 1 && $displaySeats === 'yes'): ?>
            <div class="apex-courses__event-available">
                <?php _e('Available seats:', 'apex-wordpress-plugin') ?>
                <?= $availableSeats ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-6 text-right">
        <?php if (!empty($availableSeats) && $availableSeats >= 1): ?>
            <button type="button" class="btn apex-courses__event-button"
                    data-toggle="modal" data-target="#modal_<?= $event->id ?>">
                <?php _e('Apply Now', 'apex-wordpress-plugin') ?>
            </button>
            <?php if ($availableSeats < $event->max_participants / 2): ?>
                <div class="apex-courses__event-few-places">
                    <?php _e('Few seats remaining', 'apex-wordpress-plugin') ?>
                </div>
            <?php endif;
            //Connect Modal template
            require 'modal.php'
            ?>
        <?php else: ?>
            <div class="alert alert-warning"> <?php _e('This event is fully booked', 'apex-wordpress-plugin') ?> </div>
        <?php endif; ?>
    </div>
    <?php

    //If not empty form data, display message
    if (!empty($_POST['event_id']) && $_POST['event_id'] == $event->id):?>
        <div class="apex-courses__confirmation">
            <?php
            $api->addParticipant($_POST['first_name'], $_POST['last_name'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['country'], $_POST['city'], $_POST['address_1'], $_POST['address_2'], $_POST['zip_code'], isset($_POST['sector']) ? $_POST['sector'] : null, isset($_POST['title']) ? $_POST['title'] : null, $_POST['event_id']);

            // Update booked participant count in wp database.
            $success = $api->getSuccess();
            $trSuccess = __('Your application was successfully submitted!', 'apex-wordpress-plugin');
            $trError = __('Something went wrong with your application', 'apex-wordpress-plugin');
            if ($success) {
                $event->booked_participant_count = $event->booked_participant_count + 1;
                echo '<div class="alert alert-success">' . $trSuccess . '</div>';
            } else {
                echo '<div class="alert alert-danger">' . $trError . '</div>';
            }
            ?>
        </div>
    <?php
    endif;

    //Updating events data in DB
    array_push($newEvents, $event);
    $newEventsSerialize = serialize($newEvents);
    global $wpdb;
    $postId = get_the_ID();
    $wpdb->query("UPDATE `wp_postmeta` meta1 SET meta1.meta_value = '$newEventsSerialize' WHERE `post_id` =  $postId  AND meta1.meta_key = 'apex_course_template_events'");
    ?>
</div>