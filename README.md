# php-veikkaus-api
PHP client to Veikkaus REST api

This is a work in progress, just thought someone might be interested.

Fetching latest Lotto results would be:

`Veikkaus::getInstance()->getLottoRound();`

See `scripts` directory for more usage hints. Currently Lotto and Multiscore (Moniveto) are somewhat implemented, basic support for other games is rather trivial to add by changing game name according to list given in https://github.com/VeikkausOy/sport-games-robot.
