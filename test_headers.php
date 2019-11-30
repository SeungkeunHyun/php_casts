<?php
stream_context_set_default( [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);
    $headers = get_headers("http://pod.ssenhosting.com/rss/vivo119/vivo.xml");
    print_r($headers);
?>
