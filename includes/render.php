<?php
function zazzle_render($atts) {



    $atts = shortcode_atts([
        'collection' => '',
        'layout' => 'list'
    ], $atts);

    $collection_id = trim($atts['collection']);
    $layout = $atts['layout'];
    
    if (empty($collection_id)) {
        return 'No collection ID.';
    }

    $rss_url = "https://feed.zazzle.com/collections/" . $collection_id . "/rss";

    $response = wp_remote_get($rss_url);

    if (is_wp_error($response)) {
        return 'Error fetching data.';
    }

    $body = wp_remote_retrieve_body($response);
    $xml = simplexml_load_string($body);

    if (!$xml || !isset($xml->channel->item)) {
        return 'No products found.';
    }

    ob_start();

?>

<div class="zazzle-header">
<h2 class="zazzle-heading">Personalized Vienna Souvenirs</h2>
<p class="zazzle-subheading">Create a unique memory with your name & year</p>

    <div class="zazzle-features">

        <div class="zazzle-feature">
            <div class="zazzle-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2f4f3f" stroke-width="2">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                </svg>
            </div>
            <div>
                <strong class="zazzle-title">Customizable</strong>
                <span class="zazzle-subtitle">Add your name & year</span>
            </div>
        </div>

        <div class="zazzle-feature">
            <div class="zazzle-icon">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2f4f3f" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    <!-- outer badge -->
    <circle cx="12" cy="8" r="4.5"></circle>

    <!-- inner detail -->
    <circle cx="12" cy="8" r="1.5"></circle>

    <!-- ribbon tails -->
    <path d="M9 13 L9 20 L12 18 L15 20 L15 13"></path>
</svg>
            </div>
            <div>
                <strong class="zazzle-title">High Quality</strong>
                <span class="zazzle-subtitle">Designed with love</span>
            </div>
        </div>

        <div class="zazzle-feature">
            <div class="zazzle-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2f4f3f" stroke-width="2">
                    <rect x="1" y="3" width="15" height="13"/>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
            </div>
            <div>
                <strong class="zazzle-title">Worldwide Shipping</strong>
                <span class="zazzle-subtitle">Fast delivery</span>
            </div>
        </div>

    </div>
</div>
<hr class="zazzle-separator">

<?php

    // ✅ WRAPPER (THIS WAS MISSING)
    echo '<div class="zazzle ' . esc_attr($layout) . '">';


    foreach ($xml->channel->item as $product) {

        $title = (string) $product->title;
        $link  = (string) $product->link;

        $namespaces = $product->getNamespaces(true);
        $media = $product->children($namespaces['media']);
        $image = (string) $media->content->attributes()->url;

        if ($layout === 'side') {

            echo '<div class="zazzle-item side">';
            echo '<div class="zazzle-image">';
            echo '<img src="' . esc_url($image) . '">';
            echo '</div>';

            echo '<div class="zazzle-content">';
            echo '<strong>' . esc_html($title) . '</strong><br>';
            echo '<a href="' . esc_url($link) . '" target="_blank">View product</a>';
            echo '</div>';
            echo '</div>';

        }  elseif ($layout === 'grid') {

            echo '<div class="zazzle-item grid">';
            echo '<img src="' . esc_url($image) . '">';

            echo '<div class="zazzle-card-content">'; // 👈 ADD THIS
            echo '<strong>' . esc_html($title) . '</strong><br>';

            $btn = function_exists('pll__') ? pll__('Personalize') : 'Personalize';
            echo '<a class="zazzle-btn" href="' . esc_url($link) . '" target="_blank">' . esc_html($btn) . '</a>';

            echo '</div>'; // 👈 ADD THIS

            echo '</div>';

        } else {

            echo '<div class="zazzle-item list">';
            echo '<img src="' . esc_url($image) . '"><br>';
            echo '<strong>' . esc_html($title) . '</strong><br>';
            echo '<a href="' . esc_url($link) . '" target="_blank">View product</a>';
            echo '</div>';

        }
    }

    // ✅ CLOSE WRAPPER
    echo '</div>';
    
    return ob_get_clean();
}