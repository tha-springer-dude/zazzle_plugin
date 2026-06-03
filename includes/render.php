<?php

function zazzle_render($atts) {

    $opts = get_option('zazzle_settings');
    $parsed = zazzle_parse_items($opts['items'] ?? '');

    ob_start();

    echo '<h3>Zazzle plugin works</h3>';

    if (!empty($parsed)) {

foreach ($parsed as $item) {

    echo '<div>';
    echo '<strong>Type:</strong> ' . esc_html($item['type']) . '<br>';

    if ($item['type'] === 'collection_url') {

        //$html = zazzle_fetch_collection($item['url']);

        $store = $opts['store'] ?? '';

        $rss_url = "https://www.zazzle.com/" . $store . "/rss";

        $html = zazzle_fetch_collection($rss_url);

        echo '<pre>';
        echo esc_html(substr($html, 0, 1000)); // preview only
        echo '</pre>';
    }

    if ($item['type'] === 'collection_rss') {

    //$response = wp_remote_get($item['url']);

 $store = $opts['store'] ?? '';
    $rss_url = "https://www.zazzle.com/" . $store . "/rss";

    $response = wp_remote_get($rss_url);

    if (!is_wp_error($response)) {

        $body = wp_remote_retrieve_body($response);

        $xml = simplexml_load_string($body);

        if ($xml && isset($xml->channel->item)) {

            foreach ($xml->channel->item as $product) {

                $title = (string) $product->title;
                $link  = (string) $product->link;

                // image
                $namespaces = $product->getNamespaces(true);
                $media = $product->children($namespaces['media']);
                $image = (string) $media->content->attributes()->url;

echo '<div class="zazzle-item">';

echo '<img src="' . esc_url($image) . '" style="max-width:150px;"><br>';

echo '<strong>' . esc_html($title) . '</strong><br>';

echo '<a href="' . esc_url($link) . '" target="_blank">View product</a>';

echo '</div>';
            }
        }
    }
}

    echo '</div><hr>';
}

    } else {
        echo 'No items found.';
    }

    return ob_get_clean();
}