<?php

namespace App\Tests\Integration\Twig;

use App\Tests\Integration\AbstractIntegrationTestCase;
use Twig\Environment;

/**
 * Tests d'intégration pour les templates calendrier.
 *
 * Ces tests vérifient que la fonction time_diff de KnpTimeBundle est disponible
 * et fonctionne correctement dans les templates Twig.
 */
class CalendarTemplateTest extends AbstractIntegrationTestCase
{
    private Environment $twig;

    protected function setUp(): void
    {
        parent::setUp();
        $this->twig = $this->getService(Environment::class);
    }

    // =========================================================================
    // Tests de la fonction time_diff
    // =========================================================================

    public function testTimeDiffFunctionIsAvailable(): void
    {
        $function = $this->twig->getFunction('time_diff');

        $this->assertNotNull($function, 'La fonction time_diff devrait être disponible dans Twig');
    }

    public function testTimeDiffFunctionReturnsStringForFutureDate(): void
    {
        $futureDate = new \DateTime('+2 days');

        $result = $this->twig->createTemplate('{{ time_diff(date) }}')->render(['date' => $futureDate]);

        $this->assertNotEmpty($result);
        $this->assertIsString($result);
    }

    public function testTimeDiffFunctionReturnsStringForPastDate(): void
    {
        $pastDate = new \DateTime('-3 days');

        $result = $this->twig->createTemplate('{{ time_diff(date) }}')->render(['date' => $pastDate]);

        $this->assertNotEmpty($result);
        $this->assertIsString($result);
    }

    public function testTimeDiffFunctionContainsTimeInfo(): void
    {
        $futureDate = new \DateTime('+5 days');

        $result = $this->twig->createTemplate('{{ time_diff(date) }}')->render(['date' => $futureDate]);

        // Le résultat devrait contenir une indication temporelle (jours, heures, etc.)
        $this->assertMatchesRegularExpression('/\d+/', $result, 'Le résultat devrait contenir un nombre');
    }

    // =========================================================================
    // Tests du rendu des badges time_diff (simulation du template calendrier)
    // =========================================================================

    public function testCalendarBadgeLogicForFutureEvent(): void
    {
        $futureDate = new \DateTime('+2 days');

        $template = <<<TWIG
{% if "now"|date("U") < date|date("U") %}
    <span class="badge badge-pill badge-success">{{ time_diff(date) }}</span>
{% endif %}
TWIG;

        $html = $this->twig->createTemplate($template)->render(['date' => $futureDate]);

        $this->assertStringContainsString('badge-success', $html);
    }

    public function testCalendarBadgeLogicForPastEvent(): void
    {
        $pastDate = new \DateTime('-2 days');
        $endDate = new \DateTime('-1 day');

        $template = <<<TWIG
{% if "now"|date("U") < startDate|date("U") %}
    <span class="badge badge-pill badge-success">{{ time_diff(startDate) }}</span>
{% elseif "now"|date("U") < endDate|date("U") %}
    <span class="badge badge-pill badge-warning">en cours !</span>
{% else %}
    <span class="badge badge-pill badge-danger">{{ time_diff(startDate) }}</span>
{% endif %}
TWIG;

        $html = $this->twig->createTemplate($template)->render([
            'startDate' => $pastDate,
            'endDate' => $endDate,
        ]);

        $this->assertStringContainsString('badge-danger', $html);
    }

    public function testCalendarBadgeLogicForOngoingEvent(): void
    {
        $startDate = new \DateTime('-1 hour');
        $endDate = new \DateTime('+2 hours');

        $template = <<<TWIG
{% if "now"|date("U") < startDate|date("U") %}
    <span class="badge badge-pill badge-success">{{ time_diff(startDate) }}</span>
{% elseif "now"|date("U") < endDate|date("U") %}
    <span class="badge badge-pill badge-warning">en cours !</span>
{% else %}
    <span class="badge badge-pill badge-danger">{{ time_diff(startDate) }}</span>
{% endif %}
TWIG;

        $html = $this->twig->createTemplate($template)->render([
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        $this->assertStringContainsString('badge-warning', $html);
        $this->assertStringContainsString('en cours', $html);
    }
}
