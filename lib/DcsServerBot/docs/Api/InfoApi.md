# DcsServerBot\InfoApi



All URIs are relative to http://localhost, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**currentServerServerapiCurrentServerGet()**](InfoApi.md#currentServerServerapiCurrentServerGet) | **GET** /serverapi/current_server | Current Server |
| [**getuserServerapiGetuserPost()**](InfoApi.md#getuserServerapiGetuserPost) | **POST** /serverapi/getuser | User list |
| [**linkmeServerapiLinkmePost()**](InfoApi.md#linkmeServerapiLinkmePost) | **POST** /serverapi/linkme | Link Discord to DCS |
| [**playerSquadronsServerapiPlayerSquadronsPost()**](InfoApi.md#playerSquadronsServerapiPlayerSquadronsPost) | **POST** /serverapi/player_squadrons | Player Squadrons |
| [**serversServerapiServersGet()**](InfoApi.md#serversServerapiServersGet) | **GET** /serverapi/servers | Server list |
| [**serverstatsServerapiServerstatsGet()**](InfoApi.md#serverstatsServerapiServerstatsGet) | **GET** /serverapi/serverstats | Server Statistics |
| [**squadronMembersServerapiSquadronMembersPost()**](InfoApi.md#squadronMembersServerapiSquadronMembersPost) | **POST** /serverapi/squadron_members | Squadron Members |
| [**squadronsServerapiSquadronsGet()**](InfoApi.md#squadronsServerapiSquadronsGet) | **GET** /serverapi/squadrons | Squadron list |


## `currentServerServerapiCurrentServerGet()`

```php
currentServerServerapiCurrentServerGet($nick, $date): string
```

Current Server

Server name a player is flying on

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\InfoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$nick = 'nick_example'; // string
$date = 'date_example'; // string

try {
    $result = $apiInstance->currentServerServerapiCurrentServerGet($nick, $date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InfoApi->currentServerServerapiCurrentServerGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **nick** | **string**|  | |
| **date** | **string**|  | [optional] |

### Return type

**string**

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getuserServerapiGetuserPost()`

```php
getuserServerapiGetuserPost($nick): \DcsServerBot\Model\UserEntry[]
```

User list

Get users by name

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\InfoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$nick = 'nick_example'; // string

try {
    $result = $apiInstance->getuserServerapiGetuserPost($nick);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InfoApi->getuserServerapiGetuserPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **nick** | **string**|  | |

### Return type

[**\DcsServerBot\Model\UserEntry[]**](../Model/UserEntry.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `linkmeServerapiLinkmePost()`

```php
linkmeServerapiLinkmePost($discord_id, $force): \DcsServerBot\Model\LinkMeResponse
```

Link Discord to DCS

Link your Discord account to your DCS account

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\InfoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$discord_id = 'discord_id_example'; // string | Discord user ID (snowflake)
$force = false; // bool | Force the operation

try {
    $result = $apiInstance->linkmeServerapiLinkmePost($discord_id, $force);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InfoApi->linkmeServerapiLinkmePost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **discord_id** | **string**| Discord user ID (snowflake) | |
| **force** | **bool**| Force the operation | [optional] [default to false] |

### Return type

[**\DcsServerBot\Model\LinkMeResponse**](../Model/LinkMeResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `playerSquadronsServerapiPlayerSquadronsPost()`

```php
playerSquadronsServerapiPlayerSquadronsPost($nick, $date): \DcsServerBot\Model\PlayerSquadron[]
```

Player Squadrons

List of player squadrons

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\InfoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$nick = 'nick_example'; // string
$date = 'date_example'; // string

try {
    $result = $apiInstance->playerSquadronsServerapiPlayerSquadronsPost($nick, $date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InfoApi->playerSquadronsServerapiPlayerSquadronsPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **nick** | **string**|  | |
| **date** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\PlayerSquadron[]**](../Model/PlayerSquadron.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `serversServerapiServersGet()`

```php
serversServerapiServersGet($server_name): \DcsServerBot\Model\ServerInfo[]
```

Server list

List all servers, the active mission (if any) and the active extensions

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\InfoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$server_name = 'server_name_example'; // string

try {
    $result = $apiInstance->serversServerapiServersGet($server_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InfoApi->serversServerapiServersGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **server_name** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\ServerInfo[]**](../Model/ServerInfo.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `serverstatsServerapiServerstatsGet()`

```php
serverstatsServerapiServerstatsGet($server_name): \DcsServerBot\Model\ServerStats
```

Server Statistics

List the statistics of a whole group

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\InfoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$server_name = 'server_name_example'; // string

try {
    $result = $apiInstance->serverstatsServerapiServerstatsGet($server_name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InfoApi->serverstatsServerapiServerstatsGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **server_name** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\ServerStats**](../Model/ServerStats.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `squadronMembersServerapiSquadronMembersPost()`

```php
squadronMembersServerapiSquadronMembersPost($name): \DcsServerBot\Model\UserEntry[]
```

Squadron Members

List squadron members

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\InfoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$name = 'name_example'; // string

try {
    $result = $apiInstance->squadronMembersServerapiSquadronMembersPost($name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InfoApi->squadronMembersServerapiSquadronMembersPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **name** | **string**|  | |

### Return type

[**\DcsServerBot\Model\UserEntry[]**](../Model/UserEntry.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `squadronsServerapiSquadronsGet()`

```php
squadronsServerapiSquadronsGet($limit, $offset): \DcsServerBot\Model\SquadronInfo[]
```

Squadron list

List all squadrons and their roles

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\InfoApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$limit = 56; // int
$offset = 0; // int

try {
    $result = $apiInstance->squadronsServerapiSquadronsGet($limit, $offset);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling InfoApi->squadronsServerapiSquadronsGet: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **limit** | **int**|  | [optional] |
| **offset** | **int**|  | [optional] [default to 0] |

### Return type

[**\DcsServerBot\Model\SquadronInfo[]**](../Model/SquadronInfo.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
