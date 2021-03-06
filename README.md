# auth
![](https://github.com/f1monkey/auth/workflows/Tests/badge.svg)![](https://img.shields.io/github/v/tag/f1monkey/auth)

Microservice for JWT authentication based on RoadRunner and Symfony.

## Docs

Auto-generated API documentation located at /v1/doc route.

## Development

* Copy `docker-compose.override.yml.dist` to `docker-compose.override.yml`
```
cp docker-compose.override.yml.dist docker-compose.override.yml
```
* Run docker containers
```
$ docker-compose up -d
```
* Connect to php container
```
$ docker-compose exec php bash
```
* Generate RSA key pair to sign JWT tokens
```
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
* Add private key passphrase to .env.local
```
$ printf "\nJWT_PASSPHRASE=passphrase" >> .env.local
```

## Testing
Run static analyse
```
$ vendor/bin/phpstan analyze --level=max src
```

Run tests:
```
$ vendor/bin/codecept run
```
