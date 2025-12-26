# OpenAPIClient-php

REST functions to be used for DCSServerBot.


## Installation & Usage

### Requirements

PHP 8.1 and later.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/OpenAPIClient-php/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new DcsServerBot\Api\CreditsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$nick = 'nick_example'; // string
$date = 'date_example'; // string
$campaign = 'campaign_example'; // string

try {
    $result = $apiInstance->creditsServerapiCreditsPost($nick, $date, $campaign);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditsApi->creditsServerapiCreditsPost: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *http://localhost*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*CreditsApi* | [**creditsServerapiCreditsPost**](docs/Api/CreditsApi.md#creditsserverapicreditspost) | **POST** /serverapi/credits | Campaign Credits
*CreditsApi* | [**squadronCreditsServerapiSquadronCreditsPost**](docs/Api/CreditsApi.md#squadroncreditsserverapisquadroncreditspost) | **POST** /serverapi/squadron_credits | Squadron Credits
*InfoApi* | [**currentServerServerapiCurrentServerGet**](docs/Api/InfoApi.md#currentserverserverapicurrentserverget) | **GET** /serverapi/current_server | Current Server
*InfoApi* | [**getuserServerapiGetuserPost**](docs/Api/InfoApi.md#getuserserverapigetuserpost) | **POST** /serverapi/getuser | User list
*InfoApi* | [**linkmeServerapiLinkmePost**](docs/Api/InfoApi.md#linkmeserverapilinkmepost) | **POST** /serverapi/linkme | Link Discord to DCS
*InfoApi* | [**playerSquadronsServerapiPlayerSquadronsPost**](docs/Api/InfoApi.md#playersquadronsserverapiplayersquadronspost) | **POST** /serverapi/player_squadrons | Player Squadrons
*InfoApi* | [**serverAttendanceServerapiServerAttendanceGet**](docs/Api/InfoApi.md#serverattendanceserverapiserverattendanceget) | **GET** /serverapi/server_attendance | Server Attendance Statistics
*InfoApi* | [**serversServerapiServersGet**](docs/Api/InfoApi.md#serversserverapiserversget) | **GET** /serverapi/servers | Server list
*InfoApi* | [**serverstatsServerapiServerstatsGet**](docs/Api/InfoApi.md#serverstatsserverapiserverstatsget) | **GET** /serverapi/serverstats | Server Statistics
*InfoApi* | [**squadronMembersServerapiSquadronMembersPost**](docs/Api/InfoApi.md#squadronmembersserverapisquadronmemberspost) | **POST** /serverapi/squadron_members | Squadron Members
*InfoApi* | [**squadronsServerapiSquadronsGet**](docs/Api/InfoApi.md#squadronsserverapisquadronsget) | **GET** /serverapi/squadrons | Squadron list
*StatisticsApi* | [**highscoreServerapiHighscoreGet**](docs/Api/StatisticsApi.md#highscoreserverapihighscoreget) | **GET** /serverapi/highscore | Highscore
*StatisticsApi* | [**leaderboardServerapiLeaderboardGet**](docs/Api/StatisticsApi.md#leaderboardserverapileaderboardget) | **GET** /serverapi/leaderboard | Leaderboard
*StatisticsApi* | [**modulestatsServerapiModulestatsPost**](docs/Api/StatisticsApi.md#modulestatsserverapimodulestatspost) | **POST** /serverapi/modulestats | Module Statistics
*StatisticsApi* | [**playerInfoServerapiPlayerInfoPost**](docs/Api/StatisticsApi.md#playerinfoserverapiplayerinfopost) | **POST** /serverapi/player_info | Player Information
*StatisticsApi* | [**statsServerapiStatsPost**](docs/Api/StatisticsApi.md#statsserverapistatspost) | **POST** /serverapi/stats | Player Statistics
*StatisticsApi* | [**topkdrServerapiTopkdrGet**](docs/Api/StatisticsApi.md#topkdrserverapitopkdrget) | **GET** /serverapi/topkdr | Top KDR
*StatisticsApi* | [**topkillsServerapiTopkillsGet**](docs/Api/StatisticsApi.md#topkillsserverapitopkillsget) | **GET** /serverapi/topkills | Top Kills
*StatisticsApi* | [**trapsServerapiTrapsPost**](docs/Api/StatisticsApi.md#trapsserverapitrapspost) | **POST** /serverapi/traps | Carrier Traps
*StatisticsApi* | [**trueskillServerapiTrueskillGet**](docs/Api/StatisticsApi.md#trueskillserverapitrueskillget) | **GET** /serverapi/trueskill | TrueSkill:tm:
*StatisticsApi* | [**weaponpkServerapiWeaponpkPost**](docs/Api/StatisticsApi.md#weaponpkserverapiweaponpkpost) | **POST** /serverapi/weaponpk | Weapon PK

## Models

- [CampaignCredits](docs/Model/CampaignCredits.md)
- [DailyPlayers](docs/Model/DailyPlayers.md)
- [ExtensionInfo](docs/Model/ExtensionInfo.md)
- [HTTPValidationError](docs/Model/HTTPValidationError.md)
- [Highscore](docs/Model/Highscore.md)
- [HighscoreEntry](docs/Model/HighscoreEntry.md)
- [LeaderBoard](docs/Model/LeaderBoard.md)
- [LinkMeResponse](docs/Model/LinkMeResponse.md)
- [MissionInfo](docs/Model/MissionInfo.md)
- [ModuleStats](docs/Model/ModuleStats.md)
- [PlayerEntry](docs/Model/PlayerEntry.md)
- [PlayerInfo](docs/Model/PlayerInfo.md)
- [PlayerSquadron](docs/Model/PlayerSquadron.md)
- [PlayerStats](docs/Model/PlayerStats.md)
- [PlaytimeEntry](docs/Model/PlaytimeEntry.md)
- [ServerAttendanceStats](docs/Model/ServerAttendanceStats.md)
- [ServerInfo](docs/Model/ServerInfo.md)
- [ServerStats](docs/Model/ServerStats.md)
- [SquadronCampaignCredit](docs/Model/SquadronCampaignCredit.md)
- [SquadronInfo](docs/Model/SquadronInfo.md)
- [TopKill](docs/Model/TopKill.md)
- [TopMission](docs/Model/TopMission.md)
- [TopModule](docs/Model/TopModule.md)
- [TopTheatre](docs/Model/TopTheatre.md)
- [TrapEntry](docs/Model/TrapEntry.md)
- [Trueskill](docs/Model/Trueskill.md)
- [UserEntry](docs/Model/UserEntry.md)
- [ValidationError](docs/Model/ValidationError.md)
- [ValidationErrorLocInner](docs/Model/ValidationErrorLocInner.md)
- [WeaponPK](docs/Model/WeaponPK.md)
- [WeatherInfo](docs/Model/WeatherInfo.md)

## Authorization
Endpoints do not require authorization.

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author



## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `3.0.4.18`
    - Generator version: `7.19.0-SNAPSHOT`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
