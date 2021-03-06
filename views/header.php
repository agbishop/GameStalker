<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html >
	<head>
		<title>Game Stalker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<script type="text/javascript" src="public/js/ui/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="public/js/ui/jquery-ui-1.8.20.custom.min.js"></script>
		<script type="text/javascript" src="public/js/regQtip.js"></script>
		<script type="text/javascript" src="public/js/plugins/isotope/isotope.js"></script>
		<script type="text/javascript" src="public/js/plugins/isotope/centeredMasonry.js"></script>
		<script type="text/javascript" src="public/js/plugins/jqueryJson/json2.js"></script>
		<script type="text/javascript" src="public/js/plugins/qTip/jquery.qtip.min.js"></script>
		<script type="text/javascript" src="public/js/data.js"></script>
		<script type="text/javascript" src="public/js/loaders.js"></script>
		<script type="text/javascript" src="public/js/plugins/cookie/jquery.cookie.js"></script>
		<link type="text/css" href="public/css/dark-hive/jquery-ui-1.8.20.custom.css" rel="stylesheet" />
		<link type="text/css" href="public/css/isotope/isotope.css" rel="stylesheet" />
		<link type="text/css" href="public/css/qTip/jquery.qtip.min.css" rel="stylesheet" />
		<script type="text/javascript">
		// needs to seperate jquery ui and isotope, and make them work with $.noconfict(function(){})
		
					$(function() {	
						$.ajax({
								type:'GET',
								url:'user/getOps',
								dataType:'json',
								success:function(data){
								}
							});
						function sessionCheck(){
							$.ajax({
								type:'GET',
								url:'login/checkSession',
								dataType:'text',
								success:function(data){
									if(data == 'true'){
										getPlats();
									}
									else{
										logoutAjax();
										$container.isotope({filter:'.main'});
										$('#login').show();
										$('.logout').hide();
										$.each($('.filter'),function(){
										$(this).hide();
										});
								}
							}});
						}
						sessionCheck();
						function getPSN(){
							$.ajax({
								type:'GET',
								url:'proxyService/psn',
								dataType:'json',
								success: function(data){
									$('#Pavatar').empty();
									var img = "<img style=\"width:100%;height:100%\" id=\"theImg\" src=\""+data.AvatarMedium+"\" />";
									$('#Pavatar').prepend(img);
									var imgs = '<div><img id="palt" class="trophy" src="public/imgs/trophies/platinum.png"/></div><div><img id="gold" class="trophy" src="public/imgs/trophies/gold.png"/></div><div><img id="silver" class="trophy" src="public/imgs/trophies/silver.png"/></div><div><img id="bronze" class="trophy" src="public/imgs/trophies/bronze.png"/></div>';
									$('#trophies').empty().append(imgs);
									$('#plat').parent().append(data.Trophies.Platinum);
									$('#gold').parent().append(data.Trophies.Gold);
									$('#silver').parent().append(data.Trophies.Silver);
									$('#bronze').parent().append(data.Trophies.Bronze);
								}
							});
							
						}					
/////////////////////// COOKIE Stuff
	function getxbox(){
		$.ajax({
			type: 'GET',
			url:'forum/pullXboxGamesList',
			dataType:'text',
			success: function(data){
				console.log(data);
				// Destroy cookie
				$.cookie('gameList', null);
				// Store list in cookie
				$.cookie('gameList', data, {expires: 7, path:"/"});
			}
		});
		
		$.ajax({
			type:'GET',
			url:'proxyService/xbox',
			dataType:'json',
			success: function(data){
				$('#xCard').html('');
				var inner = "<div class=\"xAvatar\" ><img src=\"" + data.LargeAvatar + "\" /></div>";
				var memType = 'silver';
				if(data.Gold == "true"){
					memType = 'gold';
				}
				inner += "<div class=\"xInfo\" style=\"color:"+memType+"\" >"+ data.Gamertag + "</div><div class=\"xGameCon\">";
				for(var i = 0; i< 5;i++ ){
					inner +="<div class=\"xGame\" \><img src=\""+data.Games[i].GameThumb+ "\" /></div>";
				}
				inner += "<div class=\"xGamerScore\"> <img src=\"public/imgs/Gamerscore_icon.png\"/>" + data.GamerScore + "</div>"; 
				inner +="</div>";
				$('#xCard').html(inner);
				
				// If cookie exists
				var List = $.parseJSON($.cookie('gameList'));
				
				console.log(List);
				
				// Check to see if game exists in db
				for(var i = 0; i < 5; i++){
					//List[i].ID
					
					// If not, prepare to add and create forum entries
				}
				
				// Destroy cookie
				$.cookie('gameList', null);
			}
		});		
	}
	
		var $container = $('#isoParent');
						$container.isotope({
							itemSelector : '.item',
							layoutMode: 'masonry',
							resizesContainer: true,
							filter: '.main'
						});
			function addPlats(){
				data = $.parseJSON($.cookie('GameStalker_ids'));
				if(data.PsnId != null){
				getPSN();
				}
				if(data.XboxId != null){
				getxbox();
				}
				if(data.SteamId != null){
					//add getSteam()
				}
				$.each($('.rss'),function(){
					$('#isoParent').isotope('remove', $(this));
				});
				getFeeds();
				$('#login').hide();
				loadHome();
				loadToolbar();
				$.each($('.filter'),function(){
					$(this).show();
				});
				$('.house').show();
				$('.ops').show();
				$('.logout').show();
				$container.isotope({filter:'.home'});	
			}
			// actually this is redundnet
			function getFeeds(){
				$.each($('.rss'),function(){
					$('#isoParent').isotope('remove', $(this));
				});
				$.ajax({
				type:'GET',
				url:'RssFeeder/getRSS',
				dataType:'html',
				success:function(data){
				$('#isoParent').isotope('insert', $(data));
				growglow();
				}
			});
			}
			function getPlats(){
				if($.cookie('GameStalker_ids') == null){
				$.ajax({
					type:'GET',
					url:'login/getPlats',
					dataType:'json',
					success:function(data){
					$.cookie('GameStalker_ids',JSON.stringify(data),{ expires: 7, path: '/' });
					addPlats();
					}
				});
				}
				else{
					addPlats();
				}
			}
///////////////////////////////////// Forum Code						
			function getForum(){
				$.each($('.forum'),function(){
					$('#isoparent').isotope('remove', $(this));
				});
				
				var obj = {};
        		obj.gameName = "Mass Effect";
        		obj.gameID = '12345';
				
				$.ajax({
					type: 'POST',
					url:'forum/viewGameThreads',
					datatype:'html',
					success:function(data){
						console.log(data);
						$('#isoparent').isotope('insert', $(data));
						growglow();
					}
				});
			}
			
		function validate(info){
            $.ajax({
            type:'POST',
            url:'login/run',
            data: $.param(info),
            success: function(data){
            	if(!isNaN(parseInt(data))){
            		$('#loginD').dialog("close");
            		$('#ux').hide();
            		$('#px').hide();
            		getPlats();
            		qTD($(":button:contains('Login')"));
            	}
            	else{
            		$('#ux').show("explode",50);
            		$('#px').show("explode",50);
            		qT(":button:contains('Login')", 'right center', 'left center', 'Username or Password Invalid', 'ui-tooltip-red');
            		//disable button on animation becasue of trolls
            		$(":button:contains('Login')").attr("disabled","disabled");
            		$('#loginD').stop().effect("shake", {times : 3}, 100,function(){
            			$(":button:contains('Login')").removeAttr("disabled","disabled");
            		});
            	}
            }
            });
           }
          function growglow(){
           $.each($('.contentBox'),function(){
           var w = $(this).width();
           	var h = $(this).height();
            $(this).hover(
           	function(){
           	//make this with animate
           	$(this).stop(true,true).animate({
           		width: '+=3',
           		height: '+=3'
           	}, 200);
           	var color = $(this).css('color');
           	$(this).css("text-shadow", "0px 0px 15px " + color);
           	},
           	function(){
           		$(this).stop(true,true).animate({
           		width: '-=3',
           		height: '-=3'
           	},200);
           	$(this).css("text-shadow","");	
           	}
           	);
           	});
           }
	growglow();
	
	$('#loading').dialog({
     	autoOpen: false,
     	closeOnEscape: false,
     	resizable: false,
     	dialogClass: 'alert',
     	modal: true,
     	minWidth: 57,
     	width: 57,
     	height: 58
     });

// QTIP FUNCTION	
	// Div - what is qTip attaching to
	// myPos - place qTip's pos
	// atPos - place at 
	// Cont - text to display
	// StyClss - style class to use (check css)

    $('#forumHub').dialog({
    	autoOpen: false,
    	width: 800,
    	modal: true,
    	resizable: false
    });
    $('.forumMain').click(function() {
        $('#loading').dialog('open');
        var obj = {};
        obj.gameName = "Mass Effect";
        obj.gameID = '12345';
        
        $.ajax({type:'POST',
        	url:'forum/loadDB_Hub',
        	data:$.param(obj),
        	success:function(data){
         		console.log(data);
         		$.each($('.forum'), function(){
         			$('#isoParent').isotope('remove', $(this));
         		});
         		$('#loading').dialog('close');
         		$('#isoParent').isotope('insert',$(data));
         		$("#weapons").button();
         		$("#walkthrough").button();
         		$("#maps").button();
         		$("#spoilers").button();
         		$("#misc").button();
         		$('#isoParent').isotope({filter: '.forum'});
        }});        
        //$('#forumHub').dialog('open');
        return false;
    });         
     
     $('#AboutD').dialog({
    	autoOpen: false,
    	modal: true,
    	resizable: false
    });
        $('.about').click(function() {
        $('#AboutD').dialog('open');
        return false;
    });
    //Ended Register here
        $('.help').click(function() { 
     	$container.isotope({filter:'.helpcontent'});
    });
    
     $('.ops').click(function() { //ops is settings page
     	
     	$('.filter').hide();
        $('#isoParent').fadeOut(100, function(){
        	$('#Settings').fadeIn(100);
        });
        getSetOps();
    });
    function getSetOps(){
    	$.ajax({
    		type:'GET',
    		url:'user/getOps',
    		dataType:'json',
    		success: function(data){
    			$('#OXbox').html(data.Ids.XboxId);
    			$('#OPsn').html(data.Ids.PsnId);
    			$('#OSteam').html(data.Ids.SteamId);
    			$('#EXbox').val(data.Ids.XboxId);
    			$('#EPsn').val(data.Ids.PsnId);
    			$('#ESteam').val(data.Ids.SteamId);
    			if(data.Rss.pc.rss.ign)
    				$('#ignPc').attr('checked',true).button('refresh');
    			if(data.Rss.pc.rss.gs)
    				$('#gsPc').attr('checked',true).button('refresh');
    			if(data.Rss.pc.rss.up)
    				$('#upPc').attr('checked',true).button('refresh');
    			if(data.Rss.ps3.rss.ign)
    				$('#ignPs3').attr('checked',true).button('refresh');
    			if(data.Rss.ps3.rss.gs)
    				$('#gsPs3').attr('checked',true).button('refresh');
    			if(data.Rss.ps3.rss.up)
    				$('#upPs3').attr('checked',true).button('refresh');
    			if(data.Rss.xbox.rss.ign)
    				$('#ignXbox').attr('checked',true).button('refresh');
    			if(data.Rss.xbox.rss.gs)
    				$('#gsXbox').attr('checked',true).button('refresh');
    			if(data.Rss.xbox.rss.up)
    				$('#upXbox').attr('checked',true).button('refresh');
    		}
    	});
    }
    function SetOps(){
    	var data = {};
    	data.Rss = {};
    	data.Rss.pc = {};
    	data.Rss.ps3 = {};
    	data.Rss.xbox = {};
    	data.Rss.pc.rss = {};
    	data.Rss.ps3.rss = {};
    	data.Rss.xbox.rss = {};
    	if($('#ignPc').is(':checked'))
    		data.Rss.pc.rss.ign = true;
    	else
    		data.Rss.pc.rss.ign = false;
    	if($('#gsPc').is(':checked'))
    		data.Rss.pc.rss.gs = true;
    		else
    		data.Rss.pc.rss.gs = false;
    	if($('#upPc').is(':checked'))
    		data.Rss.pc.rss.up = true;
    		else
    		data.Rss.pc.rss.up = false;
    	if($('#ignPs3').is(':checked'))
    		data.Rss.ps3.rss.ign = true;
    		else
    		data.Rss.ps3.rss.ign = false;
    	if($('#gsPs3').is(':checked'))
    		data.Rss.ps3.rss.gs = true;
    		else
    		data.Rss.ps3.rss.gs = false;
    	if($('#upPs3').is(':checked') )
    		data.Rss.ps3.rss.up = true;
    		else
    		data.Rss.ps3.rss.up = false;
    	if($('#ignXbox').is(':checked'))
    		data.Rss.xbox.rss.ign = true;
    		else
    		data.Rss.xbox.rss.ign = false;
    	if($('#gsXbox').is(':checked'))
    		data.Rss.xbox.rss.gs = true;
    		else
    		data.Rss.xbox.rss.gs = false;
    	if($('#upXbox').is(':checked'))
    		data.Rss.xbox.rss.up = true;
    		else
    		data.Rss.xbox.rss.up = false;
    	var temp = JSON.stringify(data.Rss);
		var tempObj = {};
		tempObj.RssOps = temp;
    	$.ajax({
 		type:'POST',
 		url: 'user/Setops',
 		data: $.param(tempObj),
 		success: function(data){
 		}
 	});
 	getFeeds();
 }
    $('#loginD').dialog({
        autoOpen: false,
        width: 360,
        modal: true,
        resizable: false,
        title: 'Login',
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
    },
    close:function()
        {
        	clearForm('#loginD');
			qTD($(":button:contains('Login')"));
        } });
    $('#login').click(function() {
        $('#loginD').dialog('open');
        return false;
    });
    $('#rand').click(function(){
    	$container.isotope({sortBy: 'random'});
    	 return false;
    });
 function logoutAjax(){
    $('#Settings').fadeOut(100,function(){
    $('#isoParent').fadeIn(100);
     });
 	$.ajax({
 		type:'GET',
 		url: 'login/logout',
 	});
 	destroyHome();
 	destroyToolbar();
 }
 $('.logout').click(function(){
 					logoutAjax();
					$container.isotope({filter:'.main'});
					$('#login').show();
					$('.ops').hide();
					$(this).hide();
					$.each($('.filter'),function(){
					$(this).hide();
				});
				$.each($('.rss'),function(){
					$('#isoParent').isotope('remove', $(this));
				});
	});  
$('#Settings').accordion({
	autoHeight: false,
	navigation: true
});
$("button.pEdit").button();
$("button.pEdit").bind("click", function(e) {
    e.stopPropagation();
    $(this).blur();
    return false;
});
$('#ign').buttonset();
$('#gs').buttonset();
$('#up').buttonset();
$("button.RssEdit").button();
$("button.RssEdit").bind("click", function(e) {
    e.stopPropagation();
    $(this).blur();
    SetOps();
    return false;
});
});
</script>
		<link rel="stylesheet" type="text/css" href="public/css/toolbar.css"/>
		<style>
		html,body {
  overflow-x:hidden;
}
@font-face{
	font-family: "Technovia";
	src: url('public/fonts/technott.ttf');
}
@font-face{
	font-family: "venus";
	src: url('public/fonts/venus rising rg.otf');
}

			body {
				margin: 0px 8px 8px 8px;
				background-image: url('public/imgs/renzler.gif');
				background-repeat:repeat;
				/*background-size: auto;*/
				width: 99%;
				height: 100%;
			}
			#isoParent {
				clear:both;
				margin:0 auto;
				width:auto;
				left:-15px;
				background: rgba(0, 0, 0, 0.5);
				z-index:1;
				top:-10px;
				/*border: 2px solid #4E4E4E;*/
			}
			.ui-dialog .ui-dialog-titlebar {
				padding: 0em;
			}
			.ui-dialog .ui-dialog-buttonpane {
				margin: 0px;
				padding: 0em;
			}
			.ui-button-text-only .ui-button-text {
				padding: 0em 0.3em;
			}
			.ui-widget-header { 
				color:white;
				font-weight: normal;
				}
			.content-con{
				border: 1px solid black;
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
				border:2px solid black;
				border-radius:10px;
				box-shadow: 0px 0px 11px 2px #750404;
				background-color:#555555;
				margin: 10px;
			}
			.login{
				color:black;
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
				cursor:pointer;
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
				cursor:pointer;
			}
			.about p{ 
				font-size:31px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;
			}
			
			.doc{
				width:200px;
				height:100px;
				background-color:white;
				color:black;
				border:2px solid black;
				cursor:pointer;
			}
			.doc p{ 
				font-size:31px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;
				
			}

			.pEdit{
				float:right;
				top:-1px;
			}

			.help{
				width:200px;
				height:100px;
				background-color:white;
				color:black;
				border:2px solid black;
			}
			
			.forum{

				width:200px;
				height:100px;
				background-color:white;
				color:black;
				border:2px solid black;
			}

			.help p{ 
				font-size:31px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;	
			}
						
			.forum p{ 
				font-size:31px;
				text-align:center;
				font-family:Verdana, Tahoma, Geneva, sans-serif;
				text-decoration:none;				
			}
			
			.alert .ui-dialog-titlebar {
				display:none
			}						

			.idEdit{
				float:right;
				top:-1px;
			}
			.RssEdit{
				float:right;
				top:-1px;
			}
		/* selectors for filtering*/
			.main .home .filter .psn .logout .ops .house
			#grandad{
				width:100%;
				height:100%;
				position:absolute;
			}
			#Pplayercard{
				width:350px;
				height:150px;
				background-image: url('public/imgs/ps3_back.png');
				background-repeat:no-repeat;
				
			}
			#xCard{
				width:350px;
				height:150px;
			}
			#xavatar{
				height:100px;
				width:100px;
				float:left;
				
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
			#ids tr,td{
			padding:2px;				
			}
			#xCard{
				cursor:default;
				background-image:url('public/imgs/CardBack.jpg');
				background-position:-50px -70px;
				overflow:hidden;
			}
			.xAvatar{
				float: left;
			    left: -30px;
			    position: relative;
			    top: -60px;
			}
			.xGame{
				float: left;
			    margin: 4px;
			    position: relative;	
			}
			.xGameCon{
    			background-color: rgba(0, 0, 0, 0.7);
    			float: left;
			    left: -31px;
			    position: relative;
			    top: 31px;
			}
			.xInfo{
    			font-size: 20px;
			    height: 30px;
			    left: -31px;
			    position: relative;
			    top: 27px;
			    width: 300px;
			}
			.xGamerScore{
				color: white;
				padding: 3px;
				margin: 2px;
			}
			.friends{
				width:150px;
				height:400px;
				background-color: orange;
			}
			#Addfriends{
				width:80px;
			}
		</style>
	</head>
	<body>
		<div id="grandad">
			
			<div id="loading"><img style="width:40px;height:40px" src="./public/imgs/loader.gif"/></div>
			
		<div class="toolbar">
		<div id="banner">
			<span id="logo">GameStalker</span>
			<div id="login" class="login toolEle"><p>Login</p></div>
			<div class="login toolEle logout" style="display:none"><p>LogOut</p></div>
			<div class="login toolEle ops" style="width:40px;display:none;left:1px"><div id="ops"></div></div>
		</div>
		</div>
			<div id="isoParent" >
			<div  class="contentBox reg item main"><p>Register</p></div>
			<div  class="contentBox about item main home"><p>About</p></div>

			<div  class="contentBox help item home"><p>Help</p></div>
			<div  class="contentBox main doc item home"><a href="Docs/Docspage" target="_blank" style="display:block; width:200px; height:100px;"></a><p>Docs</p></div>
			<div  class="contentBox helpcontent item" style="color:white;font-weight:bold"><p>
				<img style="width:625px; height:131px" src="public/imgs/Docpictures/doctoolbar.png" />
				<p> Home: Takes you back to your homepage and saves any settings changed</p>
				<p> XBOX: Takes you to your XBOX statistics</p>
				<p> PSN: Takes you to your Playstation Network statistics</p>
				<p> Steam: Takes you to your Steam statistics</p>
				<img style="width:624px; height:131px" src="public/imgs/Docpictures/doctoolbarsettings.png" />
				<p>The Cog Takes you to the settings page</p>
				<p>Log out returns you back to the front page after logging you out</p>
				<img style="width:501px; height:317px" src="public/imgs/Docpictures/GameIds.png" />
				<p>After clicking on the Settings Page, an Accordion will show. Click the different tabs to reveal the information inside.</p>
				GameIDs will show your IDs for different platforms.
				<img style="width:500px; height:310px" src="public/imgs/Docpictures/RSSFeeds.png" />
				<p>This tab allows you to subscribe to different RSS Feeds. Click the boxes to highlight them blue to subscribe.</p>
				<p>Your profile shows you your personal profile information.</p>
				</div>

			<div  class="contentBox forumMain item main home"><p>Forum Test</p></div>
			
			<div id='Pplayercard' class='psn item contentBox'>
			<div id='Pavatar'></div>
			<div id='trophies'>
				<div><img id="palt" class="trophy" src="public/imgs/trophies/platinum.png"/></div>
				<div><img id="gold" class="trophy" src="public/imgs/trophies/gold.png"/></div>
				<div><img id="silver" class="trophy" src="public/imgs/trophies/silver.png"/></div>
				<div><img id="bronze" class="trophy" src="public/imgs/trophies/bronze.png"/></div>
			</div>
		</div>
		<div id="xCard" class='item contentBox xbox'>
				<div id="xavatar">
				</div>
				<div id ="score" style="float:left"></div>
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
					
				</tr>
				<tr>
					<td>Email: </td>
					<td>
					<input id="email" type="text" name="Email"/>
					</td>
					
				</tr>
				<tr>
					<td>PSN Username:  </td>
					<td>
					<input id="Puser"type="text" name="Puser"/>
					</td>
					
				</tr>
				<tr>
					<td>Xbox Live Gamertag: </td>
					<td>
					<input id="Xuser" type="text" name="Xuser"/>
					</td>
					
				</tr>
				<!--<tr>
					<td>Steam ID: </td>
					<td>
					<input type="text" name="Suser"/>
					</td>
					
				</tr>-->
			</table>
		</div>
		<div id="AboutD" >
				<p>GameStalker is an aggregated service that combines the statistics from Xbox Live, Playstation Network and Steam 
				to allow the Gamer to compare their performance with friends across all platforms. </p>
				<p>GameStalker will be updated with RSS Feeds and Platform News soon!</p>
		</div>
		<div id = "forumHub">
			<h1>Test</h1>
		</div>		
		<div id="Settings" style="display:none;color:white;width:500px;margin:0 auto; top:10px"> 
			<h3><a href="#">Game Ids</a></h3>
			<div>
			<table id="ids">
				<tr>
					<td>Xbox Id: </td>
					<td>
					<p id="OXbox" ></p>
					</td>
					
				</tr>
				<tr>
					<td>Psn Id: </td>
					<td>
					<p id="OPsn"></p>
					</td>
				</tr>
				<tr>
					<td>Steam Id: </td>
					<td>
					<p id="OSteam" type="text" name="pass"></p>
					</td>
				</tr>
			   </table>
			</div>  
			<h3><a href="#">RSS Feeds<button class="RssEdit"><div style="font-size:12px">Save</div></button></a></h3>
			<div> 
				<div>
					<label for="gs">Gamespot</label>
				<div id="gs">
					<input type="checkbox" id="gsPs3" value="GSpsn" /><label for="gsPs3">PS3</label>
					<input type="checkbox" id="gsXbox" value="GSxbox" /><label for="gsXbox">Xbox</label>
					<input type="checkbox" id="gsPc" value="GSsteam" /><label for="gsPc">PC</label>
				</div>
				<label for="ign">IGN</label>
				<div id="ign">
					<input type="checkbox" id="ignPs3" value="ignpsnvalue" /><label for="ignPs3">PS3</label>
					<input type="checkbox" id="ignXbox" value="ignxboxvalue" /><label for="ignXbox">Xbox</label>
					<input type="checkbox" id="ignPc" value="ignsteamvalue" /><label for="ignPc">PC</label>
				</div>
				<label for="up">1 Up</label>
				<div id="up">
					<input type="checkbox" id="upPs3" value="ignpsnvalue" /><label for="upPs3">PS3</label>
					<input type="checkbox" id="upXbox" value="ignxboxvalue" /><label for="upXbox">Xbox</label>
					<input type="checkbox" id="upPc" value="ignsteamvalue" /><label for="upPc">PC</label>
				</div>
		</div>
		</div>
		<h3><a href="#">Profile<button class="pEdit"><div style="font-size:12px">Edit</div></button></a></h3>
		<div>
			<p> Your profile</p>
		</div>
		</div>
		</div>
	</body>
</html>
