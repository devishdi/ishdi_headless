<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore;

use Drupal\Core\Database\Connection;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class IshPath.
 */
final class IshPath
{
    use StringTranslationTrait;

    public const string TYPE_404 = 'page_404';

    public const string TYPE_500 = 'page_500';

    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @param array<string, mixed> $pathInfo
     * @param string|int|null $id
     */
    public function savePath(array $pathInfo, $id): void
    {
        if ($id) {
            $this->connection->merge('ish_api_path')
                ->keys(['id'], [$id])
                ->fields($pathInfo)
                ->execute();
        }
    }

    /**
     * @return array<string,mixed>
     */
    public function getPathInfoById(string $id): array
    {
        $query = $this->connection->select('ish_api_path', 'p');
        $resultQuery = $query->fields('p')->condition('p.id', $id)->execute();

        $returnData = null !== $resultQuery ? $resultQuery->fetchAssoc() : false;

        return \is_array($returnData) ? $returnData : [];
    }

    /**
     * @return array<string,mixed>
     */
    public function getPathInfo(string $path): array
    {
        $query = $this->connection->select('ish_api_path', 'p');
        $resultQuery = $query->fields('p')->condition('p.path', $path)->execute();

        $returnData = null !== $resultQuery ? $resultQuery->fetchAssoc() : false;

        return \is_array($returnData) ? $returnData : [];
    }

}
