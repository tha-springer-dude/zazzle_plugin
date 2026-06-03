<?php
if (!defined('ABSPATH')) exit;

function zazzle_register_menu() {
    add_options_page(
        'Zazzle Settings',
        'Zazzle',
        'manage_options',
        'zazzle-settings',
        'zazzle_settings_page'
    );
}

function zazzle_settings_page() {
    $opts = get_option('zazzle_settings');
    ?>
    <div class="wrap">
        <h1>Zazzle Settings</h1>

        <form method="post" action="options.php">
            <?php settings_fields('zazzle_settings_group'); ?>

            <!-- Store (editable) -->
            <input type="text"
                   name="zazzle_settings[store]"
                   value="<?php echo esc_attr($opts['store'] ?? ''); ?>"
                   placeholder="store name">

            <br><br>

            <!-- Items (editable) -->
            <textarea name="zazzle_settings[items]" rows="5"
                      placeholder="collection:xyz"><?php echo esc_textarea($opts['items'] ?? ''); ?></textarea>

            <?php submit_button(); ?>
        </form>

        <hr>

        <!-- OUTPUT (read-only preview) -->
        <h2>Saved Values</h2>

        <p><strong>Store:</strong>
            <?php echo esc_html($opts['store'] ?? ''); ?>
        </p>

        <p><strong>Items:</strong><br>
            <?php echo nl2br(esc_html($opts['items'] ?? '')); ?>
        </p>

    </div>
    <?php
}

function zazzle_register_settings() {   
    register_setting('zazzle_settings_group', 'zazzle_settings');
}