#!/usr/bin/env bash
host=${1:-0.0.0.0}
port=${2:-8008}
echo listened $host:$port
docker pull surnet/alpine-wkhtmltopdf:3.10-0.12.6-full
docker run -it \
  --name="iirose-bot-dev" \
  --privileged \
  -v /var/run/docker.sock:/var/run/docker.sock \
  -v $(which docker):/bin/docker \
  -v $(pwd):/iirosebot \
  -p $host:$port:8008 \
  -w /iirosebot \
  --rm=true hserr/iirose-bot
