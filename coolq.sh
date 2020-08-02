#!/usr/bin/env bash
docker pull richardchien/cqhttp:latest
mkdir coolq
docker run -ti --rm --name cqhttp-test \
           -v $(pwd)/coolq:/home/user/coolq \
           -p 9000:9000 \
           -p 5700:5700 \
           -e COOLQ_ACCOUNT=2835965744 \
           richardchien/cqhttp:latest