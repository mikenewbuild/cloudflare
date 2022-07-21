<?php

return [
    'api_key' => '',
    'api_base' => 'https://api.cloudflare.com/client/v4/',
    'dry_run' => true,

    /* DELETING RECORDS */
    // Define any record types you want to delete, use ['*'] to delete all
    'deletable_record_types' => ['A', 'AAAA'],
    // Define the domain name you want to delete records from
    'domain_name' => '',
    // an array of full domains to preserve eg. ['www.domain.com', 'staging.domain.com']
    'preserve_records' => [],
];
