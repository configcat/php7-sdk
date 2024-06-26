# ConfigCat SDK for PHP 7
https://configcat.com

ConfigCat SDK for PHP 7 provides easy integration for your application to ConfigCat.

ConfigCat is a feature flag and configuration management service that lets you separate releases from deployments. You can turn your features ON/OFF using <a href="https://app.configcat.com" target="_blank">ConfigCat Dashboard</a> even after they are deployed. ConfigCat lets you target specific groups of users based on region, email or any other custom user attribute.

ConfigCat is a <a href="https://configcat.com" target="_blank">hosted feature flag service</a>. Manage feature toggles across frontend, backend, mobile, desktop apps. <a href="https://configcat.com" target="_blank">Alternative to LaunchDarkly</a>. Management app + feature flag SDKs.

[![Build Status](https://github.com/configcat/php7-sdk/actions/workflows/php-ci.yml/badge.svg?branch=main)](https://github.com/configcat/php7-sdk/actions/workflows/php-ci.yml)
[![Latest Stable Version](https://poser.pugx.org/configcat/configcat-client-php7/version)](https://packagist.org/packages/configcat/configcat-client-php7)
[![Total Downloads](https://poser.pugx.org/configcat/configcat-client-php7/downloads)](https://packagist.org/packages/configcat/configcat-client-php7)
[![Sonar Quality Gate](https://img.shields.io/sonar/quality_gate/configcat_php7-sdk?logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io)](https://sonarcloud.io/project/overview?id=configcat_php7-sdk)
[![Sonar Coverage](https://img.shields.io/sonar/coverage/configcat_php7-sdk?logo=SonarCloud&server=https%3A%2F%2Fsonarcloud.io)](https://sonarcloud.io/project/overview?id=configcat_php7-sdk)

## Requirements
- PHP >= 7.1

ConfigCat SDK for PHP >= 8.1 is available [here](https://github.com/configcat/php-sdk).

## Getting started

### 1. Install the package with [Composer](https://getcomposer.org/)
```shell
composer require configcat/configcat-client-php7
```

### 2. Go to the <a href="https://app.configcat.com/sdkkey" target="_blank">ConfigCat Dashboard</a> to get your *SDK Key*:
![SDK-KEY](https://raw.githubusercontent.com/ConfigCat/php7-sdk/main/media/readme02-3.png  "SDK-KEY")

### 3. Create the *ConfigCat* client instance
```php
$client = new \ConfigCat\ConfigCatClient("#YOUR-SDK-KEY#");
```

### 4. Get your setting value:
```php
$isMyAwesomeFeatureEnabled = $client->getValue("isMyAwesomeFeatureEnabled", false);
if(is_bool($isMyAwesomeFeatureEnabled) && $isMyAwesomeFeatureEnabled) {
    doTheNewThing();
} else {
    doTheOldThing();
}
```

## Getting user specific setting values with Targeting
Using this feature, you will be able to get different setting values for different users in your application by passing a `User Object` to the `getValue()` function.

Read more about [Targeting here](https://configcat.com/docs/advanced/targeting/).


## User object
Percentage and targeted rollouts are calculated by the user object you can optionally pass to the configuration requests.
The user object must be created with a **mandatory** identifier parameter which should uniquely identify each user:
```php
$user = new \ConfigCat\User("#USER-IDENTIFIER#"); // mandatory

$isMyAwesomeFeatureEnabled = $client->getValue("isMyAwesomeFeatureEnabled", false, $user);
if(is_bool($isMyAwesomeFeatureEnabled) && $isMyAwesomeFeatureEnabled) {
    doTheNewThing();
} else {
    doTheOldThing();
}
```

## Sample/Demo app
* [Sample Laravel app](https://github.com/ConfigCat/php7-sdk/tree/main/samples/laravel)

## Need help?
https://configcat.com/support

## Contributing
Contributions are welcome. For more info please read the [Contribution Guideline](CONTRIBUTING.md).

## About ConfigCat
- [Official ConfigCat SDKs for other platforms](https://github.com/configcat)
- [Documentation](https://configcat.com/docs)
- [Blog](https://configcat.com/blog)
