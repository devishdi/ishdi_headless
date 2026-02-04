<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore\Component;

/**
 * IshComponentManager service.
 */
class IshComponentManager implements IshComponentManagerInterface
{
    /**
     * @var IshComponentInterface[]
     */
    public array $componentMap = [];

    public function addComponent(IshComponentInterface $component, string $type): void
    {
        $this->componentMap[$type] = $component;
    }

    public function getComponent(string $type): ?IshComponentInterface
    {
        return $this->componentMap[$type] ?? null;
    }

    public function getAllComponents(): array
    {
        $result = [];

        foreach ($this->componentMap as $key => $component) {
            $key = (string) $key;
            $result[$key] = $component->getKey();
        }

        return $result;
    }

}
