<?php

namespace App\EventListener;

use App\Repository\PageRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class BreadcrumbWikiSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private PageRepository $pageRepository,
        private CacheItemPoolInterface $cache,
        private Breadcrumbs $breadcrumbs,
        private UrlGeneratorInterface $urlGenerator,
        private string $wikiName,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $slug = $event->getRequest()->attributes->get('slug');

        if (!$slug) {
            return;
        }

        $page = $this->pageRepository->findOneBySlug($slug);

        if (!$page) {
            return;
        }

        $url = $this->urlGenerator->generate('wiki', ['slug' => $slug]);

        $text = 'default' === $slug ? $this->wikiName : $page->getTitle();

        try {
            $item = $this->cache->getItem('breadcrumbs');
        } catch (InvalidArgumentException) {
            return;
        }

        if ($item->isHit()) {
            $values = $item->get();
            if (array_key_exists($text, $values)) {
                unset($values[$text]);
            }
            $values[$text] = $url;
            foreach ($values as $itemText => $itemUrl) {
                $this->breadcrumbs->addItem($itemText, $itemUrl, [], false);
            }
            $item->set($values);
        } else {
            $item->set([$text => $url]);
            $this->breadcrumbs->addItem($text, $url, [], false);
        }

        $this->cache->save($item);
    }
}
