<!DOCTYPE HTML>
<!--
	Spectral by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
{if $config['appName'] == '跑路'}
<script>window.location.href='{$config["baseUrl"]}/paolu.html';</script>
{/if}
<html>
	<head>
		<title>{$config["appName"]}</title>
        <meta name="keywords" content=""/>
        <meta name="description" content=""/>
        <meta charset="utf-8" />
        <link rel="shortcut icon" href="/favicon.ico"/>
        <link rel="bookmark" href="/favicon.ico"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="landing is-preload">

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header" class="alt">
						<h1><a href="#">{$config["appName"]}</a></h1>
						<nav id="nav">
							<ul>
								<li class="special">
									<a href="#menu" class="menuToggle"><span>菜单</span></a>
									<div id="menu">
										<ul>
										{if $user->isLogin}
											<li><a href="/">首页</a></li>
											<li><a href="/user/code">充值</a></li>
											<li><a href="/user/shop">续期</a></li>
											<li><a href="/user/node">节点列表</a></li>
											<li><a href="/user">用户中心</a></li>
											<li><a href="/user/logout">退出登录</a></li>
										{else}
											<li><a href="/">首页</a></li>
											<li><a href="/auth/login">登录</a></li>
											<li><a href="/auth/register">注册</a></li>
										{/if}
										</ul>
									</div>
								</li>
							</ul>
						</nav>
					</header>

				<!-- Banner -->
					<section id="banner">
						<div class="inner">
							<h2>{$config["appName"]}</h2>
							<p>全球流量中继服务<br>
							您的隐私，他人无权干涉<br>
							</p>
							<ul class="actions special">
								{if $user->isLogin}
									<li><a href="/user" class="button fit primary">用户中心</a></li>
									<li><a href="/user/logout" class="button fit">退出登录</a></li>
								{else}
									<li><a href="/auth/login" class="button fit primary">登录</a></li>
									<li><a href="/auth/register" class="button fit">注册</a></li>
								{/if}
								{if $user->isAdmin()}
								<li><a href="/admin" class="button fit primary">管理中心</a></li>
								{/if}
							</ul>
						</div>
						<a href="#one" class="more scrolly">查看更多</a>
					</section>

				<!-- One -->
					<section id="one" class="wrapper style1 special">
						<div class="inner">
							<header class="major">
							{if $user->isLogin}
								 <p>用户：<code>{$user->user_name}</code>
                                    等级：{if $user->class!=0}
											<code>VIP{$user->class}</code>
                                          {else}
                                             <code>免费</code>
                                              {/if}
									  过期时间：{if $user->expire_in!="1989-06-04 00:05:00"}
											    <code>{$user->expire_in}</code>
                                          {else}
                                              <code>不过期</code>
                                              {/if}</p>
                                  <p>总流量：<code>{$user->enableTraffic()}</code>
                                  已用流量：<code>{$user->usedTraffic()}</code>
                                  剩余流量：<code>{$user->unusedTraffic()}</code></p>
							</header>
						{else}
                          	<p>请登录以查看你的账户信息</p>
							<script type="text/javascript" src="https://api.lwl12.com/hitokoto/main/get?encode=js&charset=utf-8"></script><div id="lwlhitokoto"><script>lwlhitokoto()</script></div>
						{/if}
						</div>
					</section>

				<!-- Two -->
					<section id="two" class="wrapper alt style2">
						<section class="spotlight">
							<div class="image"><img src="images/pic01.jpg" alt="" /></div><div class="content">
								<h2>专注：<br>
								我们的团队投入了无数心血在节点的维护与建设上</h2>
								<p>本站分别针对不同的用户设置不同的付费等级，轻度用户5元/月即可使用。</p>
							</div>
						</section>
						<section class="spotlight">
							<div class="image"><img src="images/pic02.jpg" alt="" /></div><div class="content">
								<h2>质量：<br>
								只使用专业可靠的服务器以保证服务质量</h2>
								<p>本站使用微软、谷歌、亚马逊、阿里云等国际品牌的服务器进行搭建，能最大限度地保证稳定性和极佳的速度。</p>
							</div>
						</section>
						<section class="spotlight">
							<div class="image"><img src="images/pic03.jpg" alt="" /></div><div class="content">
								<h2>初心：<br>
								不忘初心，方得始终</h2>
								<p>念念不忘，必有回响。我们谨记初衷，不随大流，以最低的价格提供最好的服务。</p>
							</div>
						</section>
					</section>

				<!-- Three -->
					<section id="three" class="wrapper style3 special">
						<div class="inner">
							<header class="major">
								<h2>服务介绍</h2>
								<p>让您更大程度上的了解本站<br>
								</p>
							</header>
							<ul class="features">
								<li class="icon fa-paper-plane-o">
									<h3>使用ShadowsocksR</h3>
									<p>采用ShadowsocksR新一代流量中继服务，速度更快，稳定性更强。</p>
								</li>
								<li class="icon fa-paper-plane">
									<h3>多个数据中心</h3>
									<p>本站在全球有10+个数据中心，30+节点，为您的需求提供最大保证</p>
								</li>
								<li class="icon fa-laptop">
									<h3>全平台支持</h3>
									<p>我们支持Windows、MacOS、Linux、iOS、Android五大平台，让您随时畅享全球流量中继服务。</p>
								</li>
								<li class="icon fa-code">
									<h3>维护支持</h3>
									<p>我们的团队不断维护更新程序，只为给您最好的体验。</p>
								</li>
							</ul>
						</div>
					</section>

				<!-- CTA -->
				{if $user->isLogin}

				{else}
					<section id="cta" class="wrapper style4">
						<div class="inner">
							<header>
								<h2>立刻加入{$config["appName"]}</h2>
								<p>为您的隐私着想，也为了安全私密的网络环境着想。</p>
							</header>
							<ul class="actions stacked">
								<li><a href="/auth/login" class="button fit primary">登录</a></li>
								<li><a href="/auth/register" class="button fit">注册</a></li>
							</ul>
						</div>
					</section>
				{/if}

				<!-- Footer -->
					<footer id="footer">
						<!--<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
							<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
							<li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
						</ul>-->
						<ul class="copyright">
							<li>&copy;{date("Y")} {$config["appName"]}</li>
						</ul>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>