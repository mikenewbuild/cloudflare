<?php

require './src/bootstrap.php';

use Mikenewbuild\Cloudflare\Zone;
use Mikenewbuild\Cloudflare\ApiClient;

/**
 * PLEASE NOTE: This is a destructive script. Use with caution.
 */

$domain_name = config('domain_name');
$dry_run = config('dry_run');
$api = new ApiClient;

$verify = $api->verify();

echo $verify->messages();

if (!$verify->success) {
    die;
}

$zone = new Zone($domain_name);
$records = $zone->records();
$records_count = count($zone->records());

echo PHP_EOL . "Displaying {$records_count} records of {$zone->records_total()} total." . PHP_EOL . PHP_EOL;

if ($dry_run) {
    echo '***' . PHP_EOL;
    echo 'This is a dry run, no records will be removed.' . PHP_EOL;
    echo '***' . PHP_EOL . PHP_EOL;
}
echo 'X = delete record' . PHP_EOL;
echo '. = preserve record' . PHP_EOL . PHP_EOL;

foreach ($records as $record) {

    if (!$zone->should_delete_record($record)) {
        echo ". \t";
    } else {
        echo "X ..... ";
        if (!$dry_run) {
            $delete = $zone->delete_record($record->id);
            if (!$delete->success) {
                echo PHP_EOL . PHP_EOL;
                echo "!! There was an issue trying to delete {$record->name}";
                echo PHP_EOL . PHP_EOL;
                echo $delete->messages();
                break;
            }
        }
    }

    $content = str_pad(substr($record->content, 0, 16), 16, " ", STR_PAD_RIGHT);

    echo "{$record->type} \t {$content} {$record->name}" . PHP_EOL;
}

echo PHP_EOL . "There is a total {$zone->records_total()} records remaining." . PHP_EOL;
