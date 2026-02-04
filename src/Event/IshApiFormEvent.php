<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class IshApiFormEvent.
 */
class IshApiFormEvent extends Event
{
    public const NODE_FORM_ALTER = 'ish_api.node.form.alter';

    /**
     * @param array<mixed> $form
     */
    public function __construct(private array &$form, private readonly FormStateInterface $formState, private readonly string $formId)
    {
    }

    /**
     * @return array<mixed, mixed>
     */
    public function &getForm(): array
    {
        return $this->form;
    }

    /**
     *
     */
    public function getFormState(): FormStateInterface
    {
        return $this->formState;
    }

    /**
     *
     */
    public function getFormId(): string
    {
        return $this->formId;
    }

}
