
	function qT(Div, myPos, atPos, Cont, styClss){
		$(Div).qtip({content: Cont, 
			overwrite: true, // Whether or not div qTip can be overwritten
			position: {
               		my: myPos,  
                   	at: atPos, 
               	},
           	show: {
               		event: false,
                   	ready: true
               	},
           	hide: false,
           	style: {
           		classes: styClss 
           	}	
		});	
	}
// QTIP Single Destroyer
	// Pass in Div, remove qTip
	function qTD(Div){
		Div.qtip('destroy');
	}
	
// QTIP Multiple Destroyer
	// Pass in location to destroy all qTips in location
	function qTMD(loc){
		loc.each(function(){qTD($(this))}); // Remove all qTips on form if closed
	}
 //REGISTER FROM HERE DOWN
function prgBar(){
		if(!$('#pg').length){
		$('.ui-dialog-buttonpane').prepend("<div id=\"pgBar\"style=\"width:270px;float:left;top: 4px;position: relative;\"></div>");
		$('#pgBar').progressbar({
			value:0
		});
		}
	}
	function resetOnClose(){
		$('#pgBar').remove();
		clearForm('#RegD');
		qTMD($('#RegD :input'));
		//reset fields
	}
    function clearForm(div){
    	$(':input',div).not(':button',':submit',':reset', ':hidden').val('');
    }
function Regvalidate(Obj){
		//disable reg button
		$(":button:contains('Register')").attr("disabled","disabled").addClass("ui-state-disabled");
		$('#pgBar').progressbar('value', 0);
		checkUsername(Obj, true);
		// re enables the Register Button
	}
	function enableReg(){
		      $(":button:contains('Register')").removeAttr("disabled").removeClass("ui-state-disabled");
	}
	function prgBar(){
		if(!$('#pg').length){
		$('.ui-dialog-buttonpane').prepend("<div id=\"pgBar\"style=\"width:270px;float:left;top: 4px;position: relative;\"></div>");
		$('#pgBar').progressbar({
			value:0
		});
		}
	}
	function checkUsername(user, validate){
		var obj ={};
		obj.username = user.username;
		if(user.username.length < 6){
			// Create qTip
			qT('#nuser', 'left center', 'right center', 'Minimum of 6 Characters', 'ui-tooltip-red');
			enableReg();
			return false;
		}
		else{
			// Destroy qTip
			qTD($('#nuser'));
		}
		$.ajax({
			type:'POST',
			url:'login/username',
			data:$.param(obj),
			dataType:'text',
			success:function(data){
				console.log(data);
				if(data == 'false'){
					// Destroy qTip
					qTD($('#nuser'));
				}
				else{
					// Create qTip
					qT('#nuser', 'left center', 'right center', 'Username Already Exists', 'ui-tooltip-red');
					enableReg();
					return false;
				}
				validate?$('#pgBar').progressbar('value', 10):false;
				if(validate){
					console.log('validating .. ');
					$('#pgBar').progressbar('value', 15);
					if(checkPass()){
						console.log('password good');
						$('#pgBar').progressbar('value', 25);
						if(checkEmail()){
							console.log('email good');
							$('#pgBar').progressbar('value', 40);
							var psn = user.psn!=""?true:false;
							var xbox = user.xbox!=""?true:false;
							if(!psn && !xbox){
								// Create qTip
								qT('#Puser', 'left center', 'right center', 'Please Provide at Least One Account', 'ui-tooltip-red');
								qT('#Xuser', 'left center', 'right center', 'Please Provide at Least One Account', 'ui-tooltip-red');
								enableReg();
								return false;
							}
							// Destroy qTip
							qTD($('#Puser'));
							qTD($('#Xuser'));
							if(psn){
								console.log('checking psn');
							checkPsnTag(user, xbox);
							}
							else{
								checkXboxTag(user);
							}
						}
						else{
							enableReg();
							return false;
						}
					}
					else{
						enableReg();
						return false;
					}
				}
			}
		});
	}
	
	
	function checkPsnTag(tag, xbox){
		var obj = {};
		obj.tag = tag.psn;
		$.ajax({
			type:'POST',
			url:'proxyService/psn',
			data:$.param(obj),
			dataType:'json',
			success: function(data){
				if(data == 'exists'){
					// Create qTip
					qT('#Puser', 'left center', 'right center', 'PSN Account Already Exists', 'ui-tooltip-red');
					enableReg();
					return;
				}
				if($.isEmptyObject(data)){
					// Create qTip
					qT('#Puser', 'left center', 'right center', 'Could Not Find Account', 'ui-tooltip-red');
					enableReg();
					return;
				}
				else{
					// Destroy qTip
					qTD($('#Puser'));
					$('#pgBar').progressbar('value', 70);
					if(xbox){
						console.log('checking xbox');
						checkXboxTag(tag);
					}
					else{
						userAdd(tag);
					}
				}
			}
		});
	}
	function userAdd(user){
		$.ajax({
			type:'POST',
			url:'login/add',
			dataType: 'text',
			data:$.param(user),
			success: function(data){
				$('#pgBar').progressbar('value', 100);
				alert('Please login to verify account');
				enableReg();
				$('#RegD').dialog('close');
				
			}
		});
	}
	function checkXboxTag(tag){
		var obj = {};
		obj.tag = tag.xbox;
		$.ajax({
			type:'POST',
			url:'proxyService/xbox',
			data: $.param(obj),
			dataType:'json',
			success: function(data){
				var res = data;
				if(data == "exists"){
					console.log("ERR");
					// Create qTip
					qT('#Xuser', 'left center', 'right center', 'Live Account Already Exists', 'ui-tooltip-red');
					enableReg();
					return;
				}
				if(res == "error 404"){
					// Create qTip
					qT('#Xuser', 'left center', 'right center', 'Could Not Find Account', 'ui-tooltip-red');
					enableReg();
					return;
				}
				/*
				 * ADD CONDITION FOR data.error.code != undefined
				 */
				else{
					$('#pgBar').progressbar('value', 100);
					// Destroy qTip
					qTD($('#Xuser'));
					userAdd(tag);
				}
			}
		});
	}
	function checkEmail(){
		var email = $('#email').val();
		if(email.indexOf('@', 1) != -1){
			// Destroy qTip
			qTD($('#email'));
			return true;
		}
		else{
			// Create qTip
			qT('#email', 'left center', 'right center', 'Please Enter a Valid Email', 'ui-tooltip-red');
			enableReg();
			return false;
		}
	}
	function checkPass(){
		if($('#pass').val().length < 6){
			// Create qTip
			qT('#pass', 'left center', 'right center', 'Minimum of 6 Characters', 'ui-tooltip-red');
			enableReg();
			return false;
		}
		else{
			// Destroy qTip
			qTD($('#pass'));
		}
		if($('#pass').val() != $('#cpass').val() ){
			// Create qTip
			qT('#cpass', 'left center', 'right center', 'Passwords Do Not Match', 'ui-tooltip-red');
			enableReg();
			return false;
		}
		else{
			// Destroy qTip
			qTD($('#cpass'));
			return true;
		}
		}
    $(function(){
    $('#RegD').dialog({
    	autoOpen: false,
    	width: 460,
    	modal: true,
    	title: 'Register',
    	resizable: false,
		buttons: {
            "Register": function() {
            	var Obj = {};
            	Obj.username = $('#nuser').val();
            	Obj.password = $('#cpass').val();
            	Obj.email = $('#email').val();
            	Obj.psn = $('#Puser').val();
            	Obj.xbox = $('#Xuser').val();
            	Regvalidate(Obj);
            },
            "Cancel": function() {
             $(this).dialog("close");
            }
       },
       open:function(){
       	prgBar();
       },
       close:function(){
       	resetOnClose();
       }
    });
    $('#pass').change(function(){
    	checkPass();
    });
    $('#cpass').change(function(){
    	checkPass();
    });
    $('#email').change(function(){
    	checkEmail()
    });
    //Added Register here 
    $('#nuser').change(function(){
    	var obj = {};
    	 obj.username = $(this).val();
    	checkUsername(obj);
    });
    //pass cpass
    $('.reg').click(function() {
        $('#RegD').dialog('open');
        return false;
    });
  })
