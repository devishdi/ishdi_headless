<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore\Component;

use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Interface IshComponentInterface.
 */
interface IshComponentInterface
{

  /**
   * Render component content.
   *
   * @return array<string, mixed>
   */
    public function render(): array;

  /**
   * Get component key for render array.
   */
    public function getKey(): string;

  /**
   * Set component.
   */
    public function setComponent(ParagraphInterface $component): void;

  /**
   * Set page object.
   */
    public function setRequestedPage(NodeInterface $page): void;

  /**
   * Set component language.
   */
    public function setLanguage(string $language): void;

}
