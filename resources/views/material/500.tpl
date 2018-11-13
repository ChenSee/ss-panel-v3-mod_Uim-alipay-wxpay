<!DOCTYPE HTML>
<!--
	Spectral by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
    <title>{$config["appName"]} - ERROR 500</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="bookmark" href="/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link href="/bootstrap" type="text/html" rel="stylesheet">
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
	<body class="is-preload">

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="/">{$config["appName"]}</a></h1>
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

				<!-- Main -->
					<article id="main">
						<header>
                            <h2>ERROR 500</h2>
                            <p>服务器崩溃了</p>
						</header>
						<section class="wrapper style5">
                            <div class="inner">
                                <p>
									别担心，这是我们的问题。你可以尝试联系站长</a>
                                </p>

                                <nav>
                                    <ul>
                                        <a href="./" class="button">返回首页</a>
                                    </ul>
                                </nav>
                            </div>
						</section>
					</article>

				<!-- Footer -->
					<footer id="footer">
						<ul class="copyright">
                            <li>&copy; {date("Y")} {$config["appName"]}</li>
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