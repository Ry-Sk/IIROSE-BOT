# IIROSE-BOT

这是一个为[IIROSE](https://iirose.com/)设计的机器人框架

## 基础使用

打开运行站点（添加站点请提交pr）

1. [官方站](bot.imoe.xyz)

输入你机器人的用户名密码进行登录。

(机器人用户名需要在Ruby处报备)

在插件市场选择需要使用的插件点击添加

选择完成后进入进行配置

随后机器人将会前往机器人测试房间  [_5f06817deec1d_] ，在测试完成后联系hs_err更换房间。

## 本地运行（开发环境）

请先安装git,docker

克隆本项目

```shell
git clone https://github.com/iirose-tools/iirose-bot.git
```

进入项目目录

```shell
cd iirose-bot
```

运行docker

```shell
./entrypoint.sh # 可加参数，./entrypoint.sh host port
```

composer安装

```shell
composer install
```

执行安装脚本

```shell
./install.sh
```

运行站点

```shell
./iirose-bot run
```

访问测试[http://localhost:8008](http://localhost:8008)

## 插件开发

请转到wiki

## 前端开发

遵循API即可（是不是可以完成一些奇奇怪怪的程序？例如......易语言插件？）

[POSTMAN文档](https://documenter.getpostman.com/view/10410469/T1DiFzz8?version=latest)
