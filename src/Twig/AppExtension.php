<?php

namespace App\Twig;

use App\Entity\User;
use App\Service\MarkdownHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function __construct(private MarkdownHelper $markdownHelper)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('role_name', [$this, 'getRoleName']),
            new TwigFilter(
                'md2html', [
                $this,
                'markdownToHtml',
            ], ['is_safe' => ['html']]
            ),
        ];
    }

    public function getRoleName($value): string
    {
        return array_search($value, User::ROLES, true);
    }

    public function markdownToHtml(string $content): string
    {
        return $this->markdownHelper->parse($content);
    }
}
