<?php

namespace Mikenewbuild\Cloudflare;

use Mikenewbuild\Cloudflare\Response;
use Mikenewbuild\Cloudflare\ApiClient;

class Zone
{
    protected ApiClient $api;

    protected string $domain;

    protected array $deletable_types;

    protected array $preserve_records;

    protected ?Response $attributes = null;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
        $this->api = new ApiClient;
        $this->deletable_types = config('deletable_record_types');
        $this->preserve_records = config('preserve_records');
    }

    public function refresh(): self
    {
        $this->attributes = $this->api->findZone($this->domain);

        return $this;
    }

    public function attributes(): Response
    {
        if ($this->attributes === NULL) {
            $this->refresh();
        }

        return $this->attributes;
    }

    public function id(): string
    {
        return $this->attributes()->id;
    }

    public function fetchRecords(): Response
    {
        return $this->api->get("zones/{$this->id()}/dns_records");
    }

    public function records(): array
    {
        $records = $this->fetchRecords()->result;

        return array_map(fn ($record) => new Response($record), $records);
    }

    public function records_total(): string
    {
        return $this->fetchRecords()->result_info->total_count;
    }

    public function should_delete_record(Response $record): bool
    {
        if ($this->deletable_record_name($record)) {
            return false;
        }

        return $this->deletable_record_type($record);
    }

    public function deletable_record_type($record): bool
    {
        if ($this->deletable_types === ['*']) {
            return true;
        }

        return in_array($record->type, $this->deletable_types);
    }

    public function deletable_record_name($record): bool
    {
        return in_array($record->name, $this->preserve_records());
    }

    public function preserve_records(): array
    {
        return array_merge([$this->domain], $this->preserve_records);
    }

    public function delete_record(string $id): Response
    {
        return $this->api->delete("zones/{$this->id()}/dns_records/{$id}");
    }
}
