# Examples for Realforce API SDK for PHP

Note that everything is done from the root of the project and not from the examples folder.

## How to run the examples

1. Install the dependencies with Composer:

```bash
$ composer install
```

2. Copy the `.env.example` file in the examples folder as `.env`

```bash
$ cp examples/.env.example examples/.env
```

3. Fill the `.env` file with correct information

    - `REALFORCE_API_TOKEN` is your private API Token for Realforce.

4. Run the PHP built-in web server. Supply the `-t` option to this directory:

```bash
$ php -S localhost:8000 -t examples/
```

5. Point your browser to the host and port you specified.

## How does the Realforce API works

Every request should contain a valid API token. Use the `RealforceClient::setApiToken` method prior any requests.
All private operational requests require an authentication token.
