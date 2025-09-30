Realforce PHP SDK
=============

Super-simple, minimum abstraction Realforce API v1.x wrapper, in PHP.

I hate complex wrappers. This lets you get from the Realforce API docs to the code as directly as possible.

[![Build](https://github.com/antistatique/realforce-php-sdk/actions/workflows/tests.yml/badge.svg)](https://github.com/antistatique/realforce-php-sdk/actions/workflows/tests.yml)
[![Coverage Status](https://coveralls.io/repos/github/antistatique/realforce-php-sdk/badge.svg)](https://coveralls.io/github/antistatique/realforce-php-sdk)
[![Packagist](https://img.shields.io/packagist/dt/antistatique/realforce-php-sdk.svg?maxAge=2592000)](https://packagist.org/packages/antistatique/realforce-php-sdk)
[![License](https://poser.pugx.org/antistatique/realforce-php-sdk/license)](https://packagist.org/packages/antistatique/realforce-php-sdk)
[![PHP Versions Supported](https://img.shields.io/badge/php-%3E%3D%208.3-8892BF.svg)](https://packagist.org/packages/antistatique/realforce-php-sdk)

https://github.com/realforce/documentation
https://www.realforce.com/developers

Getting started
------------

You can install `realforce-php-sdk` using Composer:

```
composer require antistatique/realforce-php-sdk
```

Examples
--------

See the `examples/` directory for examples of the key client features. You can view them in your browser by running the php built-in web server.

```bash
php -S localhost:8000 -t examples/
```

And then browsing to the host and port you specified (in the above example, `http://localhost:8000`).

### Basic Example

Start by `use`-ing the class and creating an instance with your API key

```php
use \Antistatique\Realforce\RealforceClient;
```

Every request should contain a valid API token. Use the `RealforceClient::setApiToken` method prior any requests.
All private operational requests require an authentication token.

### Listing of Properties

Fetch a list of published properties' public data.

👉 https://github.com/realforce/documentation/blob/master/api-public/endpoints/properties-list.md

```php
# Setup the Realforce client.
$realforce = new RealforceClient();
$realforce->setApiToken($token);

# Prepare the request.
$query = new Antistatique\Realforce\Request\PropertiesListRequest();
$query
  ->lang(['fr', 'en'])
  ->page(0)
  ->perPage(10)
;

# Fetch the list of properties.
$response = $rf->publicProperties()->list($query);
print_r($response);
```

### Labels - Amenities

Fetch "amenities" labels linked to the public data you retrieve from the public API endpoints.

👉 https://github.com/realforce/documentation/blob/master/api-public/endpoints/labels-amenities.md

```php
# Setup the Realforce client.
$realforce = new RealforceClient();
$realforce->setApiToken($token);

# Prepare the request.
$query = new Antistatique\Realforce\Request\I18nRequest();
$query
  ->lang(['fr', 'en'])
;

# Fetch the list of amenities' labels.
$response = $rf->publicLabels()->amenities($query);
print_r($response);
```

### Labels - Categories

Fetch "categories" labels linked to the public data you retrieve from the public API endpoints.

👉 https://github.com/realforce/documentation/blob/master/api-public/endpoints/labels-categories.md

```php
# Setup the Realforce client.
$realforce = new RealforceClient();
$realforce->setApiToken($token);

# Prepare the request.
$query = new Antistatique\Realforce\Request\I18nRequest();
$query
  ->lang(['fr', 'en'])
;

# Fetch the list od categories' labels.
$response = $rf->publicLabels()->categories($query);
print_r($response);
```

### Labels - Categories

Fetch "locations" labels linked to the public data you retrieve from the public API endpoints.

👉 https://github.com/realforce/documentation/blob/master/api-public/endpoints/labels-locations.md

```php
# Setup the Realforce client.
$realforce = new RealforceClient();
$realforce->setApiToken($token);

# Prepare the request.
$query = new Antistatique\Realforce\Request\LocationsRequest();
$query
  ->isQuarter(true)
  ->lang(['fr', 'en'])
;

# Fetch the list of locations' labels.
$response = $rf->publicLabels()->locations($query);
print_r($response);
```

Troubleshooting
---------------

To get the last error returned by either the HTTP client or by the API, use `getLastError()`:

```php
echo $rf->getLastError();
```

For further debugging, you can inspect the headers and body of the response:

```php
print_r($rf->getLastResponse());
print_r($rf->getLastResponsetHttpStatus());

```

If you suspect you're sending data in the wrong format, you can look at what was sent to Realforce by the wrapper:

```php
print_r($rf->getLastRequest());
```

If your server's CA root certificates are not up to date you may find that SSL verification fails and you don't get a response. The correction solution for this [is not to disable SSL verification](http://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/). The solution is to update your certificates. If you can't do that, there's an option at the top of the class file. Please don't just switch it off without at least attempting to update your certs -- that's lazy and dangerous. You're not a lazy, dangerous developer are you?
