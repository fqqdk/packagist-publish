This is a handy composer command that can be used to easily publish your composer package to packagist.

Usage:

in your composer.json:

```json
{
    "require-dev": {
        "fqqdk/packagist-publisher": "*"
    }
}
```

Deploying your package to packagist:

```bash
composer publish [-b|--api_base_url API_BASE_URL] [--] <username> <api_token>
```

The api_base_url option has the default value of https://packagist.org/ (of the main packagist repository) for convenience,
but setting this allows you to use your own packagist style registry (provided it has the same API as packagist)

username is your packagist username, and api_token is your packagist API token. When using this command in a continous build
environment, consider passing these parameters in a secure way, e.g. using environment variables.
