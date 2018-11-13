	<footer class="ui-footer">
		<div class="container">
			<marquee>&copy; {$config["appName"]}  <a href="/staff">STAFF</a> | Version 2.0 Build 88</marquee> {if $config["enable_analytics_code"] == 'true'}{include file='analytics.tpl'}{/if}
		</div>
	</footer>




	<!-- js -->
	<script src="/theme/material/js/jquery.min.js"></script>
	<script src="//static.geetest.com/static/tools/gt.js"></script>

	<script src="/theme/material/js/base.min.js"></script>
	<script src="/theme/material/js/project.min.js"></script>
	<!--选择你需要的背景js，并注释掉另一个-->
	<!--1：原版-->
	<!--<script type="text/javascript" color="217,113,24" opacity="0.8" count="99" src="https://cdn.jsdelivr.net/npm/canvas-nest.js@1.0.1"></script>-->
	<!--2：樱花-->
	<script src="/assets/js/sakura.js"></script>
</body>
</html>
