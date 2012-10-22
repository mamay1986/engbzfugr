$(document).ready(function(){
    $('input.fieldfocus,textarea.fieldfocus').fieldFocus();
    $(".show").colorbox({
        rel:'show'
    });
    //$( "#datepicker" ).datepicker();
	
	$(".list-afiwa li").hover(
		function () {
			var rel = $(this).attr('rel');
			$(".list-afiwa li").removeClass("active");
			$(".image-block-afiwa li").hide();
			$(this).addClass("active");
			$("li."+rel+"").show();
		}, 
		function () {
			
		}
	);
	$(".datelist .leftbut").hide();
	$(".datelist .rightbut").live('click',function(){
		var margin_left = parseInt($("#spisok-items-week-date").css('margin-left'))-51;
		$("#spisok-items-week-date").css({'margin-left': margin_left+'px'});
		if(parseInt($("#spisok-items-week-date").css('margin-left'))<=-1530){
			$(".datelist .rightbut").hide();
			$(".datelist .leftbut").show();
		}else{ 
			$(".datelist .rightbut").show();
			$(".datelist .leftbut").show();
		}
		//alert(margin_left);
	});
	$(".datelist .leftbut").live('click',function(){
		var margin_left = parseInt($("#spisok-items-week-date").css('margin-left'))+51;
		$("#spisok-items-week-date").css({'margin-left': margin_left+'px'});
		if(parseInt($("#spisok-items-week-date").css('margin-left'))>=0){
			$(".datelist .leftbut").hide();
			$(".datelist .rightbut").show();
		}else{ 
			$(".datelist .leftbut").show();
			$(".datelist .rightbut").show();
		}
		//alert(margin_left);
	});
	
	$(".afisha-topic a").live('click',function(){
		$(".afisha-topic a").removeClass('active');
		$(this).addClass('active');
	});
	
	/*-----popup----*/
	$("#close_popup_likes a").live('click',function(){
		$("#current, #popup_likes").hide();
	});
	/*$("#current").live('click',function(){
		$("#current, #popup_likes").hide();
	});*/
	/*-----popup----*/
	
	showInputs($('#parent select').val());
	
});

/*места*/
function showInputsParams(array_all,array){
	try{
		var str = "parent,caption,addres,date_work,phone,email,web,wifi,bron_cherez_engels,text";
		for(i=0;i<array_all.length;i++){
			$("#"+array_all[i]).hide();
		}
		for(i=0;i<array.length;i++){
			$("#"+array[i]).show();
			str +=","+array[i];
		}
		document.getElementById("filds").value=str;
	}catch(e){
		return true;
	}
}

function showInputs(id){
	try{
		/*'caption','addres','date_work','phone','email','web','wifi','bron_cherez_engels','kitchen','average_chek','business_lunch','banket','more_services','capacity','steam','pool','restroom','music','residents','num_dance_flors','num_track','type_billiards','num_tables'*/
		var array_all = ['kitchen','average_chek','business_lunch','banket','more_services','capacity','steam','pool','restroom','music','residents','num_dance_flors','num_track','type_billiards','num_tables'];
		//alert(id);
		switch(id){
			case '209'://рестораны
				//var array = ['kitchen','average_chek','business_lunch','banket','more_services'];
				//break;
			case '210'://кафе
				//var array = ['kitchen','average_chek','business_lunch','banket','more_services'];
				//break;
			case '211'://бары
				var array = ['kitchen','average_chek','business_lunch','banket','more_services'];
				break;
			case '212'://парки
				var array = [];
				break;
			case '213'://музеи
				var array = [];
				break;
			case '251'://отели и гостиницы
				var array = [];
				break;
			case '258'://клубы
				var array = ['kitchen','music','residents','num_dance_flors','more_services'];
				break;
			default:
				var array = [];
				break;
		}
		showInputsParams(array_all,array);
	}catch(e){
		return true;
	}
}
/*места*/

function setCookie(){
	$.cookie('like_cookie', '1', { expires: 2, path: '/', domain: 'engels.bz',});
}

function showCookie(){
	if(!$.cookie('like_cookie')){
		//$("#hiddenreg").css("top",getBodyScrollTop()+30);
		$('#current, #popup_likes').show();
		setCookie();
	}
}

function showPopup(){
	if(!$.cookie('like_cookie')){
		setTimeout("showCookie();", 10000);
	}
}

function getMeetsMain(time,i){
	$("#preloader_meets").show();
	$("#spisok-items-week-date .list").removeClass('active');
	$("#item-week-date"+i).addClass('active');
	xajax_getMeetsMain(time);
}

function getDate(obj){
    $("#select_date").html('<input type="text" name="filter[event_date]" value="" id="datepicker" style="width:200px;" />');
    $("#datepicker").datepicker();
    $("#datepicker").datepicker('show');
}
/*рейтинг*/
function addStarRating(id_content,rating){
	xajax_addStar(id_content,rating);
}
/*рейтинг*/