

{include file='user/main.tpl'}







	<main class="content">
		<div class="content-header ui-content-header">
			<div class="container">
				<h1 class="content-heading">商品列表</h1>
			</div>
		</div>
		<div class="container">
			<div class="col-lg-12 col-sm-12">
				<section class="content-inner margin-top-no">
					
					<div class="card">
						<div class="card-main">
							<div class="card-inner">
								<p><font color="red" size="4">商品不可叠加，新购商品会覆盖旧商品的效果。</font></p>
								<p>当前余额：<font color="red" size="4"> {$user->money}</font> CNY</p>
							</div>
						</div>
					</div>
                  
                  <div class="card">
						<div class="card-main">
							<div class="card-inner">
								<p><font size="4">如果<font color="red" size="4">之前买的套餐开启了自动续费</font>，则在<font color="red" size="4">购买新套餐后</font>，<font color="red" size="4">必须</font>前往<a href="/user/bought">购买记录页面</a>关闭上个套餐的自动续费！<br>
								否则上个套餐到期仍会扣款！所造成损失本站不负责</font></p>
							</div>
						</div>
					</div>
					
					
					<div aria-hidden="true" class="modal modal-va-middle fade" id="coupon_modal" role="dialog" tabindex="-1">
						<div class="modal-dialog modal-xs">
							<div class="modal-content">
								<div class="modal-heading">
									<a class="modal-close" data-dismiss="modal">×</a>
									<h2 class="modal-title">您有优惠码吗？</h2>
								</div>
								<div class="modal-inner">
									<div class="form-group form-group-label">
										<label class="floating-label" for="coupon">有的话，请在这里输入。没有的话，直接确定吧</label>
										<input class="form-control" id="coupon" type="text">
									</div>
								</div>
								<div class="modal-footer">
									<p class="text-right"><button class="btn btn-flat btn-brand waves-attach" data-dismiss="modal" id="coupon_input" type="button">确定</button></p>
								</div>
							</div>
						</div>
					</div>
					
					
					<div aria-hidden="true" class="modal modal-va-middle fade" id="order_modal" role="dialog" tabindex="-1">
						<div class="modal-dialog modal-xs">
							<div class="modal-content">
								<div class="modal-heading">
									<a class="modal-close" data-dismiss="modal">×</a>
									<h2 class="modal-title">订单确认</h2>
								</div>
								<div class="modal-inner">
									<p id="name">商品名称：</p>
									<p id="credit">优惠额度：</p>
									<p id="total">总金额：</p>
									<p id="auto_reset">在到期时自动续费</p>
									
									<div class="checkbox switch" id="autor">
										<label for="autorenew">
											<input checked class="access-hide" id="autorenew" type="checkbox"><span class="switch-toggle"></span>自动续费
										</label>
									</div>
									
								</div>
								
								<div class="modal-footer">
									<p class="text-right"><button class="btn btn-flat btn-brand waves-attach" data-dismiss="modal" id="order_input" type="button">确定</button></p>
								</div>
							</div>
						</div>
					</div>
					
					{include file='dialog.tpl'}
	
			</div>
			
			
			
		</div>
		
		<div class="container">
			{foreach $groups as $group_name => $group}
			<div class="col-lg-3 col-sm-3">
				<div class="card">
					<div class="card-main">
						<div class="card-inner">
	                        <p align="center"><font color="orange" size=5>{$group_name}</font></p>
							<p align="center" id="{$group_name}_id">#</p>
							<p align="center" align="center" align="center" id="{$group_name}_price"> CNY</p>
	                        <p align="center" align="center" align="center" id="{$group_name}_content" class="card-shop-height"></p>
	                        <p align="center" align="center" id="{$group_name}_auto_renew"></p>
							<p align="center" id="{$group_name}_auto_reset_bandwidth"></p>
	                        <div class="form-group form-group-label">
	                        <label class="floating-label"><font color="red">点击下方价格选择更多时长套餐</font></label>
	                        <select id="{$group_name}_select" class="form-control" onchange="changeshop('{$group_name}')">
	                        	{foreach $group as $shop}
	                            	<option value="{$shop->id}">{$shop->price} CNY/{$shop->name}</option>
	                            {/foreach}
	                        </select>
	                        </div>
							<p><a class="btn btn-brand-accent" href="javascript:void(0);" onClick="buy('{$group_name}')">购买</a></p>
						</div>
					</div>
				</div>
			</div>
			{/foreach}
		</div>
	</main>









{include file='user/footer.tpl'}


<script>
$(document).ready(function(){
	shop = new Array();
	{foreach $groups as $group_name => $group}
		shop["{$group_name}"] = new Array();
		{foreach $group as $shop}
			shop["{$group_name}"]["{$shop->id}"] = new Array();
			shop["{$group_name}"]["{$shop->id}"]["name"] = "{$shop->name}";
			shop["{$group_name}"]["{$shop->id}"]["price"] = "{$shop->price}";
			shop["{$group_name}"]["{$shop->id}"]["content"] = "{$shop->content()}";
			shop["{$group_name}"]["{$shop->id}"]["auto_renew"] = "{$shop->auto_renew}";
			shop["{$group_name}"]["{$shop->id}"]["auto_reset_bandwidth"] = "{$shop->auto_reset_bandwidth}";
		{/foreach}		
		changeshop("{$group_name}");
	{/foreach}
});

function changeshop(group_name) {
	var id = $("#"+group_name+"_"+"select").val();
	var price = shop[group_name][$("#"+group_name+"_"+"select").val()]["price"];
	var content = shop[group_name][$("#"+group_name+"_"+"select").val()]["content"];
	var auto_renew = shop[group_name][$("#"+group_name+"_"+"select").val()]["auto_renew"];
	var auto_reset_bandwidth = shop[group_name][$("#"+group_name+"_"+"select").val()]["auto_reset_bandwidth"];
	$("#"+group_name+"_"+"id").html("商品ID："+id);
	$("#"+group_name+"_"+"price").html(price+" CNY");
	$("#"+group_name+"_"+"content").html(content);
	if(auto_renew==0)
	{
		$("#"+group_name+"_"+"auto_renew").html("不能自动续费");
	}
	else
	{
		$("#"+group_name+"_"+"auto_renew").html("可选 在 "+auto_renew+" 天后自动续费");
	}
	if(auto_reset_bandwidth==0)
	{
		$("#"+group_name+"_"+"auto_reset_bandwidth").html("不自动重置");
	}
	else
	{
		$("#"+group_name+"_"+"auto_reset_bandwidth").html("自动重置");
	}
}

function buy(group_name) {
	id = $("#"+group_name+"_"+"select").val();
	auto_renew = shop[group_name][$("#"+group_name+"_"+"select").val()]["auto_renew"];
	auto_reset_bandwidth = shop[group_name][$("#"+group_name+"_"+"select").val()]["auto_reset_bandwidth"];
	if(auto_renew==0)
	{
		document.getElementById('autor').style.display="none";
	}
	else
	{
		document.getElementById('autor').style.display="";
	}
	
	if(auto_reset_bandwidth==0)
	{
		document.getElementById('auto_reset').style.display="none";
	}
	else
	{
		document.getElementById('auto_reset').style.display="";
	}
	$("#coupon_modal").modal();
}


$("#coupon_input").click(function () {
		$.ajax({
			type: "POST",
			url: "coupon_check",
			dataType: "json",
			data: {
				coupon: $("#coupon").val(),
				shop: id
			},
			success: function (data) {
				if (data.ret) {
					$("#name").html("商品名称："+data.name);
					$("#credit").html("优惠额度："+data.credit);
					$("#total").html("总金额："+data.total);
					$("#order_modal").modal();
				} else {
					$("#result").modal();
					$("#msg").html(data.msg);
				}
			},
			error: function (jqXHR) {
				$("#result").modal();
                $("#msg").html(data.msg+"  发生了错误。");
			}
		})
	});
	
$("#order_input").click(function () {

		if(document.getElementById('autorenew').checked)
		{
			var autorenew=1;
		}
		else
		{
			var autorenew=0;
		}
			
		$.ajax({
			type: "POST",
			url: "buy",
			dataType: "json",
			data: {
				coupon: $("#coupon").val(),
				shop: id,
				autorenew: autorenew
			},
			success: function (data) {
				if (data.ret) {
					$("#result").modal();
					$("#msg").html(data.msg);
					window.setTimeout("location.href='/user/shop'", {$config['jump_delay']});
				} else {
					$("#result").modal();
					$("#msg").html(data.msg);
				}
			},
			error: function (jqXHR) {
				$("#result").modal();
                $("#msg").html(data.msg+"  发生了错误。");
			}
		})
	});

</script>