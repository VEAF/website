# DcsServerBot\CreditsApi



All URIs are relative to http://localhost, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**creditsServerapiCreditsPost()**](CreditsApi.md#creditsServerapiCreditsPost) | **POST** /serverapi/credits | Campaign Credits |
| [**squadronCreditsServerapiSquadronCreditsPost()**](CreditsApi.md#squadronCreditsServerapiSquadronCreditsPost) | **POST** /serverapi/squadron_credits | Squadron Credits |


## `creditsServerapiCreditsPost()`

```php
creditsServerapiCreditsPost($nick, $date, $campaign): \DcsServerBot\Model\CampaignCredits
```

Campaign Credits

Get campaign credits for players

### Example

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

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **nick** | **string**|  | |
| **date** | **string**|  | [optional] |
| **campaign** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\CampaignCredits**](../Model/CampaignCredits.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `squadronCreditsServerapiSquadronCreditsPost()`

```php
squadronCreditsServerapiSquadronCreditsPost($name, $campaign): \DcsServerBot\Model\SquadronCampaignCredit
```

Squadron Credits

Squadron campaign credits

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new DcsServerBot\Api\CreditsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$name = 'name_example'; // string
$campaign = 'campaign_example'; // string

try {
    $result = $apiInstance->squadronCreditsServerapiSquadronCreditsPost($name, $campaign);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CreditsApi->squadronCreditsServerapiSquadronCreditsPost: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **name** | **string**|  | |
| **campaign** | **string**|  | [optional] |

### Return type

[**\DcsServerBot\Model\SquadronCampaignCredit**](../Model/SquadronCampaignCredit.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/x-www-form-urlencoded`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
