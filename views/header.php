<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Game Stalker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<script type="text/javascript" src="<?php echo URL ?>jquery.js"></script>
		<script type="text/javascript" src="<?php echo URL ?>js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="<?php echo URL ?>js/jquery-ui-1.8.17.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo URL ?>public/js/navi.js"></script>
		<script type="text/javascript" src="<?php echo URL ?>public/js/plugins/isotope/isotope.js"></script>
		<script type="text/javascript" src="<?php echo URL ?>public/js/plugins/isotope/centeredMasonry.js"></script>
		<link type="text/css" href="<?php echo URL ?>css/dark-hive/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo URL ?>public/css/isotope/isotope.css" rel="stylesheet" />
		<script type="text/javascript">
		// needs to seperate jquery ui and isotope, and make them work with $.noconfict(function(){})
		
					$(function() {	
						function getPSN(){
							var id = {};
							id.psnID = 'blackbird7180';
							$.ajax({
								type:'POST',
								url:'proxyService/psn',
								dataType:'json',
								success: function(data){
									console.log(data);
									$('#Pavatar').empty();
									var img = "<img style=\"width:100%;height:100%\" id=\"theImg\" src=\""+data.AvatarMedium+"\" />";
									$('#Pavatar').prepend(img);
									$('#plat').parent().append(data.Trophies.Platinum);
									$('#gold').parent().append(data.Trophies.Gold);
									$('#silver').parent().append(data.Trophies.Silver);
									$('#bronze').parent().append(data.Trophies.Bronze);
								}
							});
						}
		var $container = $('#isoParent');
						$container.isotope({
							itemSelector : '.item',
							layoutMode: 'masonry',
							masonry:{
								columnWidth: 60,
								gutterWidth:10,
							},
							filter: '.main'
						},ranLay());
			function getthird(){
				console.log($('#CB').width());
				return Math.floor($('#CB').width()/3.08);
			}
			function addPlats(){
				getPSN();
				$('#login').hide();
				$.each($('.filter'),function(){
					$(this).show();
				});
				$('.logout').show();
				$container.isotope({filter:'.home'},ranLay());	
			}
			
			function validate(info){
            $.ajax({
            type:'POST',
            url:'login/run',
            data: $.param(info),
            success: function(data){
            	console.log(data);
            	if(data != ""){
            		$('#loginD').dialog("close");
            		$('#ux').hide();
            		$('#px').hide();
            		addPlats();
            	}
            	else{
            		$('#ux').show("explode",50);
            		$('#px').show("explode",50);
            		$('#loginD').effect("shake", {times : 3}, 100);
            	}
            }
            });
           }
           function ranLay(){
          $container.isotope({sortBy: 'random'});
          }
          function growglow(){
           $.each($('.contentBox'),function(){
           var w = $(this).width();
           	var h = $(this).height();
            $(this).hover(
           	function(){
           	$(this).css('cursor', 'pointer');
           	//make this with animate
           	$(this).stop(true,true).animate({
           		width: '+=5',
           		height: '+=5'
           	}, 200);
           	var color = $(this).css('color');
           	$(this).css("text-shadow", "0px 0px 15px " + color);
           	},
           	function(){
           		$(this).stop(true,true).animate({
           		width: '-=5',
           		height: '-=5'
           	},200);
           	$(this).css("text-shadow","");	
           	}
           	);
           	});
           }
	growglow();
	// Register validator under construction
	function Regvalidate(obj){
		
		
	}
	function checkUsername(user){
		$.ajax({
			type:'POST',
			url:'login/username',
			data:$.param(user),
			dataType:'text',
			success:function(data){
				console.log(data);
				if(data == 'false'){
					$('#nux').hide();
					return true;
				}
				else{
					$('#nux').show();
					return false;
				}
			}
		});
	}
	function checkPsnTag(tag){
		$.ajax({
			type:'POST',
			url:'proxyService/psn',
			data:$.param(tag),
			datatype:'json',
			success: function(data){
				console.log(data);
			
			}
		})
	}
    //Nick Added Register here 
    $('#nuser').change(function(){
    	var user = $(this).val();
    	if(user.length < 6)
    		return false;
    	var obj = {};
    	 obj.username = $(this).val();
    	checkUsername(obj);
    });
    //pass cpass
    $('#pass').change(function(){
    	if($(this).val().length < 6){
    		$('#px')
    	}
    });
    $('#cpass').change(function(){
    	var pass = $('#pass').val();
    	var cpass = $(this).val();
    	if(pass != cpass){
    		// put x on pass word
    	}
    });
    $('#RegD').dialog({
    	autoOpen: false,
    	width: 450,
    	modal: true,
		buttons: {
            "Login": function() {
            $(this).dialog("close");
              	
            },
            "Cancel": function() {
             $(this).dialog("close");
            }
        }
    });
    $('.reg').click(function() {
        $('#RegD').dialog('open');
        return false;
    });
    //Nick Ended Register here 
    $('#loginD').dialog({
        autoOpen: false,
        width: 360,
        modal: true,
        resizable: false,
        buttons: {
            "Login": function() {
            	var obj = {};
            	obj.username = $('[name=username]').val();
            	obj.password = $('[name=password]').val();
					validate(obj);       	
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }       
    });
    $('#login').click(function() {
        $('#loginD').dialog('open');
        return false;
    });
    $('#rand').click(function(){
    	console.log("HERE");
    	$container.isotope({sortBy: 'random'});
    	 return false;
    });
     $('#psn').click(function(){
 	console.log("HERE");
 	$container.isotope({filter:'.psn'});
 });
 $('.logout').click(function(){
					$container.isotope({filter:'.main'},ranLay());
					$('#login').show();
					$(this).hide();
					$.each($('.filter'),function(){
					$(this).hide();
				});
	});
});
</script>
		<link rel="stylesheet" type="text/css" href="<?php echo URL ?>public/css/toolbar.css"/>
		<style>
		html,body {
  height: 98;
  overflow-x:hidden;
}
@font-face{
	font-family: "Technovia";
	src: url('public/fonts/technott.ttf');
}
			body {
				background-image: url('public/imgs/black.jpg');
				 background-size: auto;
				width: 99%;
				height: 100%;
			}
			#isoParent {
				margin:0 auto;
				border: 2px solid #4E4E4E;
			}
			.ui-dialog .ui-dialog-titlebar {
				padding: 0em;
			}
			.ui-dialog .ui-dialog-buttonpane {
				margin: 0px;
				padding: 0em;
			}
			.ui-button-text-only .ui-button-text {
				padding: 0em 0.4em;
			}
			.content-con{
				border: 1px solid white;
				border-radius:5px;
				float:left;
				padding: 5px;
				margin: 10px;
			}
			.ximg{
				width:12px;
				height:12px;
			}
			.ui-dialog .ui-dialog-content{
				padding:.5em;
			}
			.contentBox{
				border:2px solid white;
				border-radius:10px;
				box-shadow: 10px 10px 5px #333;
				background-color:#555555;
				margin: 10px;
			}
			.login{
				color:white;
				font-size:15px;
				float:right;
			}
			.login p{
				font-size:15px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;
				position: relative;
				top: -6px;
			}
			.reg{
				width:200px;
				height:100px;
				background-color:#555555;
				color:white;
			}
			.reg p{
				font-size:31px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;
			}
			.about{
				width:200px;
				height:100px;
				background-color:white;
				color:black;
				border:2px solid black;
			}
			.about p{ 
				font-size:31px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;
				
			}
		/* selectors for filtering*/
			.main .home .filter .psn .logout
			#grandad{
				width:100%;
				height:100%;
				position:absolute;
			}
			#Pplayercard{
				width:350px;
				height:150px;
			}
			#Pavatar{
			width: 100px;
height: 100px;
border: 2px solid black;
margin: 22px 12px 22px 22px;
border-radius: 10px;
float: left;
padding: 3px;
			}
			#trophies{
				color:white;
				font-size:15px;
			width: 178px;
border: solid;
height: 105px;
float: left;
border-radius: 20px;
padding: 5px;
margin: 16px 0 10px 0;
			}
			.trophy{
				width: 20px;
				height: 20px;
				margin: 1px;
				margin-left: 4px;
			}
		</style>
	</head>
	<body>
		<div id="grandad">
		<div class="toolbar">
		<div id="banner">
			<span id="logo" >GameStalker</span>
			<div id="login" class="login toolEle"><p>Login</p></div>
			<div class="login toolEle logout" style="display:none"><p>LogOut</p></div>
			<div class="filter toolEle" style="display:none;float:left"><div id="xbox"></div></div>
			<div class="filter toolEle" style="display:none;float:left;left:-1px"><div id="psn"></div></div>
			<div class="filter toolEle" style="display:none;float:left;left:-2px"><div id="steam"></div></div>
		</div>
		</div>
			<div id="isoParent" style="padding:10px">
			<div  class="contentBox reg item main"><p>Register</p></div>
			<div  class="contentBox about item main home"><p>About</p></div>
			<div id='Pplayercard' class='psn item contentBox'>
			<div id='Pavatar'></div>
			<div id='trophies'>
				<div><img id="palt" class="trophy" src="public/imgs/trophies/platinum.png"/></div>
				<div><img id="gold" class="trophy" src="public/imgs/trophies/gold.png"/></div>
				<div><img id="silver" class="trophy" src="public/imgs/trophies/silver.png"/></div>
				<div><img id="bronze" class="trophy" src="public/imgs/trophies/bronze.png"/></div>
			</div>
		</div>
			</div>
		<div id="loginD" >
			<table>
				<tr>
					<td>Username: </td>
					<td>
					<input type="text" name="username" />
					</td>
					<td class="ximg" id="ux" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>Password: </td>
					<td>
					<input type="password" name="password"/>
					</td>
					<td class="ximg" id="px" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
			</table>
		</div>
		<div id="RegD" >
			<table>
				<tr>
					<td>Username: </td>
					<td>
					<input id="nuser" type="text" name="nuser" />
					</td>
					<td class="ximg" id="nux" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>Password: </td>
					<td>
					<input id="pass" type="password" name="pass"/>
					</td>
				</tr>
				<tr>
					<td>Confirm Password: </td>
					<td>
					<input type="password" id="cpass" name="cpass"/>
					</td>
					<td class="ximg" id="cx" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>Email: </td>
					<td>
					<input type="text" name="Email"/>
					</td>
				</tr>
				<tr>
					<td>PSN Username:  </td>
					<td>
					<input type="text" name="Puser"/>
					</td>
					<td class="ximg" id="psnx" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<tr>
					<td>Xbox Live Gamertag: </td>
					<td>
					<input type="text" name="Xuser"/>
					</td>
					<td class="ximg" id="xboxx" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>
				<!--<tr>
					<td>Steam ID: </td>
					<td>
					<input type="text" name="Suser"/>
					</td>
					<td class="ximg" id="px" style="display:none"><img src="public/imgs/x.png" /></td>
				</tr>-->
			</table>
		</div>
		</div>
	</body>
</html>
