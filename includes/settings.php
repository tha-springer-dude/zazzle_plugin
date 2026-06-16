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
        <h1>Zazzle</h1>

        <h2>Verwendung</h2>

        <p>Shortcode zum Anzeigen von Produkten:</p>

        <pre>[zazzle collection="COLLECTION_ID" layout="list"]</pre>
        <pre>[zazzle collection="COLLECTION_ID" layout="side"]</pre>

        <p><strong>Parameter:</strong></p>
        <ul>
            <li><strong>collection</strong> – ID der Zazzle Collection</li>
            <li><strong>layout</strong> –
                <ul>
                    <li><code>list</code> = untereinander</li>
                    <li><code>side</code> = horizontal scrollend</li>
                </ul>
            </li>
        </ul>

        <p><strong>Beispiel:</strong></p>
        <pre>[zazzle collection="119919024677499506" layout="side"]</pre>

        <hr>

        <?php /* --- FUTURE FORM (DISABLED) --- */ ?>

        <!--
        <form method="post" action="options.php">
            <?php settings_fields('zazzle_settings_group'); ?>

            <input type="text"
                   name="zazzle_settings[store]"
                   value="<?php echo esc_attr($opts['store'] ?? ''); ?>"
                   placeholder="store name">

            <br><br>

            <textarea name="zazzle_settings[items]" rows="5"
                      placeholder="collection:xyz"><?php echo esc_textarea($opts['items'] ?? ''); ?></textarea>

            <?php submit_button(); ?>
        </form>
        -->

    </div>
    <?php
}

function zazzle_register_settings() {   
    register_setting('zazzle_settings_group', 'zazzle_settings');
}