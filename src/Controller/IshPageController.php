<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ishdi_headless\IshCore\IshApiTrait;
use Drupal\ishdi_headless\IshCore\IshPageApi;
use Drupal\ishdi_headless\StrictRequestStack;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class IshPageController.
 */
final class IshPageController extends ControllerBase
{
    use IshApiTrait;

    public function __construct(
        protected IshPageApi $ishPageApi,
        protected StrictRequestStack $requestStack,
        protected LoggerInterface $logger,
    ) {
    }

  /**
   * {@inheritdoc}
   *
   * @return IshPageController|static
   */
    public static function create(ContainerInterface $container)
    {
      /** @var \Drupal\ishdi_headless\IshCore\IshPageApi $ishPageApi */
        $ishPageApi = $container->get('ishdi_headless.page_api');

      /** @var \Drupal\ishdi_headless\StrictRequestStack $requestStack */
        $requestStack = $container->get('ishdi_headless.strict_request');

      /** @var \Psr\Log\LoggerInterface $logger */
        $logger = $container->get('logger.channel.ishdi_headless');

        return new static($ishPageApi, $requestStack, $logger);
    }

  /**
   *
   */
    public function contentPage(): JsonResponse
    {
        $language = $this->languageManager()->getCurrentLanguage()->getId();
        $path = $this->requestStack->getCurrentRequest()->query->get('path');

        $params = $this->requestStack->getCurrentRequest()->query->get('params');
        $params = $this->decodeParams((string) $params);

        return $this->ishPageApi->getContents((string) $path, $language, $params);
    }

}
