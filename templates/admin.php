<div class="wrap">

    <?php settings_errors(); ?>

    <form method='post' action="options.php">
        <?php
        settings_fields('apex_plugin_group');
        do_settings_sections('promote_apex_plugin');
        submit_button();
        ?>
    </form>
</div>
