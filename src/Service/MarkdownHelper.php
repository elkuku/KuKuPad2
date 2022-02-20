<?php

namespace App\Service;

use Psr\Cache\CacheItemPoolInterface;

class MarkdownHelper
{
    public function __construct(
        private MarkdownParser $markdown,
        private CacheItemPoolInterface $cache,
    ) {
    }

    public function parse(string $source): string
    {
        $item = $this->cache->getItem('markdown_'.md5($source));

        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }

    public function clearCache(): bool
    {
        return $this->cache->clear();
    }
}
