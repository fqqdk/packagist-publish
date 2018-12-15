This is a handy composer script that can be used to easily deploy the root composer package to packagist.

Usage:

in your composer.json:

```json
{
    "require-dev": {
        "fqqdk/packagist-self-deploy": "*"
    },
    "scripts": {
        "deploy": "Packagist\\SelfDeployCommand::main"
    }
}
```

Deploying your package to packagist

```bash
composer deploy [username [apiToken [apiUrl]]]
```

you can also set the above parameters from environment variables 
    PACKAGIST_USERNAME
    PACKAGIST_API_TOKEN
    PACKAGIST_API_URL
respectively.

The apiUrl has the default value of https://packagist.org/ (of the main packagist repository) for convenience,
but setting this allows you to use your own composer repository (provided it has the same API as packagist)
