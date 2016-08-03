jQuery(document).ready(function($) {
	
	/**
	 * get areas, append to customized travel dropdown list
	 */
	$.ajax({
		url:"/listarea",
		dataType:"json",
		type:"get",
		success: function(response) {
			var ctDropdown = $("body").find(".customized-travel-dropdown");
			$.each(response.results, function(i, val){
				var item = "<li><a href='http://"+window.location.host+"/area/detail/"+val.Id+"' data-areaId="+val.Id+">"+val.Name+"</a></li>";
				ctDropdown.append(item);
			});
			ctDropdown.append('<li style="padding-left:80px; cursor:default;" onclick="foldAreaList();">[收起]</li>');
		},
		beforeSend: function() {
			console.log("fetching areas ...");
		},
		error: function(xhr, status, error) {
			console.log(xhr.responseText);
		}
	});

	/**
	 * get current url and append to Home href
	 */
	var home_url = "http://"+window.location.host;
	$("body").find(".home-url").attr("href", home_url);
	//get current url and append to customized_travel_url
	var customized_travel_url = "http://"+window.location.host+"/customizedtravel";
	$("body").find(".customized-travel-dropdown-toggle").attr("href", customized_travel_url);
	var shuttle_url = "http://"+window.location.host+"/shuttleservice";
	$("#shuttle-tab-header a").attr("href", shuttle_url);
	
	/**
	 * get current url and decide which tab should be highlighted
	 */
	$("#main-navbar li").removeClass("active");
	var current_url = window.location.pathname;
	if(current_url == "/"){
		$("#home-tab-header").addClass("active");
	}else if(current_url == "/customizedtravel"){
		$("#ct-tab-header").addClass("active");
	}else if(current_url == "/shuttleservice"){
		$("#shuttle-tab-header").addClass("active");
	}else if(current_url == "/information"){
		$("#info-tab-header").addClass("active");
	}else if(current_url == "/parteners"){
		$("#partener-tab-header").addClass("active");
	}
	
	/**
	 * load responsive sliders
	 */
	$("#slider").responsiveSlides({
      	auto: true,
      	nav: true,
      	speed: 500,
        namespace: "callbacks",
        pager: true,
      });
	
	/**
	 * open customized-travel dropdown 
	 */
	$(".customized-travel-dropdown-toggle").hover(function(e){
		e.preventDefault();
		$(".customized-travel-dropdown").css("display", "block");
	});
	
	/**
	 * user submit form in ct-main page
	 */
	$("#ct-customer-submission-form-submit").click(function(e){
		e.preventDefault();
		$("#ct-customer-submission-error-message ul").empty();
		var firstname = $("#ct-first-name input").val();
		var lastname = $("#ct-last-name input").val();
		var phone = $("#ct-phone input").val();
		var email = $("#ct-email input").val();
		var wechat = $("#ct-wechat input").val();
		var message = $("#ct-message-textarea textarea").val();
		var ready = true;
		if(firstname=='' || lastname==''){
			ready = false;
			$("span.name-span").css("display","inline");
		}else{
			$("span.name-span").css("display","none");
		}
		if(phone=='' && email=='' && wechat==''){
			ready = false;
			$("span.contact-span").css("display","inline");
		}else{
			$("span.contact-span").css("display","none");
		}
		if(message==''){
			ready = false;
			$("span.message-span").css("display","block");
		}else{
			$("span.message-span").css("display","none");
		}
		if(ready){
			console.log("ready to submit");
			var error_area = $("#ct-customer-submission-error-message ul");
			$.ajax({
				url:"/customizedtravel/submit",
				dataType:"json",
				type:"post",
				data:{firstname:firstname,
					lastname:lastname,
					phone:phone,
					email:email,
					wechat:wechat,
					message:message},
				success: function(response) {
					$("#ct-customer-submission-form-submit").prop("disabled",false);
					error_area.empty();
					if(response.success){
						$("#ct-customer-submission-form")[0].reset();
						error_area.append("已成功提交！工作人员将稍后联系您，谢谢您的耐心等待^_^");
					}else{
						if(response.result != null){
							if("errors" in response.result){
								$.each(response.result.errors, function(i,val){
									error_area.append("<li>"+val+"</li>");
								});
							}
						}						
					}
				},
				beforeSend: function() {
					$("#ct-customer-submission-form-submit").prop("disabled",true);
					error_area.append("请稍后，提交中……");
				},
				error: function(xhr, status, error) {
					console.log(xhr.responseText);
					$("#ct-customer-submission-form-submit").prop("disabled",false);
					error_area.empty();
				}
			});
		}
	})
	
});

/**
 * close customized-travel dropdown
 */
function foldAreaList()
{
	$(".customized-travel-dropdown").css("display", "none");
}
