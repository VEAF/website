<?php

namespace App\Tests\Integration\Service;

use App\Entity\User;
use App\Manager\Recruitment\EventManager;
use App\Service\UserService;
use App\Tests\Integration\AbstractIntegrationTestCase;

/**
 * Tests d'intégration pour UserService.
 *
 * Ces tests vérifient les notifications de profil avec la base de données.
 */
class UserServiceTest extends AbstractIntegrationTestCase
{
    private UserService $userServiceVeaf;
    private UserService $userService51eg;

    protected function setUp(): void
    {
        parent::setUp();

        $eventManager = $this->getService(EventManager::class);

        // Service configuré pour VEAF
        $this->userServiceVeaf = new UserService(
            $this->entityManager,
            $eventManager,
            'veaf'
        );

        // Service configuré pour 51eg (moins de vérifications)
        $this->userService51eg = new UserService(
            $this->entityManager,
            $eventManager,
            '51eg'
        );
    }

    // =========================================================================
    // Tests des notifications pour VEAF
    // =========================================================================

    public function testGetProfileNotificationsForUserWithoutSimulator(): void
    {
        $user = $this->createUser([
            'simDcs' => false,
            'simBms' => false,
            'status' => User::STATUS_MEMBER,
        ]);

        $notifications = $this->userServiceVeaf->getProfileNotifications($user);

        $this->assertNotEmpty($notifications);
        $this->assertStringContainsStringIgnoringCase('simulateur', $notifications[0]->getMessage());
    }

    public function testGetProfileNotificationsForUserWithDcsSimulator(): void
    {
        $user = $this->createUser([
            'simDcs' => true,
            'simBms' => false,
            'status' => User::STATUS_MEMBER,
            'discord' => 'user#1234',
            'forum' => 'username',
        ]);

        $notifications = $this->userServiceVeaf->getProfileNotifications($user);

        // Pas de notification "simulateur" car DCS est activé
        $hasSimNotification = false;
        foreach ($notifications as $notification) {
            if (false !== stripos($notification->getMessage(), 'simulateur')) {
                $hasSimNotification = true;
                break;
            }
        }

        $this->assertFalse($hasSimNotification, 'Ne devrait pas avoir de notification simulateur');
    }

    public function testGetProfileNotificationsForUserWithoutForum(): void
    {
        $user = $this->createUser([
            'simDcs' => true,
            'status' => User::STATUS_MEMBER,
            'forum' => null,
            'discord' => 'user#1234',
        ]);

        $notifications = $this->userServiceVeaf->getProfileNotifications($user);

        $hasForumNotification = false;
        foreach ($notifications as $notification) {
            if (false !== stripos($notification->getMessage(), 'forum')) {
                $hasForumNotification = true;
                break;
            }
        }

        $this->assertTrue($hasForumNotification, 'Devrait avoir une notification forum');
    }

    public function testGetProfileNotificationsForUserWithoutDiscord(): void
    {
        $user = $this->createUser([
            'simDcs' => true,
            'status' => User::STATUS_MEMBER,
            'forum' => 'username',
            'discord' => null,
        ]);

        $notifications = $this->userServiceVeaf->getProfileNotifications($user);

        $hasDiscordNotification = false;
        foreach ($notifications as $notification) {
            if (false !== stripos($notification->getMessage(), 'discord')) {
                $hasDiscordNotification = true;
                break;
            }
        }

        $this->assertTrue($hasDiscordNotification, 'Devrait avoir une notification discord');
    }

    public function testGetProfileNotificationsForUnknownUserStatus(): void
    {
        $user = $this->createUser([
            'simDcs' => true,
            'status' => User::STATUS_UNKNOWN,
            'discord' => 'user#1234',
            'forum' => 'username',
        ]);

        $notifications = $this->userServiceVeaf->getProfileNotifications($user);

        $hasCadetNotification = false;
        foreach ($notifications as $notification) {
            if (false !== stripos($notification->getMessage(), 'cadet')) {
                $hasCadetNotification = true;
                break;
            }
        }

        $this->assertTrue($hasCadetNotification, 'Devrait avoir une notification pour devenir cadet');
    }

    public function testGetProfileNotificationsForCompleteProfile(): void
    {
        $user = $this->createUser([
            'simDcs' => true,
            'status' => User::STATUS_MEMBER,
            'discord' => 'user#1234',
            'forum' => 'username',
        ]);

        $notifications = $this->userServiceVeaf->getProfileNotifications($user);

        $this->assertEmpty($notifications, 'Un profil complet ne devrait pas avoir de notifications');
    }

    // =========================================================================
    // Tests des notifications pour 51eg (moins de vérifications)
    // =========================================================================

    public function testGetProfileNotificationsFor51egWithoutSimulator(): void
    {
        $user = $this->createUser([
            'simDcs' => false,
            'simBms' => false,
            'status' => User::STATUS_MEMBER,
        ]);

        $notifications = $this->userService51eg->getProfileNotifications($user);

        // 51eg ne vérifie pas le simulateur
        $hasSimNotification = false;
        foreach ($notifications as $notification) {
            if (false !== stripos($notification->getMessage(), 'simulateur')) {
                $hasSimNotification = true;
                break;
            }
        }

        $this->assertFalse($hasSimNotification, '51eg ne devrait pas vérifier le simulateur');
    }

    // =========================================================================
    // Tests du cache
    // =========================================================================

    public function testNotificationsAreCached(): void
    {
        $user = $this->createUser([
            'simDcs' => false,
            'status' => User::STATUS_MEMBER,
        ]);

        $notifications1 = $this->userServiceVeaf->getProfileNotifications($user);
        $notifications2 = $this->userServiceVeaf->getProfileNotifications($user);

        // Même instance (cache)
        $this->assertSame($notifications1, $notifications2);
    }

    // =========================================================================
    // Tests des méthodes count et has
    // =========================================================================

    public function testCountProfileNotifications(): void
    {
        $user = $this->createUser([
            'simDcs' => false,
            'simBms' => false,
            'status' => User::STATUS_MEMBER,
            'discord' => null,
            'forum' => null,
        ]);

        $count = $this->userServiceVeaf->countProfileNotifications($user);

        // Au moins 3 notifications: pas de sim, pas de forum, pas de discord
        $this->assertGreaterThanOrEqual(3, $count);
    }

    public function testHasProfileNotifications(): void
    {
        $user = $this->createUser([
            'simDcs' => false,
            'status' => User::STATUS_MEMBER,
        ]);

        $this->assertTrue($this->userServiceVeaf->hasProfileNotifications($user));
    }

    public function testHasProfileNotificationsReturnsFalseForCompleteProfile(): void
    {
        $user = $this->createUser([
            'simDcs' => true,
            'status' => User::STATUS_MEMBER,
            'discord' => 'user#1234',
            'forum' => 'username',
        ]);

        $this->assertFalse($this->userServiceVeaf->hasProfileNotifications($user));
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function createUser(array $data): User
    {
        $user = new User();
        $user->setEmail($data['email'] ?? 'test'.uniqid().'@localhost');
        $user->setNickname($data['nickname'] ?? 'test'.uniqid());
        $user->setPassword('$2y$10$hashed_password_placeholder');
        $user->setRoles(['ROLE_USER']);
        $user->setSimDcs($data['simDcs'] ?? false);
        $user->setSimBms($data['simBms'] ?? false);
        $user->setStatus($data['status'] ?? User::STATUS_UNKNOWN);
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());

        if (isset($data['discord'])) {
            $user->setDiscord($data['discord']);
        }
        if (isset($data['forum'])) {
            $user->setForum($data['forum']);
        }

        $this->persistAndFlush($user);

        return $user;
    }
}
