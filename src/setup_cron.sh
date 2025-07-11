#!/bin/bash

croncmd="php $(pwd)/cron.php"
cronjob="*/5 * * * * $croncmd"

( crontab -l 2>/dev/null | grep -v -F "$croncmd" ; echo "$cronjob" ) | crontab -
echo "Cron job added to run every 5 minutes."
