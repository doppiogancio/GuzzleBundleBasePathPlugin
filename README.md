# Guzzle Bundle Base Path Plugin
A Plugin for Guzzle Bundle, that will help you to set a base path to each request of your client.

----

## Prerequisites
- PHP 7.1 or above

## Installation

To install this bundle, run the command below on the command line and you will get the latest stable version from [Packagist][4].

``` bash
composer require doppiogancio/guzzle-bundle-base-path
```

## Usage

### Enable bundle

Find next lines in `src/Kernel.php`:

```php
foreach ($contents as $class => $envs) {
    if (isset($envs['all']) || isset($envs[$this->environment])) {
        yield new $class();
    }
}
```

and replace them by:

```php
foreach ($contents as $class => $envs) {
    if (isset($envs['all']) || isset($envs[$this->environment])) {
        if ($class === \EightPoints\Bundle\GuzzleBundle\EightPointsGuzzleBundle::class) {
            yield new $class([
                new Doppiogancio\Bundle\GuzzleBundleBasePathPlugin\GuzzleBundleBasePathPlugin(),
            ]);
        } else {
            yield new $class();
        }
    }
}
```

### Basic configuration

``` yaml
# app/config/config.yml

eight_points_guzzle:
    clients:
        api_payment:
            base_url: "http://api.domain.tld"
            
            options:
                auth: oauth2

            # plugin settings
            plugin:
                base_path:
                    base_path:       "/api/v3"
```

### Options

| Key       | Description                            | Required | Example |
|-----------|----------------------------------------|----------|---------|
| base_path | base path to add to every request path | yes      | /api/v3 |

## License

This middleware is licensed under the MIT License - see the LICENSE file for details
