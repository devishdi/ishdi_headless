<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\EventSubscriber;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\ishdi_headless\Event\IshApiFormEvent;
use Drupal\ishdi_headless\IshCore\IshPath;
use Drupal\node\Form\NodeForm;
use Drupal\node\NodeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class IshContentFormAlter.
 */
final class IshContentFormAlter implements EventSubscriberInterface
{
    use StringTranslationTrait;

    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            IshApiFormEvent::NODE_FORM_ALTER => 'onNodeFormAlter',
        ];
    }

    public function onNodeFormAlter(IshApiFormEvent $event): void
    {
        $form = &$event->getForm();
        $formState = $event->getFormState()->getformObject();

        if (!$formState instanceof NodeForm) {
            return;
        }

        $ishPath = self::getIshPath();

        /** @var NodeInterface $node */
        $node = $formState->getEntity();
        $id = (string) $node->id();

        $pathInfo = [];
        if ($id) {
            $pathInfo = $ishPath->getPathInfoById((string) $id);
        }

        $form['app_api_path'] = [
            '#type' => 'textfield',
            '#title' => 'Path',
            '#default_value' => $pathInfo['path'] ?? '',
            '#size' => 60,
            '#maxlength' => 128,
            '#pattern' => '(\/|\/[A-Za-z][A-Za-z0-9\/\-_]*)',
            '#required' => true,
        ];


        $form['#validate'][] = 'Drupal\ishdi_headless\EventSubscriber\IshContentFormAlter:nodeFormValidate';
        $form['actions']['submit']['#submit'][] = 'Drupal\ishdi_headless\EventSubscriber\IshContentFormAlter::nodeFormSubmit';
    }

    /**
     * @param array<mixed> $form
     */
    public static function nodeFormSubmit(array &$form, FormStateInterface $formState): void
    {
        $formObject = $formState->getformObject();
        $ishPath = self::getIshPath();

        if (!$formObject instanceof NodeForm) {
            return;
        }

        $entity = $formObject->getEntity();
        $id = $entity->id();
        $path = $formState->getValue('app_api_path');

        $pathInfo = [
            'path' => $path,
            'bundle' => $entity->bundle(),
            'type' => 'node',
            'id' => $id,
        ];

        $ishPath->savePath($pathInfo, $id);
    }

    /**
     * @param array<mixed> $form
     */
    public static function nodeFormValidate(array &$form, FormStateInterface $formState): void
    {
        $formObject = $formState->getformObject();
        $ishPath = self::getIshPath();

        if (!$formObject instanceof NodeForm) {
            return;
        }

        $id = $formObject->getEntity()->id();
        $path = $formState->getValue('app_api_path');

        $id = (string) $id;
        $pathInfo = $ishPath->getPathInfo($path);

        if (!empty($pathInfo) && $pathInfo['id'] !== $id) {
            $formState->setErrorByName('app_api_path', 'Path Exist.');
        }
    }

    private static function getIshPath(): IshPath
    {
        return \Drupal::service('ishdi_headless.app_path');
    }

}
