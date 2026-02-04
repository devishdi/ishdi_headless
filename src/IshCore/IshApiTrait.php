<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use GuzzleHttp\Utils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait IshApiTrait.
 */
trait IshApiTrait
{
    public int $httpStatus = Response::HTTP_BAD_REQUEST;

    /**
     * @param resource|string|bool|null $content
     *
     * @return array<string,mixed>
     */
    public function jsonDecode($content): array
    {
        if (!empty($content) && \is_string($content)) {
            $params = Utils::jsonDecode($content, true);
        } else {
            throw new \RuntimeException('Invalid json');
        }

        return \is_array($params) ? $params : [];
    }

    /**
     * @param array<mixed> $content
     */
    public function jsonEncode(array $content): string
    {
        return Utils::jsonEncode($content);
    }

    /**
     * @param array<string, mixed> $response
     */
    public function jsonResponse(array $response, int $status): JsonResponse
    {
        $responseStatus = -1 === $status ? 200 : $status;
        $result = ['success' => $this->checkSuccessValue($status), 'data' => $response, 'status' => $responseStatus];

        return new JsonResponse($result, $responseStatus);
    }

    /**
     * @param array<string, mixed> $response
     */
    public function jsonCacheResponse(array $response, int $status): CacheableJsonResponse
    {
        $cache = $response['#cache'];
        unset($response['#cache']);
        $responseStatus = -1 === $status ? 200 : $status;

        $result = ['success' => $this->checkSuccessValue($status), 'data' => $response, '#cache' => $cache, 'status' => $responseStatus];

        $cachedResponse = new CacheableJsonResponse($result, $responseStatus);
        $cachedResponse->addCacheableDependency(CacheableMetadata::createFromRenderArray($result));

        return $cachedResponse;
    }

    public function checkSuccessValue(int $status): bool
    {
        return (bool) (Response::HTTP_OK === $status || Response::HTTP_NOT_FOUND === $status);
    }

    public function setHttpStatusSuccess(): void
    {
        $this->httpStatus = Response::HTTP_OK;
    }

    public function setHttpStatusNotValid(): void
    {
        $this->httpStatus = -1;
    }

    public function setHttpStatusValidation(): void
    {
        $this->httpStatus = Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    public function setHttpStatusNotFound(): void
    {
        $this->httpStatus = Response::HTTP_NOT_FOUND;
    }

    public function setHttpStatusFailed(): void
    {
        $this->httpStatus = Response::HTTP_BAD_REQUEST;
    }

    public function setHttpStatusForbidden(): void
    {
        $this->httpStatus = Response::HTTP_FORBIDDEN;
    }

    public function setHttpStatusUnauthorized(): void
    {
        $this->httpStatus = Response::HTTP_UNAUTHORIZED;
    }

    /**
     * @return array<mixed>
     */
    public function decodeParams(string $params = ''): array
    {
        return !empty($params) ? $this->jsonDecode(urldecode($params)) : [];
    }
}
