FROM ubuntu:20.04
ENV TZ Asia/Shanghai
RUN sed -i "s/\/\/.*archive.ubuntu.com/\/\/mirrors.tuna.tsinghua.edu.cn/g;s/\/\/.*security.ubuntu.com/\/\/mirrors.tuna.tsinghua.edu.cn/g" /etc/apt/sources.list && \
    apt update && \
    DEBIAN_FRONTEND=noninteractive apt install wkhtmltopdf ttf-wqy-zenhei ttf-wqy-microhei -y -f && \
    echo '$TZ' > /etc/timezone && \
    pecl install swoole && \
    DEBIAN_FRONTEND=noninteractive apt remove php-dev git -y && \
    apt autoremove