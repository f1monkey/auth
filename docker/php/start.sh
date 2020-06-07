#!/bin/bash
(composer install && (test -f /srv/bin/rr || /srv/vendor/bin/rr get-binary --location /srv/bin) && /srv/bin/rr -v -d serve) || (echo $1 && tail -f /dev/null)
