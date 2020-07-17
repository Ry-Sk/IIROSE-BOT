#!/usr/bin/env bash
host=${1:-0.0.0.0}
port=${2:-8008}
echo listened $host:$port
docker run -it --name="iirose-bot-dev" -v $(pwd):/home/container -p $host:$port:8008 -u container -w /home/container --rm=true hserr/iirose-bot
