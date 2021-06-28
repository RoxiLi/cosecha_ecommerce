<?php

declare(strict_types=1);

namespace RebelCode\Spotlight\Instagram\Feeds\Templates;

use Exception;
use RebelCode\Spotlight\Instagram\SaaS\SaasResourceFetcher;

class FeedTemplatesProvider extends SaasResourceFetcher
{
    protected $templates = [];

    public function get(): array
    {
        if (!$this->templates) {
            try {
                $this->templates = parent::get();
            } catch (Exception $e) {}
        }

        return $this->templates;
    }
}
