FROM php:7.4-cli-buster
ENV TZ Asia/Shanghai
RUN apt-get update && apt-get install -y build-essential curl git python libglib2.0-dev patchelf libssl-dev libzip-dev libltdl-dev libnss3 mediainfo && \
    docker-php-ext-install sockets && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install zip && \
    cd /tmp && \
    git clone https://chromium.googlesource.com/chromium/tools/depot_tools.git && \
    export PATH=`pwd`/depot_tools:"$PATH" && \
    fetch v8 && \
    cd v8 && \
    git checkout 8.0.426.30 && \
    gclient sync && \
    tools/dev/v8gen.py -vv x64.release -- is_component_build=true use_custom_libcxx=false && \
    ninja -C out.gn/x64.release/ && \
    mkdir -p /usr/local/v8/lib && \
    mkdir -p /usr/local/v8/include && \
    cp out.gn/x64.release/lib*.so out.gn/x64.release/*_blob.bin \
        out.gn/x64.release/icudtl.dat /usr/local/v8/lib/ && \
    cp -R include/* /usr/local/v8/include/ && \
    for A in /usr/local/v8/lib/*.so; do patchelf --set-rpath '$ORIGIN' $A; done && \
    cd /tmp && \
    git clone https://github.com/phpv8/v8js.git && \
    cd v8js && \
    phpize && \
    ./configure --with-v8js=/usr/local/v8 LDFLAGS="-lstdc++" CPPFLAGS="-DV8_COMPRESS_POINTERS" && \
    make && \
    make install &&\
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    git clone https://github.com/swoole/swoole-src.git && \
    cd swoole-src && \
    git checkout v4.5.10 && \
    phpize && \
    ./configure --enable-openssl --enable-sockets --enable-http2 --enable-mysqlnd && \
    make && make install && \
    docker-php-ext-enable v8js.so && \
    docker-php-ext-enable swoole.so && \
    apt purge build-essential git python  -y && \
    apt-get autoremove --purge -y  && \
    apt-get clean -y && \
    rm -rf /tmp/*
ENTRYPOINT [ "/bin/bash" ]
ENV PS1="\h:\w\$ "
