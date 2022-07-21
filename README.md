# Cloudflare Utils

This serves as a set of helper scripts to achieve tasks via the Cloudflare API.

# Setup

Install any dependencies and generate autoloader.

```bash
composer install
```

Copy the sample config file, and populate the values you need.

```bash
cp config.sample.php config.php
```

You can [generate an API token via the dashboard](https://dash.cloudflare.com/profile/api-tokens). It's recommended to create a very specific and short-lived token for the task at hand.

## Remove DNS Records

To remove DNS records from a domain in bulk, set up the required values in `config.php` and then run the script with:

```
php remove_dns_records.php
```

Be aware that this is a destructive script, so you should run it with `'dry_run' => true` first to check that it will remove the correct types of records. If there's a lot of records, you may need to run the script more than once when actually deleting records as there's no limit or pagination handling. Set the value to `false` to actually delete the records permanently.
