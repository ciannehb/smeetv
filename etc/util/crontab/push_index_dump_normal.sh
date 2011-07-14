#!/bin/bash

curl http://smeetv.com/etc/util/index.onequery.php?priority=0 > /dev/null 
curl http://smeetv.com/etc/util/index.onequery.php?priority=1 > /dev/null 
curl http://smeetv.com/etc/util/index.onequery.php?priority=2 > /dev/null
curl http://smeetv.com/etc/util/index.onequery.php?priority=3 > /dev/null


sleep 10; curl http://smeetv.com/etc/util/index.onequery.php?priority=0 > /dev/null 
sleep 10; curl http://smeetv.com/etc/util/index.onequery.php?priority=1 > /dev/null 
sleep 10; curl http://smeetv.com/etc/util/index.onequery.php?priority=2 > /dev/null
sleep 10; curl http://smeetv.com/etc/util/index.onequery.php?priority=3 > /dev/null


sleep 20; curl http://smeetv.com/etc/util/index.onequery.php?priority=0 > /dev/null 
sleep 23; curl http://smeetv.com/etc/util/index.onequery.php?priority=1 > /dev/null 
sleep 25; curl http://smeetv.com/etc/util/index.onequery.php?priority=2 > /dev/null
sleep 26; curl http://smeetv.com/etc/util/index.onequery.php?priority=3 > /dev/null


sleep 35; curl http://smeetv.com/etc/util/index.onequery.php?priority=0 > /dev/null 
sleep 40; curl http://smeetv.com/etc/util/index.onequery.php?priority=1 > /dev/null 
sleep 48; curl http://smeetv.com/etc/util/index.onequery.php?priority=2 > /dev/null
sleep 55; curl http://smeetv.com/etc/util/index.onequery.php?priority=3 > /dev/null


