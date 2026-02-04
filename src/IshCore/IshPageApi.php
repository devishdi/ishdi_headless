<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore;

use Drupal\Core\Database\Connection;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\ishdi_headless\Exception\IshApiException;
use Drupal\ishdi_headless\IshCore\Content\IshPageContent;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class IshiAp.
 */
final class IshPageApi
{
    use StringTranslationTrait;
    use IshApiTrait;

    public function __construct(
        private readonly Connection $connection,
        protected LoggerInterface $logger,
        protected IshPageContent $pageContent
    ) {
    }

    /**
     * @param array<string, mixed> $params
     */
    public function getContents(string $path, string $language, array $params = [], ?string $user_id = null): JsonResponse
    {
        $content = [];
        $pathNotFound = '/';
        try {
            $page = $this->getNodePathByLanguage($path, $language);

            if ($page === null) {
                $path = $pathNotFound;
                $page = $this->getNodePathByLanguage($path, $language);
                if ($page === null) {
                    throw new IshApiException('404 page not configured or not exist');
                }
            }

            $content = $this->pageContent->render($page, $path, $params);

            if ($path === $pathNotFound) {
                $this->setHttpStatusNotFound();
            } else {
                $this->setHttpStatusSuccess();
            }
        } catch (IshApiException $e) {
            $path = $pathNotFound;
            $params['original_path'] = '';

            return $this->getContents($path, $language, $params, $user_id);
        } catch (BadRequestHttpException $e) {
            $this->httpStatus = Response::HTTP_FORBIDDEN;
            $this->logger->notice($e->getMessage(), [$path, $params, $language]);
        } catch (\Throwable $e) {
            $this->httpStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->logger->error($e->getMessage(), [$path, $params, $language]);
        }

        return $this->jsonResponse($content, $this->httpStatus);
    }

    public function getNodePathByLanguage(string $path, string $language): NodeInterface|null
    {
        try {
            $query = $this->connection->select('ish_api_path', 'p');
            $query->innerJoin('node_field_data', 'f', 'p.id = f.nid');
            $query
                ->fields('p')
                ->condition('p.path', $path)
                ->condition('f.langcode', $language);
            $query->condition('f.status', '1');

            $resultQuery = $query->execute();
            $pathObject = null !== $resultQuery ? $resultQuery->fetchAssoc() : false;

            if (!empty($pathObject)) {
                $node = Node::load($pathObject['id']);

                if ($node instanceof NodeInterface && $node->hasTranslation($language)) {
                    return $node->getTranslation($language);
                }
            } else {
                throw new IshApiException('Page Not Found');
            }
        } catch (\Throwable $e) {
            $this->logger->notice($e->getMessage());
        }

        return null;
    }

}
