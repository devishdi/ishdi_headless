<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore\Component;

use Drupal\ishdi_headless\IshCore\Component\IshComponentInterface;
use Drupal\ishdi_headless\IshCore\Content\IshContentBase;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * IshComponentBase class.
 */
abstract class IshComponentBase extends IshContentBase implements IshComponentInterface
{
    public const COMPONENT_KEY = '';

    public ParagraphInterface $component;

    public NodeInterface $page;

    /**
     * @var array<string, mixed>
     */
    public array $content = [];

    public function getKey(): string
    {
        return static::COMPONENT_KEY;
    }

    public function setRequestedPage(NodeInterface $page): void
    {
        $this->page = $page;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    public function setComponent(ParagraphInterface $component): void
    {
        $this->component = $component;
    }

    public function render(): array
    {
        return [];
    }

}
