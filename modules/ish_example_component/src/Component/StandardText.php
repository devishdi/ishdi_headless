<?php

declare(strict_types=1);

namespace Drupal\ish_example_component\Component;

use Drupal\ishdi_headless\IshCore\Component\IshComponentBase;

/**
 * StandardText service.
 */
class StandardText extends IshComponentBase
{
    public const COMPONENT_KEY = 'standardText';

    /**
     * {@inheritDoc}
     */
    public function render(): array
    {
        $this->content = [
            'title' => $this->fetchFirstValue($this->component, 'field_ish_title'),
            'description' => $this->getComponentBody($this->component, 'field_ish_body'),
        ];

        return $this->content;
    }

}
