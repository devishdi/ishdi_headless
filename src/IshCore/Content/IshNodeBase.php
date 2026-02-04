<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore\Content;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\ishdi_headless\IshCore\Component\IshComponentInterface;
use Drupal\ishdi_headless\IshCore\Component\IshComponentManager;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Class IshNodeBase.
 */
abstract class IshNodeBase extends IshContentBase
{
    public const COMPONENT_TYPE = '';

    public ?string $path = null;

    private NodeInterface $page;

    /**
     * @var array<string, mixed>
     */
    public array $params = [];

    /**
     * @var array<string, mixed>
     */
    public array $content = [];

    public function __construct(
        private readonly IshComponentManager $componentManager
    ) {
    }

    /**
     * Get formatted Node contents
     *
     * @param array<mixed> $params
     * @return array<mixed>
     */
    public function getContents(NodeInterface $node, string $path, array $params = []): array
    {
        $this->setPath($path);
        $this->setPage($node);
        $this->params = $params;
        $this->setLanuguage($node->language()->getId());
        $this->getMainContents();
        $this->loadComponents($this->page);

        return $this->content;
    }

    protected function getMainContents(): void
    {
        $this->content['info'] = ['path' => $this->path, 'pageTitle' => $this->page->getTitle(), 'language' => $this->language];
    }

    public function getComponentType(): string
    {
        return static::COMPONENT_TYPE;
    }

    /**
     * @param ParagraphInterface[] $components
     */
    private function setComponents(array $components, bool $regionCheck = false): void
    {
        foreach ($components as $component) {
            if ($component->hasTranslation($this->language)) {
                /**
                 * @var ParagraphInterface $component
                 */
                $component = $component->getTranslation($this->language);
            }
            $bundle = $component->bundle();

            $componentRender = $this->componentManager->getComponent($bundle);

            if ($componentRender instanceof IshComponentInterface) {
                $sectionKey = $this->getSectionName($componentRender->getKey());
                $componentRender->setComponent($component);
                $componentRender->setRequestedPage($this->page);
                $componentRender->setLanguage($this->language);
                $this->content['components'][$sectionKey] = $componentRender->render();
            }
        }
    }

    protected function loadComponents(ContentEntityInterface $page): void
    {
        $this->content['components'] = [];
        // content
        $components = $this->loadParagraphs($page, static::COMPONENT_TYPE);
        $this->setComponents($components);
    }

    private function getSectionName(string $sectionName): string
    {
        $i = 1;
        $sectionKey = '';
        while ($i > 0) {
            $sectionKey = $sectionName . '_' . $i;
            if (\array_key_exists($sectionKey, $this->content['components'])) {
                ++$i;
            } else {
                $i = 0;
            }
        }

        return $sectionKey;
    }

    public function getPage(): NodeInterface
    {
        return $this->page;
    }

    protected function setPage(NodeInterface $page): void
    {
        $this->page = $page;
    }

    public function setParams(string $name, mixed $value): void
    {
        $this->params[$name] = $value;
    }

    /**
     * Get page params
     *
     * @return array<mixed>
     */
    public function getParams(): array
    {
        return $this->params ?? [];
    }

    public function getLanuguage(): string
    {
        return $this->language;
    }

    public function setLanuguage(string $language): void
    {
        $this->language = $language;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

}
