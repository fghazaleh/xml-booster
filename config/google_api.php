<?php

/* --------------------------
 * Google API configs
 *
 *
 * */

return [
    'sheets' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect_url' => 'urn:ietf:wg:oauth:2.0:oob',
        'scopes' => [Google_Service_Sheets::DRIVE],
        'token_file' => 'storage/token.json'
    ]
];