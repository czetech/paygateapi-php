#!/bin/sh
set -e

case $1 in

  test)
    exec ./vendor/bin/phpunit tests
  ;;

  docs)
    exec ./vendor/bin/phpdoc
  ;;

  *)
    exec "$@"
  ;;

esac

exit 0
