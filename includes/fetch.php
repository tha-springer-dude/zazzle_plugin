<?php

function zazzle_parse_items($items_raw) {
    $lines = explode("\n", $items_raw);
    $result = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if (!$line) continue;

        if (str_starts_with($line, 'collection_url:')) {
            $url = str_replace('collection_url:', '', $line);
            $result[] = [
                'type' => 'collection_url',
                'url'  => trim($url),
            ];
        }

        if (str_starts_with($line, 'collection_rss:')) {
    $url = str_replace('collection_rss:', '', $line);
    $result[] = [
        'type' => 'collection_rss',
        'url'  => trim($url),
    ];
}

        if (str_starts_with($line, 'product:')) {
            $id = str_replace('product:', '', $line);
            $result[] = [
                'type' => 'product',
                'id'   => trim($id),
            ];
        }
    }

    return $result;
}


function zazzle_fetch_collection($url) {

    $response = wp_remote_get($url);

    echo '<pre>';
    print_r($response);
    echo '</pre>';

    if (is_wp_error($response)) {
        return [];
    }

    return wp_remote_retrieve_body($response);
}