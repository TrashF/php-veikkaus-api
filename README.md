# php-veikkaus-api
PHP client to Veikkaus REST api

This is a work in progress, just thought someone might be interested.

Fetching latest Lotto results would be:

`Veikkaus::getInstance()->getLottoRound();`

See `scripts` directory for more usage hints. Current features:
- Querying for Lotto rounds
- Basic support for sport games described in Veikkaus API examples at https://github.com/VeikkausOy/sport-games-robot
- Logging in and querying for user balance