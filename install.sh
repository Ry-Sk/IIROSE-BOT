#!/usr/bin/env bash
echo 正在检查更新
git pull -f
echo 正在安装
composer install
docker pull hserr/wkhtmltoimage
docker pull jgoldfar/maxima-docker:debian-latest
echo 正在创建数据文件
mkdir ./storge
echo 正在创建数据库
awk 'BEGIN { cmd="cp -i ./database.db ./storge/."; print "n" |cmd; }'
read -p 请输入访问地址 URL
echo "URL = $URL" > env.ini