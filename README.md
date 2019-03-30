# 欢迎使用ss panel v3 mod 自带支付宝与微信监听修改版

- 免签约支付宝程序自检测 根据COOKIE
- 免签约微信支付程序自检测 根据COOKIE
- EMAIL通知失效情况
- 加入XCAT命令
- 加入定时任务自动检测
- 相关配置在.config.php
- 不定时同步[NimaQu](https://github.com/NimaQu/ss-panel-v3-mod_Uim)库
- 我的站点[云](http://yun.9in.info)
- 我的博客[CHEN](http://9in.info)

### 讨论群
https://t.me/chenAirport

### 本次更新
- 免签约微信支付程序自检测 根据COOKIE
- 支付宝判断完善
- 微信判断完善
- 多人同时支付错乱问题（点击x时会自动删除改订单间隔尽量缩短）
- 每分钟更改为5次的检测（测试不会被ban）
- 完善检测失效机制
- 添加手动开启支付开关（检测机制有可能在cookie失效情况下会直接关掉）
- 添加固定金额支付模式（也可手动输入支付模式）
- 改用依赖的形式（以后核心更新只需要```composer update chen-see/chen-pay```）
- ~~运行方式的改变不再使用定时任务（更稳定）~~
- 同步支付内核更新
- 更新支付宝双接口轮流切换API达到支付宝防止频繁访问阻止机制
- 如果单一接口出现阻止则会持续使用另外接口
- 更新日志保存地址为/storage/logs/chenpay.log
- 有订单情况下才是10秒一次的频率 杜绝支付宝风控
- 无订单情况下2分钟一次

### 相关截图
<img src="http://ww1.sinaimg.cn/large/006v0omggy1fvgz36p0ckj30u02kck43.jpg" width="300"/>
<img src="http://ww1.sinaimg.cn/large/006v0omggy1fvgzmfn25pj30u02xodt6.jpg" width="300"/>

### 使用步骤:
#### 1. 安装依赖
```
composer require chen-see/chen-pay
# 如有提示按Y
# 如无法正常安装可执行rm -rf vendor/然后再composer update
# 如监听时出现异常 yum update nss
```
#### 2. 设置配置文件
在配置文件```/config/.config.php```中的```$System_Config['payment_system']```后插入以下内容:
```
//支付系统设置--------------------------------------------------------------------
#取值 codepay,doiampay,paymentwall,zfbjk,spay,f2fpay,yftpay,none,f2fpay_codepay,chenAlipay
$System_Config['payment_system']='chenAlipay';
```

#### 3. 导入数据
- 使用一般mysql工具导入sql/config.sql即可（不会影响数据）

#### 4. 获取支付宝COOKIE
- 浏览器访问：https://mbillexprod.alipay.com/enterprise/tradeListQuery.htm
- 登录支付宝账号
- 浏览器按f12再刷新一下
- 可以看到tradeListQuery.json
- 点击header它找到Cookie: 后面就是cookie全部复制到后台配置框内

#### 5. 获取微信COOKIE
- 浏览器访问：https://wx.qq.com（此地址必须设置到后台支付设置里，登录完成后会有所变更）
- 手机扫码登录微信账号
- 浏览器按f12再刷新一下
- 可以看到webwxinit?r=*******
- 点击header它找到Cookie: 后面有cookie了

#### 6. 获取微信&支付宝付款码
- 直接到支付点加号
- 找到收付款
- 点击二维码收款
- 设置金额（如果你想多金额）
- 保存收款码
- 访问：https://cli.im/deqr 上传付款码二维码
- 解析出来的地址复制到后台管理的二维码地址框

#### 7. 执行命令
```
# crontab -e
# 方法一
* * * * * for((i=1;i<=5;i++));do php /你的目录/xcat wxpay;sleep 10;done
* * * * * for((i=1;i<=5;i++));do php /你的目录/xcat alipay;sleep 10;done
# 方法二
* * * * * php /你的目录/xcat wxpay
* * * * * sleep 10; php /你的目录/xcat wxpay
* * * * * sleep 20; php /你的目录/xcat wxpay
* * * * * sleep 30; php /你的目录/xcat wxpay
* * * * * sleep 40; php /你的目录/xcat wxpay
* * * * * sleep 50; php /你的目录/xcat wxpay
* * * * * php /你的目录/xcat alipay
* * * * * sleep 10; php /你的目录/xcat alipay
* * * * * sleep 20; php /你的目录/xcat alipay
* * * * * sleep 30; php /你的目录/xcat alipay
* * * * * sleep 40; php /你的目录/xcat alipay
* * * * * sleep 50; php /你的目录/xcat alipay
# 日志查看
# tail /你的目录/storage/logs/chenpay.log
```


**ss-panel-v3-mod**是一款专为shadowsocks设计的web前端面板，再次感谢ss-panel-v3-mod 的制作者，修改后的功能简介：

- **支付系统集成**：集成 支付宝当面付  易付通 码支付 TrimePay 等多种支付系统，使用方法见项目[wiki](https://github.com/NimaQu/ss-panel-v3-mod_Uim/wiki/)
- **UI** ：修改为 ~~援交~~ 圆角、并自定义了几个图标的显示，节点列表等級0可见等級1节点但无法看见节点详情，增加了国家图标显示
- **商店**：商品增加同时连接设备数，用户限速属性
- 从肥羊那里**抄**来的：新用户注册现金奖励|高等级节点体验|设备数量限制
- **优化**：css和js等置入本地提升加载速度
- 增加**v2Ray** 功能，详情请看 wiki: [Indexyz 版](https://github.com/Anankke/ss-panel-v3-mod_Uim/wiki/v2ray---indexyz-%E7%89%88%E4%BD%BF%E7%94%A8%E6%95%99%E7%A8%8B)  |  [Rico 版](https://github.com/Anankke/ss-panel-v3-mod_Uim/wiki/v2ray---Rico-%E7%89%88%E4%BD%BF%E7%94%A8%E6%95%99%E7%A8%8B)

Telegram 频道 ：https://t.me/sspanel_Uim

Telegram 非官方水群 ：https://t.me/SSUnion

演示站: [sspanel.host](https://sspanel.host) 账号和密码都是 admin  对接节点的 mukey=NimaQu

**原作者** [galaxychuck](https://github.com/galaxychuck)

[支持开发者请点我](https://github.com/NimaQu/ss-panel-v3-mod_UIM#%E5%85%B3%E4%BA%8E%E6%8D%90%E8%B5%A0)

## 搭建教程

GitHub Wiki : https://github.com/NimaQu/ss-panel-v3-mod_Uim/wiki/%E5%89%8D%E7%AB%AF%E5%AE%89%E8%A3%85

Wiki已经启用，欢迎为此面板维护wiki

#### 鸣谢

##### [galaxychuck](https://github.com/galaxychuck)

- 面板**原作者**

##### [hkserversolution](https://www.hkserversolution.com/cart.php)
- Demo 演示站服务器赞助

##### [dumplin](https://github.com/dumplin233) 

- 码支付对接 + 码支付当面付二合一
- 为面板加入 AFF 链接功能
- 商品增加限速和限制 ip 属性
- 多端口订阅
- 解决用户列表加载缓慢历史遗留问题

##### [RinSAMA](https://github.com/mxihan)

- 整理分类 config.php
- 美观性调整
- 客服系统优化

##### [miku](https://github.com/xcxnig)

- 美观和性能优化

##### [Tony Zou](https://github.com/ZJY2003)

- 为公告增加群发邮件功能
- 节点负载情况显示&用户账户过期在首页弹窗提醒
- 增加返利列表

[**Indexyz**](https://github.com/Indexyz)

- 为面板增加 v2Ray 功能

[**NeverBehave**](https://github.com/NeverBehave)

- 添加 Telegram OAuth

[**CGDF**](https://github.com/CGDF-GitHub)

- xcat一键update
- 适配SSD
- 用户列表分页加载

[**CHEN**](https://github.com/ChenSee)

- 免签约支付宝与微信，自带监听，不需第三方软件，直接到个人账户

[**laurieryayoi**](https://github.com/laurieryayoi)

- 重做美化UI（~~援交~~圆角化）

- 重写节点列表，支持分级显示所有级别节点

##### 还有所有被引用过代码的同学，以及所有提交过issue的同学。

#### 关于捐赠

您对我们的帮助将是支持我们做下去的动力，只需您在购买部分产品或向他人推荐产品时从我们的返利链接购买，这就是对我们很大的支持了。~~没有钱了，肯定要做啊，不做没有钱用啊，打工是不可能打工的，这辈子不可能打工的~~


##### dumplin

- [码支付-微信收款功能开通](https://codepay.fateqq.com/i/39756)

##### galaxychuck

- [黛米付-支付接入](https://www.daimiyun.cn/register.php?aff=624)
- [冲上云霄云主机](http://console.soar-clouds.com/aff.php?aff=94)
- [Vultr](https://www.vultr.com/?ref=7205737)

[laurieryayoi 的前端课程报名](https://t.me/kinokonominoco)



## 免责声明

本程序由 MIT License 授权。**不提供任何担保**。使用本程序即表明，您知情并同意：程序开发者不对此程序导致的任何服务中断、数据损失或任何少见未列出的事故负责。
