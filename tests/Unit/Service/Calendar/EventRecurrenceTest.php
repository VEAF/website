<?php

namespace App\Tests\Unit\Service\Calendar;

use App\Entity\Calendar\Event;
use App\Entity\User;
use App\Manager\Calendar\EventManager;
use App\Repository\Calendar\EventRepository;
use App\Service\Calendar\EventService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;

/**
 * Tests unitaires pour la logique de récurrence des événements.
 *
 * Ces tests vérifient les calculs de dates sans avoir besoin de la base de données.
 */
class EventRecurrenceTest extends TestCase
{
    private EventService $service;

    protected function setUp(): void
    {
        // Créer des mocks pour les dépendances
        $eventRepository = $this->createMock(EventRepository::class);
        $eventManager = $this->createMock(EventManager::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $router = $this->createMock(RouterInterface::class);

        $this->service = new EventService(
            $eventRepository,
            $eventManager,
            $entityManager,
            $router
        );
    }

    // =========================================================================
    // Tests pour getNextEventDateTime()
    // =========================================================================

    public function testGetNextEventDateTimeReturnsNullForNoRepeat(): void
    {
        $event = $this->createEvent(Event::REPEAT_NONE, new \DateTime('2024-01-15 21:00:00'));

        $result = $this->service->getNextEventDateTime($event);

        $this->assertNull($result);
    }

    public function testGetNextEventDateTimeForWeeklyRepeat(): void
    {
        // Lundi 15 janvier 2024 à 21h
        $event = $this->createEvent(Event::REPEAT_DAY_OF_WEEK, new \DateTime('2024-01-15 21:00:00'));

        $result = $this->service->getNextEventDateTime($event);

        $this->assertNotNull($result);
        $this->assertEquals('2024-01-22', $result->format('Y-m-d'));
        $this->assertEquals('21:00:00', $result->format('H:i:s'));
    }

    public function testGetNextEventDateTimeForMonthlyRepeat(): void
    {
        // 15 janvier 2024 à 20h
        $event = $this->createEvent(Event::REPEAT_DAY_OF_MONTH, new \DateTime('2024-01-15 20:00:00'));

        $result = $this->service->getNextEventDateTime($event);

        $this->assertNotNull($result);
        $this->assertEquals('2024-02-15', $result->format('Y-m-d'));
        $this->assertEquals('20:00:00', $result->format('H:i:s'));
    }

    public function testGetNextEventDateTimeForMonthlyRepeatAtEndOfMonth(): void
    {
        // 31 janvier 2024 - que se passe-t-il en février ?
        $event = $this->createEvent(Event::REPEAT_DAY_OF_MONTH, new \DateTime('2024-01-31 20:00:00'));

        $result = $this->service->getNextEventDateTime($event);

        $this->assertNotNull($result);
        // PHP gère ça en roulant sur le mois suivant (2 ou 3 mars selon l'année)
        $this->assertEquals('2024-03', $result->format('Y-m'));
    }

    public function testGetNextEventDateTimeForNthWeekdayOfMonth(): void
    {
        // 3ème dimanche de janvier 2024 = 21 janvier
        $event = $this->createEvent(
            Event::REPEAT_NTH_WEEK_DAY_OF_MONTH,
            new \DateTime('2024-01-21 21:00:00')
        );

        $result = $this->service->getNextEventDateTime($event);

        $this->assertNotNull($result);
        // 3ème dimanche de février 2024 = 18 février
        $this->assertEquals('2024-02-18', $result->format('Y-m-d'));
        $this->assertEquals('21:00:00', $result->format('H:i:s'));
    }

    public function testGetNextEventDateTimeForFirstMondayOfMonth(): void
    {
        // 1er lundi de janvier 2024 = 1er janvier
        $event = $this->createEvent(
            Event::REPEAT_NTH_WEEK_DAY_OF_MONTH,
            new \DateTime('2024-01-01 20:00:00')
        );

        $result = $this->service->getNextEventDateTime($event);

        $this->assertNotNull($result);
        // 1er lundi de février 2024 = 5 février
        $this->assertEquals('2024-02-05', $result->format('Y-m-d'));
    }

    public function testGetNextEventDateTimeForSecondTuesdayOfMonth(): void
    {
        // 2ème mardi de janvier 2024 = 9 janvier
        $event = $this->createEvent(
            Event::REPEAT_NTH_WEEK_DAY_OF_MONTH,
            new \DateTime('2024-01-09 19:30:00')
        );

        $result = $this->service->getNextEventDateTime($event);

        $this->assertNotNull($result);
        // 2ème mardi de février 2024 = 13 février
        $this->assertEquals('2024-02-13', $result->format('Y-m-d'));
        $this->assertEquals('19:30:00', $result->format('H:i:s'));
    }

    public function testGetNextEventDateTimePreservesTime(): void
    {
        $event = $this->createEvent(Event::REPEAT_DAY_OF_WEEK, new \DateTime('2024-01-15 21:30:45'));

        $result = $this->service->getNextEventDateTime($event);

        $this->assertEquals('21:30:45', $result->format('H:i:s'));
    }

    // =========================================================================
    // Tests pour isNeededToCreateNextEvent()
    // =========================================================================

    public function testIsNeededToCreateNextEventReturnsFalseForNoRepeat(): void
    {
        $event = $this->createEvent(Event::REPEAT_NONE, new \DateTime('tomorrow'));

        $result = $this->service->isNeededToCreateNextEvent($event);

        $this->assertFalse($result);
    }

    public function testIsNeededToCreateNextEventReturnsTrueWithin32Days(): void
    {
        // Événement dans 20 jours -> prochain événement dans 27 jours (< 32)
        $startDate = (new \DateTime('now'))->modify('+20 days');
        $event = $this->createEvent(Event::REPEAT_DAY_OF_WEEK, $startDate);

        $result = $this->service->isNeededToCreateNextEvent($event);

        $this->assertTrue($result);
    }

    public function testIsNeededToCreateNextEventReturnsFalseAfter32Days(): void
    {
        // Événement dans 30 jours -> prochain événement dans 37 jours (> 32)
        $startDate = (new \DateTime('now'))->modify('+30 days');
        $event = $this->createEvent(Event::REPEAT_DAY_OF_WEEK, $startDate);

        $result = $this->service->isNeededToCreateNextEvent($event);

        $this->assertFalse($result);
    }

    public function testIsNeededToCreateNextEventForMonthlyEvent(): void
    {
        // Événement dans 10 jours -> prochain événement dans ~40 jours (> 32)
        $startDate = (new \DateTime('now'))->modify('+10 days');
        $event = $this->createEvent(Event::REPEAT_DAY_OF_MONTH, $startDate);

        $result = $this->service->isNeededToCreateNextEvent($event);

        // Dépend de la date actuelle dans le mois
        // Le prochain événement mensuel sera dans environ 1 mois + 10 jours
        $nextDate = $this->service->getNextEventDateTime($event);
        $interval = (new \DateTime('now'))->diff($nextDate);

        $this->assertEquals($interval->days <= 32, $result);
    }

    // =========================================================================
    // Tests pour les constantes de l'entité Event
    // =========================================================================

    public function testEventRepeatConstants(): void
    {
        $this->assertEquals(0, Event::REPEAT_NONE);
        $this->assertEquals(1, Event::REPEAT_DAY_OF_WEEK);
        $this->assertEquals(2, Event::REPEAT_DAY_OF_MONTH);
        $this->assertEquals(3, Event::REPEAT_NTH_WEEK_DAY_OF_MONTH);
    }

    public function testEventTypeConstants(): void
    {
        $this->assertEquals(1, Event::EVENT_TYPE_TRAINING);
        $this->assertEquals(2, Event::EVENT_TYPE_MISSION);
        $this->assertEquals(3, Event::EVENT_TYPE_OPEX);
        $this->assertEquals(4, Event::EVENT_TYPE_MEETING);
        $this->assertEquals(5, Event::EVENT_TYPE_MAINTENANCE);
        $this->assertEquals(6, Event::EVENT_TYPE_ATC);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function createEvent(int $repeatType, \DateTime $startDate): Event
    {
        $owner = $this->createMock(User::class);

        $event = new Event();
        $event->setTitle('Test Event');
        $event->setType(Event::EVENT_TYPE_TRAINING);
        $event->setStartDate($startDate);
        $event->setEndDate((clone $startDate)->modify('+2 hours'));
        $event->setRepeatEvent($repeatType);
        $event->setSimDcs(true);

        // Utiliser reflection pour setOwner car c'est requis
        $reflection = new \ReflectionClass($event);
        $property = $reflection->getProperty('owner');
        $property->setAccessible(true);
        $property->setValue($event, $owner);

        return $event;
    }
}
