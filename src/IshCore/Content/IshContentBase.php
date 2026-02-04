<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore\Content;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Class IshContentBase.
 */
abstract class IshContentBase
{
    public string $language = 'en';

    protected function fetchFirstValue(ContentEntityInterface $field, string $type): string
    {
        $result = '';
        $values = $field->get($type)->getValue();

        if (\is_array($values)) {
            $result = reset($values);
            $result = !empty($result['value']) ? $result['value'] : '';
        }

        return $result;
    }

    /**
     * @return array<int|string, string>
     */
    protected function fetchValue(ContentEntityInterface $field, string $type): array
    {
        $result = [];
        $values = $field->get($type)->getValue();

        if (\is_array($values)) {
            foreach ($values as $value) {
                $result[] = !empty($value['value']) ? $value['value'] : '';
            }
        }

        return $result;
    }

    /**
     * @return ParagraphInterface[]
     */
    protected function loadParagraphs(ContentEntityInterface $field, string $type): array
    {
        $fieldValue = $field->get($type)->getValue();

        if (!\is_array($fieldValue)) {
            return [];
        }
        $targetIds = array_column($fieldValue, 'target_id');

        $paragraphs = Paragraph::loadMultiple($targetIds);

        foreach ($paragraphs as $key => $paragraph) {
            $translation = $paragraph;
            if ($paragraph->hasTranslation($this->language)) {
                $translation = $paragraph->getTranslation($this->language);
            }

            $paragraphs[$key] = $translation;
        }

        /* @var ParagraphInterface[] $paragraphs */
        return $paragraphs;
    }

    protected function getComponentBody(ContentEntityInterface $field, string $type): string
    {
        $desc = $this->fetchFirstValue($field, $type);
        if (\is_string($desc)) {
            $content = preg_replace('/<([^<\/>]*)>([\s]*?|(?R))<\/\1>/imsU', '', $desc);
            if (\is_string($content)) {
                $content = preg_replace('/[\\n\\r]+/', '', $content);
            }
        }

        return $content ?: '';
    }

}
