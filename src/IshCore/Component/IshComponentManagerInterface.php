<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore\Component;

/**
 * Interface IshComponentManagerInterface.
 */
interface IshComponentManagerInterface
{

  /**
   * Add components to collector.
   */
    public function addComponent(IshComponentInterface $component, string $type): void;

  /**
   * Get component from collector.
   */
    public function getComponent(string $type): ?IshComponentInterface;

  /**
   * Get component key getter.
   *
   * @return array<string, string>
   */
    public function getAllComponents(): array;

}
