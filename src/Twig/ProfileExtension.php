<?php

namespace App\Twig;

use App\Component\Profile\Notification;
use App\Entity\User;
use App\Service\UserService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ProfileExtension extends AbstractExtension
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('profile_notifications', [$this, 'profileNotifications']),
            new TwigFilter('has_profile_notifications', [$this, 'hasProfileNotifications']),
            new TwigFilter('has_profile_notification', [$this, 'hasProfileNotification']),
        ];
    }

    /**
     * @return Notification[]|array
     */
    public function profileNotifications(?User $user): array
    {
        if (null === $user) {
            return [];
        }

        return $this->userService->getProfileNotifications($user);
    }

    public function hasProfileNotifications(?User $user): bool
    {
        if (null === $user) {
            return false;
        }

        return $this->userService->hasProfileNotifications($user);
    }

    public function hasProfileNotification(?User $user, int $type): bool
    {
        if (null === $user) {
            return false;
        }

        return $this->userService->hasProfileNotification($user, $type);
    }
}
