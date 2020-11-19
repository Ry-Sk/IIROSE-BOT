FROM 54ik1/php-v8js
ENV TZ Asia/Shanghai
RUN apt update && \
    apt install libssl-dev libzip-dev -y && \
    docker-php-ext-install sockets && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install zip && \
    git clone https://github.com/swoole/swoole-src.git && \
    cd swoole-src && \
    git checkout v4.5.7 && \
    phpize && \
    ./configure --enable-openssl --enable-sockets --enable-http2 --enable-mysqlnd && \
    make && make install && \
    cd .. && \
    rm -rf swoole-src && \
    docker-php-ext-enable swoole.so && \
    apt-get autoremove --purge -y  && \
    apt-get clean -y
ENTRYPOINT [ "/bin/bash" ]