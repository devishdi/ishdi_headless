<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore\Content;

use Drupal\ishdi_headless\IshCore\Content\IshNodeBase;
use Drupal\node\NodeInterface;

/**
 * Class IshPageContent.
 */
class IshPageContent extends IshNodeBase
{
    public const string COMPONENT_TYPE = 'field_ish_components';

    /**
     * @param array<mixed> $params
     * @return array<mixed>
     */
    public function render(NodeInterface $node, string $path, array $params = []): array
    {
        return $this->getContents($node, $path, $params);
    }

}
