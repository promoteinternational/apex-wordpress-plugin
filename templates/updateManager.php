

<form method='post' action="options.php">
    <?php
    settings_fields('apex_api_group');
    do_settings_sections('api_update_manager');
    submit_button();
    ?>
</form>