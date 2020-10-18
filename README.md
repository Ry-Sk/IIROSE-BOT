<p align="center">
<img src="https://i.loli.net/2020/05/11/bRMo78CNJP4HIiX.png" alt="iirose" width="100">
</p>
<h1 align="center">IIROSE-BOT</h1>

> 这是一个为[IIROSE](https://iirose.com/)设计的机器人框架

## 本地运行（开发环境）

```bash
# 安装docker
curl -sSL https://get.daocloud.io/docker | sh

# Git请自行用包管理安装
# Ubuntu / Debian 系
sudo apt install git
# Redhat / Centos 系
sudo yum install git
# Arch 系
sudo pacman -Sy git
# Alpine 
sudo apk add git

# 克隆本项目
git clone https://github.com/iirose-tools/iirose-bot.git

# 进入项目目录
cd iirose-bot

# 运行 docker
./entrypoint.sh # 可加参数，./entrypoint.sh host port

# composer 安装
composer install

# 执行安装脚本
./install.sh

# 运行站点
./iirosebot run
```
> 访问测试 [http://localhost:8008](http://localhost:8008)

## 插件开发

> 请转到 ~~wiki~~ 还没有写

## 前端开发

> 遵循API即可（是不是可以完成一些奇奇怪怪的程序？例如......易语言插件？）

[POSTMAN文档 ](https://documenter.getpostman.com/view/10410469/T1DiFzz8?version=latest)
