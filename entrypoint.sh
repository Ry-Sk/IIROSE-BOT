#!/usr/bin/env bash
docker run -it --name="iirose-bot-dev" -v /home/logos/iirose-bot:/home/container -p 0.0.0.0:8008:8008 -u container -w /home/container --rm=true hserr/iirose-bot
