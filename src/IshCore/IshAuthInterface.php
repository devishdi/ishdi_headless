<?php

declare(strict_types=1);

namespace Drupal\ishdi_headless\IshCore;

/**
 * Interface IshAuthInterface.
 */
interface IshAuthInterface
{

  /**
   * Return token string for a valid user.
   *
   * @param array<string,int|string> $userData
   */
    public function getToken(array $userData): ?string;

  /**
   * Return token for an anonymous user.
   *
   * @param array<string,string> $userData
   */
    public function getAnonymousToken(array $userData): ?string;

  /**
   * Check token is valid for all user end points.
   */
    public function checkToken(string $user_id): bool;

  /**
   * Check token is valid for all anonymous end points including login and registration.
   */
    public function checkAnonymousToken(): bool;

}
