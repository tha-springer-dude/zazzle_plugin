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
            echo '<strong>' . esc_html($title) . '</strong><br>';
            echo '<a href="' . esc_url($link) . '" target="_blank">View product</a>';
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