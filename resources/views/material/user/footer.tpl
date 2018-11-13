	<footer class="ui-footer">
		<div class="container">
			<marquee>&copy; {$config["appName"]}  <a href="/staff">STAFF</a> <marquee> {if $config["enable_analytics_code"] == 'true'}{include file='analytics.tpl'}{/if}
		</div>
	</footer>

	<!-- js -->
	<script src="https://cdnjs.loli.net/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0"></script>
	<script src="//static.geetest.com/static/tools/gt.js"></script>

	<script src="/theme/material/js/base.min.js"></script>
	<script src="/theme/material/js/project.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/clipboard@1.5.16/dist/clipboard.min.js"></script>
	<!--选择你需要的背景js，并注释掉另一个-->
	<!--1：原版-->
	<!--<script type="text/javascript" color="217,113,24" opacity="0.8" count="99" src="https://cdn.jsdelivr.net/npm/canvas-nest.js@1.0.1"></script>-->
	<!--2：樱花-->
	<script src="/assets/js/sakura.js"></script>


</body>
</html>
