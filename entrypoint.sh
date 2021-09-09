#!/usr/bin/env bash
host=${1:-0.0.0.0}
port=${2:-8008}
echo listened $host:$port
docker run -it \
  --name="iirose-bot-dev" \
  -v /var/run/docker.sock:/var/run/docker.sock \
  --net=host \
  -v $(which docker):/bin/docker \
  -v $(pwd):$(pwd) \
  -v $(pwd)/storge/public:$(pwd)/public/storge \
  -p $host:$port:8008 \
  -w $(pwd) \
  -u $UID \
  --rm=true hserr/iirose-bot:dev
  