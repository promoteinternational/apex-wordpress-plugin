<?php
//Determining the number of available seats - TODO: Rewrite to use portal participant count.
$availableSeats = $event->max_participants - $event->booked_participant_count;
?>
<div class="apex-courses__event">
    <div class="col-6">
        <div class="apex-courses__event-date">
            <?= $event->event_dates ?>
        </div>
        <?php if (!empty($venue_city)): ?>
            <div class="apex-courses__event-place">
                <?= $venue_city ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($availableSeats) && $availableSeats >= 1 && $displaySeats === 'yes'): ?>
            <div class="apex-courses__event-available">
                <?php _e('Available seats:', 'promote-apex-plugin') ?>
                <?= $availableSeats ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-6 text-right">
        <?php if (!empty($availableSeats) && $availableSeats >= 1): ?>
            <button type="button" class="btn apex-courses__event-button"
                    data-toggle="modal" data-target="#modal_<?= $event->id ?>">
                <?php _e('Apply Now', 'promote-apex-plugin') ?>
            </button>
            <?php
            //Connect Modal template
            require 'modal.php'
            ?>
        <?php endif; ?>
    </div>
    <?php if (empty($availableSeats) || $availableSeats == 0):  ?>
    <div class="col-12">
        <div class="alert alert-warning"> <?php _e('This event is fully booked', 'promote-apex-plugin') ?> </div>
    </div>
    <?php elseif ($availableSeats < $event->max_participants / 2): ?>
    <div class="col-12">
        <div class="apex-courses__event-few-places">
            <?php _e('Few seats remaining', 'promote-apex-plugin') ?>
        </div>
    </div>
    <?php endif;
    //If not empty form data, display message
    if (!empty($_POST['event_id']) && $_POST['event_id'] == $event->id):?>
        <div class="apex-courses__confirmation">
            <?php
            $api->addParticipant($_POST['first_name'], $_POST['last_name'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['country'], $_POST['city'], $_POST['address_1'], $_POST['address_2'], $_POST['zip_code'], isset($_POST['sector']) ? $_POST['sector'] : null, isset($_POST['title']) ? $_POST['title'] : null, $_POST['event_id']);

            // Update booked participant count in wp database.
            $success = $api->getSuccess();
            $trSuccess = __('Your application was successfully submitted!', 'promote-apex-plugin');
            $trError = __('Something went wrong with your application', 'promote-apex-plugin');
            if ($success) {
                $event->booked_participant_count = $event->booked_participant_count + 1;
                echo '<div class="alert alert-success">' . $trSuccess . '</div>';
                if (!empty($afterBooking)) {
                    $transactionId = md5($_POST['email'] . $post->post_name);
                    ?><script type="text/javascript">
                        course.transactionId="<?php echo $transactionId ?>";
                        <?php echo $afterBooking ?>
                    </script>
                    <?php
                }
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