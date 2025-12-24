# DcsServerBot\StatisticsApi



All URIs are relative to http://localhost, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**highscoreServerapiHighscoreGet()**](StatisticsApi.md#highscoreServerapiHighscoreGet) | **GET** /serverapi/highscore | Highscore |
| [**leaderboardServerapiLeaderboardGet()**](StatisticsApi.md#leaderboardServerapiLeaderboardGet) | **GET** /serverapi/leaderboard | Leaderboard |
| [**playerInfoServerapiPlayerInfoPost()**](StatisticsApi.md#playerInfoServerapiPlayerInfoPost) | **POST** /serverapi/player_info | Player Information |
| [**statsServerapiModulestatsPost()**](StatisticsApi.md#statsServerapiModulestatsPost) | **POST** /serverapi/modulestats | Module Statistics |
| [**statsServerapiStatsPost()**](StatisticsApi.md#statsServerapiStatsPost) | **POST** /serverapi/stats | Player Statistics |
| [**topkdrServerapiTopkdrGet()**](StatisticsApi.md#topkdrServerapiTopkdrGet) | **GET** /serverapi/topkdr | Top KDR |
| [**topkillsServerapiTopkillsGet()**](StatisticsApi.md#topkillsServerapiTopkillsGet) | **GET** /serverapi/topkills | Top Kills |
| [**trapsServerapiTrapsPost()**](StatisticsApi.md#trapsServerapiTrapsPost) | **POST** /serverapi/traps | Carrier Traps |
| [**trueskillServerapiTrueskillGet()**](StatisticsApi.md#trueskillServerapiTrueskillGet) | **GET** /serverapi/trueskill | TrueSkill:tm: |
| [**weaponpkServerapiWeaponpkPost()**](StatisticsApi.md#weaponpkServerapiWeaponpkPost) | **POST** /serverapi/weaponpk | Weapon PK |


## `highscoreServerapiHighscoreGet()`

```php
highscoreServerapiHighscoreGet($server_name, $period, $limit): \DcsServerBot\Model\Highscore
```

Highscore

Get highscore statistics for players

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$server_name = 'server_name_example'; // string
$period = 'all'; // string
$limit = 10; // int

try {
    $result = $apiInstance->highscoreServerapiHighscoreGet($server_name, $period, $limit);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->highscoreServerapiHighscoreGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **server_name** | **string**|  | [optional] |
| **period** | **string**|  | [optional] [default to &#39;all&#39;] |
| **limit** | **int**|  | [optional] [default to 10] |

### Return type

[**\DcsServerBot\Model\Highscore**](../Model/Highscore.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `leaderboardServerapiLeaderboardGet()`

```php
leaderboardServerapiLeaderboardGet($what, $order, $query, $limit, $offset, $server_name): \DcsServerBot\Model\LeaderBoard
```

Leaderboard

Get leaderbord information

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$what = 'what_example'; // string
$order = 'desc'; // string
$query = 'query_example'; // string
$limit = 56; // int
$offset = 56; // int
$server_name = 'server_name_example'; // string

try {
    $result = $apiInstance->leaderboardServerapiLeaderboardGet($what, $order, $query, $limit, $offset, $server_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->leaderboardServerapiLeaderboardGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **what** | **string**|  | |
| **order** | **string**|  | [optional] [default to &#39;desc&#39;] |
| **query** | **string**|  | [optional] |
| **limit** | **int**|  | [optional] |
| **offset** | **int**|  | [optional] |
| **server_name** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\LeaderBoard**](../Model/LeaderBoard.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `playerInfoServerapiPlayerInfoPost()`

```php
playerInfoServerapiPlayerInfoPost($nick, $date, $server_name): \DcsServerBot\Model\PlayerInfo
```

Player Information

Get player information

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$nick = 'nick_example'; // string
$date = 'date_example'; // string
$server_name = 'server_name_example'; // string

try {
    $result = $apiInstance->playerInfoServerapiPlayerInfoPost($nick, $date, $server_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->playerInfoServerapiPlayerInfoPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **nick** | **string**|  | |
| **date** | **string**|  | [optional] |
| **server_name** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\PlayerInfo**](../Model/PlayerInfo.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `statsServerapiModulestatsPost()`

```php
statsServerapiModulestatsPost($nick, $date, $server_name, $last_session): \DcsServerBot\Model\ModuleStats
```

Module Statistics

Get module statistics

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$nick = 'nick_example'; // string
$date = 'date_example'; // string
$server_name = 'server_name_example'; // string
$last_session = True; // bool

try {
    $result = $apiInstance->statsServerapiModulestatsPost($nick, $date, $server_name, $last_session);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->statsServerapiModulestatsPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **nick** | **string**|  | |
| **date** | **string**|  | [optional] |
| **server_name** | **string**|  | [optional] |
| **last_session** | **bool**|  | [optional] |

### Return type

[**\DcsServerBot\Model\ModuleStats**](../Model/ModuleStats.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `statsServerapiStatsPost()`

```php
statsServerapiStatsPost($nick, $date, $server_name, $last_session): \DcsServerBot\Model\PlayerStats
```

Player Statistics

Get player statistics

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$nick = 'nick_example'; // string
$date = 'date_example'; // string
$server_name = 'server_name_example'; // string
$last_session = True; // bool

try {
    $result = $apiInstance->statsServerapiStatsPost($nick, $date, $server_name, $last_session);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->statsServerapiStatsPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **nick** | **string**|  | |
| **date** | **string**|  | [optional] |
| **server_name** | **string**|  | [optional] |
| **last_session** | **bool**|  | [optional] |

### Return type

[**\DcsServerBot\Model\PlayerStats**](../Model/PlayerStats.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `topkdrServerapiTopkdrGet()`

```php
topkdrServerapiTopkdrGet($limit, $offset, $server_name): \DcsServerBot\Model\TopKill[]
```

Top KDR

Get top KDR statistics for players

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$limit = 10; // int
$offset = 0; // int
$server_name = 'server_name_example'; // string

try {
    $result = $apiInstance->topkdrServerapiTopkdrGet($limit, $offset, $server_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->topkdrServerapiTopkdrGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **limit** | **int**|  | [optional] [default to 10] |
| **offset** | **int**|  | [optional] [default to 0] |
| **server_name** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\TopKill[]**](../Model/TopKill.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `topkillsServerapiTopkillsGet()`

```php
topkillsServerapiTopkillsGet($limit, $offset, $server_name): \DcsServerBot\Model\TopKill[]
```

Top Kills

Get top kills statistics for players

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$limit = 10; // int
$offset = 0; // int
$server_name = 'server_name_example'; // string

try {
    $result = $apiInstance->topkillsServerapiTopkillsGet($limit, $offset, $server_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->topkillsServerapiTopkillsGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **limit** | **int**|  | [optional] [default to 10] |
| **offset** | **int**|  | [optional] [default to 0] |
| **server_name** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\TopKill[]**](../Model/TopKill.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `trapsServerapiTrapsPost()`

```php
trapsServerapiTrapsPost($nick, $date, $limit, $offset, $server_name): \DcsServerBot\Model\TrapEntry[]
```

Carrier Traps

Get traps for players

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$nick = 'nick_example'; // string
$date = 'date_example'; // string
$limit = 56; // int
$offset = 56; // int
$server_name = 'server_name_example'; // string

try {
    $result = $apiInstance->trapsServerapiTrapsPost($nick, $date, $limit, $offset, $server_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->trapsServerapiTrapsPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **nick** | **string**|  | [optional] |
| **date** | **string**|  | [optional] |
| **limit** | **int**|  | [optional] |
| **offset** | **int**|  | [optional] |
| **server_name** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\TrapEntry[]**](../Model/TrapEntry.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `trueskillServerapiTrueskillGet()`

```php
trueskillServerapiTrueskillGet($limit, $offset, $server_name): \DcsServerBot\Model\Trueskill[]
```

TrueSkill:tm:

Get TrueSkill:tm: statistics for players

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$limit = 10; // int
$offset = 0; // int
$server_name = 'server_name_example'; // string

try {
    $result = $apiInstance->trueskillServerapiTrueskillGet($limit, $offset, $server_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->trueskillServerapiTrueskillGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **limit** | **int**|  | [optional] [default to 10] |
| **offset** | **int**|  | [optional] [default to 0] |
| **server_name** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\Trueskill[]**](../Model/Trueskill.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `weaponpkServerapiWeaponpkPost()`

```php
weaponpkServerapiWeaponpkPost($nick, $date, $server_name): \DcsServerBot\Model\WeaponPK[]
```

Weapon PK

Get PK statistics for all weapons of a specific players

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\StatisticsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$nick = 'nick_example'; // string
$date = 'date_example'; // string
$server_name = 'server_name_example'; // string

try {
    $result = $apiInstance->weaponpkServerapiWeaponpkPost($nick, $date, $server_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling StatisticsApi->weaponpkServerapiWeaponpkPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **nick** | **string**|  | |
| **date** | **string**|  | [optional] |
| **server_name** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\WeaponPK[]**](../Model/WeaponPK.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
