<?php

namespace App\Tests\Unit\Entity\Calendar;

use App\Entity\Calendar\Flight;
use PHPUnit\Framework\TestCase;

class FlightTest extends TestCase
{
    public function testMissionConstantsValues(): void
    {
        $this->assertEquals(0, Flight::MISSION_UNDEFINED);
        $this->assertEquals(1, Flight::MISSION_CAP);
        $this->assertEquals(2, Flight::MISSION_CAS);
        $this->assertEquals(3, Flight::MISSION_SEAD);
        $this->assertEquals(4, Flight::MISSION_ESCORT);
        $this->assertEquals(5, Flight::MISSION_TRANSPORT);
        $this->assertEquals(6, Flight::MISSION_RECON);
        $this->assertEquals(7, Flight::MISSION_CSAR);
        $this->assertEquals(8, Flight::MISSION_TANKER);
        $this->assertEquals(9, Flight::MISSION_AWACS);
        $this->assertEquals(10, Flight::MISSION_FAC);
    }

    public function testMissionsArrayContainsAllConstants(): void
    {
        $this->assertCount(11, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_UNDEFINED, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_CAP, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_CAS, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_SEAD, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_ESCORT, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_TRANSPORT, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_RECON, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_CSAR, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_TANKER, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_AWACS, Flight::MISSIONS);
        $this->assertArrayHasKey(Flight::MISSION_FAC, Flight::MISSIONS);
    }

    /**
     * @dataProvider missionDataProvider
     */
    public function testGetMissionAsStringReturnsCorrectLabel(int $mission, string $expected): void
    {
        $flight = new Flight();
        $flight->setMission($mission);

        $this->assertEquals($expected, $flight->getMissionAsString());
    }

    public function missionDataProvider(): array
    {
        return [
            'undefined' => [Flight::MISSION_UNDEFINED, 'non définie'],
            'CAP' => [Flight::MISSION_CAP, 'CAP'],
            'CAS' => [Flight::MISSION_CAS, 'CAS / Strike'],
            'SEAD' => [Flight::MISSION_SEAD, 'SEAD'],
            'Escort' => [Flight::MISSION_ESCORT, 'Escorte'],
            'Transport' => [Flight::MISSION_TRANSPORT, 'Transport'],
            'Recon' => [Flight::MISSION_RECON, 'Reconnaissance'],
            'CSAR' => [Flight::MISSION_CSAR, 'CSAR'],
            'Tanker' => [Flight::MISSION_TANKER, 'Ravitailleur'],
            'AWACS' => [Flight::MISSION_AWACS, 'AWACS'],
            'FAC' => [Flight::MISSION_FAC, 'FAC / JTAC'],
        ];
    }

    public function testGetMissionAsStringReturnsDefaultForNullMission(): void
    {
        $flight = new Flight();

        $this->assertEquals('non définie', $flight->getMissionAsString());
    }

    public function testGetMissionAsStringReturnsDefaultForUnknownMission(): void
    {
        $flight = new Flight();
        $flight->setMission(999);

        $this->assertEquals('non définie', $flight->getMissionAsString());
    }

    public function testSetAndGetDepartureBase(): void
    {
        $flight = new Flight();
        $flight->setDepartureBase('Al Dhafra');

        $this->assertEquals('Al Dhafra', $flight->getDepartureBase());
    }

    public function testSetAndGetReturnBase(): void
    {
        $flight = new Flight();
        $flight->setReturnBase('Incirlik');

        $this->assertEquals('Incirlik', $flight->getReturnBase());
    }

    public function testDepartureBaseDefaultsToNull(): void
    {
        $flight = new Flight();

        $this->assertNull($flight->getDepartureBase());
    }

    public function testReturnBaseDefaultsToNull(): void
    {
        $flight = new Flight();

        $this->assertNull($flight->getReturnBase());
    }

    public function testMissionDefaultsToNull(): void
    {
        $flight = new Flight();

        $this->assertNull($flight->getMission());
    }

    public function testSetMissionReturnsFluentInterface(): void
    {
        $flight = new Flight();
        $result = $flight->setMission(Flight::MISSION_CAP);

        $this->assertSame($flight, $result);
    }

    public function testSetDepartureBaseReturnsFluentInterface(): void
    {
        $flight = new Flight();
        $result = $flight->setDepartureBase('Kutaisi');

        $this->assertSame($flight, $result);
    }

    public function testSetReturnBaseReturnsFluentInterface(): void
    {
        $flight = new Flight();
        $result = $flight->setReturnBase('Kutaisi');

        $this->assertSame($flight, $result);
    }
}
