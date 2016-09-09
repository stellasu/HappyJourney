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
			ctDropdown.append('<li style="padding-left:5em; cursor:default;" onclick="foldAreaList();">[收起]</li>');
		},
		beforeSend: function() {
			//console.log("fetching areas ...");
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
	//admin urls
	$("body").find(".text-manage-url").attr("href", home_url+"/administration");
	var customized_travel_manage_url = "http://"+window.location.host+"/administration/customizedtravel";
	$("body").find(".customized-travel-manage-dropdown-toggle").attr("href", customized_travel_manage_url);
	var shuttle_manage_url = "http://"+window.location.host+"/administration/shuttleservice";
	$("body").find(".shuttle-service-manage-dropdown-toggle").attr("href", shuttle_manage_url);
	
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
	}else if(current_url == "/administration"){
		$("#text-manage-tab-header").addClass("active");
	}else if(current_url=="/administration/customizedtravel" || current_url=="/administration/customizedtravel/addarea" || current_url=="/administration/customizedtravel/editarea"){
		$("#ct-manage-tab-header").addClass("active");
	}else if(current_url=="/administration/shuttleservice" || current_url=="/administration/shuttleservice/manageitinerary" || current_url=="/administration/shuttleservice/additinerary"){
		$("#shuttle-manage-tab-header").addClass("active");
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
	 * open customized-travel and admin customized-travel, admin shuttle-service dropdown 
	 */
	$(".customized-travel-dropdown-toggle").hover(function(e){
		e.preventDefault();
		$(".customized-travel-dropdown").css("display", "block");
	});
	$(".customized-travel-manage-dropdown-toggle").hover(function(e){
		e.preventDefault();
		$(".customized-travel-manage-dropdown").css("display", "block");
		$(".shuttle-service-manage-dropdown").css("display", "none");
	});
	$(".shuttle-service-manage-dropdown-toggle").hover(function(e){
		e.preventDefault();
		$(".customized-travel-manage-dropdown").css("display", "none");
		$(".shuttle-service-manage-dropdown").css("display", "block");
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
			$("#ct-submission-div span.name-span").css("display","inline");
		}else{
			$("#ct-submission-div span.name-span").css("display","none");
		}
		if(phone=='' && email=='' && wechat==''){
			ready = false;
			$("#ct-submission-div span.contact-span").css("display","inline");
		}else{
			$("#ct-submission-div span.contact-span").css("display","none");
		}
		if(message==''){
			ready = false;
			$("#ct-submission-div span.message-span").css("display","block");
		}else{
			$("#ct-submission-div span.message-span").css("display","none");
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
	});
	
	/**
	 * on shuttle-service main page, when click on the header of the destination
	 * selection div, open the drop down
	 */
	$("#ss-destination-div .ss-selection-header").click(function(e){
		e.preventDefault();
		var contentDiv = $(this).parent().find(".ss-selection-content");
		contentDiv.slideDown();
	});
	
	/**
	 * on shuttle-service main page, if a confirm button is clicked, the next div will open
	 */
	$("#ss-destination-div button").click(function(e){
		$("#ss-date-div").slideDown();
	});
	$("#ss-date-div button").click(function(e){
		$("#ss-time-div").slideDown();
		$("#ss-submit").css("display","block");
	});
	
	/**
	 * select a value in #ss-destination-div
	 */
	$("#ss-destination-div li").click(function(e){
		$(this).parent().find("li").removeClass("selected-li")
								 .addClass("unselected-li");
		$(this).addClass("selected-li")
			   .removeClass("unselected-li");
		
	});
	
	/**
	 * pick a data in #ss-date-div
	 */
	$("#ss-date-div .datepicker").datepicker({minDate:0});
	$("#ss-date-div .datepicker").mousedown(function() {
	    $('#ui-datepicker-div').toggle();
	});
	
	/**
	 * submit shuttle service form to get itineraries
	 */
	$("#ss-submit").click(function(e){
		//check if all fields are filled
		var allset = true;
		if($("#ss-destination-div .selected-li").length > 0){
			var destination = $("#ss-destination-div .selected-li").data("destinationid");
			$("#ss-destination-error").css("display", "none");
		}else{
			allset = false;
			$("#ss-destination-error").css("display", "block");
		}
		if($("#ss-date-div .datepicker").val() != ""){
			var date = $("#ss-date-div .datepicker").val();
			$("#ss-date-error").css("display", "none");
		}else{
			allset = false;
			$("#ss-date-error").css("display", "block");
		}
		if($("#ss-hour-select").val()!=null){
			var hour = $("#ss-hour-select").val();
			$("#ss-time-error").css("display", "none");
		}else{
			allset = false;
			$("#ss-time-error").css("display", "block");
		}
		if(allset == true){ //submit to fetch qualified itineraries
			//prepare date value
			var month = date.slice(0,2);
			var day = date.slice(3,5);
			var year = date.slice(6);
			var datestring = year.concat(month, day);
			$.ajax({
				url:"/shuttleservice/listitinerary",
				dataType:"json",
				type:"post",
				data:{DestinationId:destination,
					Date:datestring,
					Hour:hour},
				success: function(response) {
					if(response.success){
						$("#ss-itinerary-div .content-loading").css("display", "none");
						$("#ss-itinerary-div .content-loaded").css("display", "block");
						$("#ss-itinerary-div .ss-selection-content").html("<ul class='selection-ul'></ul>");
						$.each(response.result, function(i,val){
							if(val.Hour < 12){
								var ampm = 'am';
							}else{
								var ampm = 'pm';
							}
							var itinerary_li = $("<li class='unselected-li' data-itineraryid="+val.Id+">"+val.Hour+": "+val.Minute+" "+ampm+", "+val.Vehicle+"</li>");
							itinerary_li.click(function(e){
								$(this).parent().find("li").removeClass("selected-li")
														 .addClass("unselected-li");
								$(this).addClass("selected-li")
									   .removeClass("unselected-li");	
							});
							$("#ss-itinerary-div ul").append(itinerary_li);
						});
					}else{
						$("#ss-itinerary-div .content-loading").css("display", "block");
						$("#ss-itinerary-div .content-loaded").css("display", "none");
						$("#ss-itinerary-div .content-loading").html("<span>对不起，没有符合您要求的车次。请填写表格，我们的客服将会为您订制行程。</span>");	
						$("#ss-submission-div").slideDown();
					}
				},
				beforeSend: function() {
					$("#ss-itinerary-div").slideDown();	
					$("#ss-itinerary-div .content-loaded").css("display", "none");
					$("#ss-itinerary-div .content-loading").css("display", "block");
				},
				error: function(xhr, status, error) {
					console.log(xhr.responseText);
				}
			});
		}
		
	});
	
	/**
	 * on ss_main page, user confirms a itinerary
	 */
	$("#ss-itinerary-div .ss-selection-button").click(function(e){
		if($("#ss-itinerary-div .selected-li").length > 0){
			$("#ss-submission-div").slideDown();
			$("#ss-itinerary-error").css("display", "none");
			$("#ss-submission-div .ss-selection-header").html("请填写以下表格，以便我们和您联系（*为必填项）");
			$("#ss-message-label").find("label").html("留言：");
		}else{
			$("#ss-itinerary-error").css("display", "block");
		}
	});

	/**
	 * on ss_main page, use clicks "no-itinerary-available-link"
	 */
	$("#no-itinerary-available-link").click(function(e){
		$("#ss-submission-div").slideDown();
		$("#ss-itinerary-error").css("display", "none");
		$("#ss-submission-div .ss-selection-header").html("请填写以下表格，我们将为您定制行程（*为必填项）");
		$("#ss-message-label").find("label").html("留言：&nbsp;*");
	});
	
	/**
	 * user submit form in ss-main page
	 */
	$("#ss-submission-form-submit").click(function(e){
		e.preventDefault();
		$("#ss-submission-error-message ul").empty();
		var firstname = $("#ss-first-name input").val();
		var lastname = $("#ss-last-name input").val();
		var phone = $("#ss-phone input").val();
		var email = $("#ss-email input").val();
		var wechat = $("#ss-wechat input").val();
		var message = $("#ss-message-textarea textarea").val();
		var itineraryId = null;
		var ready = true;
		if(firstname=='' || lastname==''){
			ready = false;
			$("#ss-submission-div span.name-span").css("display","inline");
		}else{
			$("#ss-submission-div span.name-span").css("display","none");
		}
		if(phone=='' && email=='' && wechat==''){
			ready = false;
			$("#ss-submission-div span.contact-span").css("display","inline");
		}else{
			$("#ss-submission-div span.contact-span").css("display","none");
		}
		if(message==''){
			if($("#ss-itinerary-div .selected-li").length==0){
				ready = false;
				$("#ss-submission-div span.message-span").css("display","block");
			}else{
				$("#ss-submission-div span.message-span").css("display","none");
				itineraryId = $("#ss-itinerary-div .selected-li").data("itineraryid");
			}			
		}else{
			$("#ss-submission-div span.message-span").css("display","none");
		}
		if(ready){
			console.log("ready to submit");
			var error_area = $("#ss-submission-error-message ul");
			$.ajax({
				url:"/shuttleservice/submit",
				dataType:"json",
				type:"post",
				data:{firstname:firstname,
					lastname:lastname,
					phone:phone,
					email:email,
					wechat:wechat,
					message:message,
					itineraryId:itineraryId},
				success: function(response) {
					$("#ss-submission-form-submit").prop("disabled",false);
					error_area.empty();
					if(response.success){
						$("#ss-submission-form")[0].reset();
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
					$("#ss-submission-form-submit").prop("disabled",true);
					error_area.append("请稍后，提交中……");
				},
				error: function(xhr, status, error) {
					console.log(xhr.responseText);
					$("#ss-submission-form-submit").prop("disabled",false);
					error_area.empty();
				}
			});
		}
	});
	
	/**
	 * admin login
	 */
	$("#loginSubmit").click(function(e){
		var username = $("#login-username").val();
		var password = $("#login-password").val();
		var rememberme = $("#login-rememberme").val();
		var ready = true;
		$("#loginDiv").find("div.error-message").css("display","none")
		if(username==''){
			ready = false;
			$("#login-username").parent(".login-form-item").find(".error-message").css("display","inline");
		}else{
			$("#login-username").parent(".login-form-item").find(".error-message").css("display","none");
		}
		if(password==''){
			ready = false;
			$("#login-password").parent(".login-form-item").find(".error-message").css("display","inline");
		}else{
			$("#login-password").parent(".login-form-item").find(".error-message").css("display","none");
		}
		if(ready){
			$.ajax({
				url:"/administration/authenticate",
				dataType:"json",
				type:"post",
				data:{username:username,
					password:password,
					rememberme:rememberme},
				success: function(response) {
					if(response.success){
						window.location.href = "http://happyjourney.local/administration";
					}else{
						$("#loginDiv").find("div.error-message").css("display","inline")
																.html(response.message);
					}
				},
				beforeSend: function() {
					
				},
				error: function(xhr, status, error) {
					$("#loginDiv").find("div.error-message").css("display","inline")
					.html("Error!");
					console.log(xhr.responseText);
				}
			});
		}
		
	});
	
	/**
	 * admin: submit description edit
	 */
	$("button.text-manage-submit").click(function(e){
		var type = $(this).data("type");
		var div = $("#edit-"+type+"-text");
		var text = div.find("textarea").val();
		var ready = true;
		div.find("span.error-message").css("display","none")
		if(text==''){
			ready = false;
			div.find("span.error-message").css("display","inline");
		}else{
			div.find("span.error-message").css("display","none");
		}
		if(ready){
			$.ajax({
				url:"/administration/updatetext",
				dataType:"json",
				type:"post",
				data:{type:type,
					text:text},
				success: function(response) {
					if(response.success){
						div.find("span.error-message").css("display","inline").html("已更新成功！");
					}else{
						div.find("span.error-message").css("display","inline").html(response.message);
					}
				},
				beforeSend: function() {
					
				},
				error: function(xhr, status, error) {
					div.find("span.error-message").css("display","inline").html("Error!");
					console.log(xhr.responseText);
				}
			});
		}
		
	});
	
	/**
	 * admin: delete a ct message
	 */
	$("button.delete-message-btn").click(function(e){
		var messageId = $(this).data("id");
		$.ajax({
			url:"/administration/customizedtravel/deletemessage",
			dataType:"json",
			type:"post",
			data:{Id:messageId},
			success: function(response) {
				if(response.success){
					$("#message-div-"+messageId).remove();
				}else{
					console.log(response);
				}
			},
			beforeSend: function() {
				
			},
			error: function(xhr, status, error) {
				console.log(xhr.responseText);
			}
		});
	});
	
	/**
	 * admin: submit area edit
	 */
	$(".edit-area-submit-div button").click(function(e){
		var type = $(this).data("type");
		var Id = $(this).parent(".edit-area-submit-div").data("id");
		var div = $("#area-div-"+Id);
		if(type=="delete"){
			var post = {Id:Id, Deleted:1};
		}
		if(type=="edit"){			
			var description = div.find("textarea").val();
			var post = {Id:Id, Description:description};
		}
		$("span.error-message").css("display","none")
		$.ajax({
			url:"/administration/customizedtravel/editarea",
			dataType:"json",
			type:"post",
			data:post,
			success: function(response) {
				if(response.success){
					if(type=="edit"){
						div.find("span.error-message").css("display","inline").html("已更新成功！");
					}
					if(type=="delete"){
						div.remove();
					}
				}else{
					div.find("span.error-message").css("display","inline").html(response.message);
				}
			},
			beforeSend: function() {
				
			},
			error: function(xhr, status, error) {
				div.find("span.error-message").css("display","inline").html("Error!");
				console.log(xhr.responseText);
			}
		});
		
	});
	
	/**
	 * admin: add a new area
	 */
	$(".add-area-submit-div button").click(function(e){
		var name = $(".area-detail-div").find("input").val();
		var description = $(".area-detail-div").find("textarea").val();
		var ready = true;
		$("span.error-message").css("display","none").html("");
		if(name=='' || description==''){
			ready = false;
			$("span.error-message").css("display","inline").html("请填写名称和介绍");
		}else{
			$("span.error-message").css("display","none").html("");
		}
		if(ready){
			$.ajax({
				url:"/administration/customizedtravel/addarea",
				dataType:"json",
				type:"post",
				data:{Name:name,
					Description:description},
				success: function(response) {
					if(response.success){
						$("span.error-message").css("display","inline").html("已更新成功！");
					}else{
						$("span.error-message").css("display","inline").html(response.message);
					}
				},
				beforeSend: function() {
					
				},
				error: function(xhr, status, error) {
					$("span.error-message").css("display","inline").html("Error!");
					console.log(xhr.responseText);
				}
			});
		}
		
	});
	
	/**
	 * admin: delete a ss message and close the linked CustomerItinerary
	 */
	$(".itinerary-status-div button.delete-itinerary-btn").click(function(e){
		var messageId = $(this).data("id");
		var customerItineraryId = $(this).data("customeritineraryid");
		$.ajax({
			url:"/administration/shuttleservice/closecustomeritinerary",
			dataType:"json",
			type:"post",
			data:{Id:messageId,
				CustomerItineraryId: customerItineraryId},
			success: function(response) {
				if(response.success){
					$("#itinerary-div-"+messageId).find(".itinerary-status-div").html('<div class="display-message-div"><span class="information-message">This request is processed.</span></div>');
				}else{
					console.log(response);
				}
			},
			beforeSend: function() {
				
			},
			error: function(xhr, status, error) {
				console.log(xhr.responseText);
			}
		});
	});
	
	/**
	 * admin: delete an itinerary
	 */
	$("#list-ss-itinerary-div button.delete-itinerary-btn").click(function(e){
		var Id = $(this).data("id");
		$.ajax({
			url:"/administration/shuttleservice/edititinerary",
			dataType:"json",
			type:"post",
			data:{Id:Id},
			success: function(response) {
				if(response.success){
					$("#itinerary-div-"+Id).remove();
				}else{
					console.log(response);
				}
			},
			beforeSend: function() {
				
			},
			error: function(xhr, status, error) {
				console.log(xhr.responseText);
			}
		});
	});
	
	/**
	 * select a value in #admin-ss-destination-div
	 */
	$("#admin-ss-destination-div li").click(function(e){
		$(this).parent().find("li").removeClass("selected-li")
								 .addClass("unselected-li");
		$(this).addClass("selected-li")
		   .removeClass("unselected-li");
	});
	
	/**
	 * when tries to add a new destination, unselect other destinations
	 */
	$("#new-destination-name").click(function(e){
		$("#admin-ss-destination-div li").removeClass("selected-li")
								 .addClass("unselected-li");
	});
	
	/**
	 * pick a date in #admin-ss-date-div
	 */
	$("#admin-ss-date-div .datepicker").datepicker({minDate:0});
	$("#adminss-date-div .datepicker").mousedown(function() {
	    $('#ui-datepicker-div').toggle();
	});
	
	/**
	 * submit shuttle service form to get itineraries
	 */
	$("#admin-ss-submit").click(function(e){
		//check if all fields are filled
		var allset = true;
		$("div.additinerary-error-message").find(".error-message").css("display", "none").html(" ");
		if($("#admin-ss-destination-div .selected-li").length > 0){
			var destination = $("#admin-ss-destination-div .selected-li").data("destinationid");
			$("#admin-ss-destination-error").css("display", "none");
		}else{
			if($("#new-destination-name").val().length>0){
				var name = $("#new-destination-name").val();
				var description = $("#new-destination-description").val();
			}else{
				allset = false;
				$("#admin-ss-destination-error").css("display", "block");
			}			
		}
		if($("#admin-ss-date-div .datepicker").val() != ""){
			var date = $("#admin-ss-date-div .datepicker").val();
			$("#admin-ss-date-error").css("display", "none");
		}else{
			allset = false;
			$("#admin-ss-date-error").css("display", "block");
		}
		if($("#admin-ss-hour-select").val()!=null && $("#admin-ss-minute-select").val()!=null){
			var hour = $("#admin-ss-hour-select").val();
			var minute = $("#admin-ss-minute-select").val();
			$("#admin-ss-time-error").css("display", "none");
		}else{
			allset = false;
			$("#admin-ss-time-error").css("display", "block");
		}
		if($("#admin-ss-vehicle-div input").val() != ""){
			var vehicle = $("#admin-ss-vehicle-div input").val();
			$("#admin-ss-vehicle-error").css("display", "none");
		}else{
			allset = false;
			$("#admin-ss-vehicle-error").css("display", "block");
		}
		if(allset == true){ //submit to fetch qualified itineraries
			//prepare date value
			var month = date.slice(0,2);
			var day = date.slice(3,5);
			var year = date.slice(6);
			var datestring = year.concat(month, day);
			$.ajax({
				url:"/administration/shuttleservice/additinerary",
				dataType:"json",
				type:"post",
				data:{DestinationId:destination,
					Name:name,
					Description:description,
					Date:datestring,
					Hour:hour,
					Minute:minute,
					Vehicle:vehicle},
				success: function(response) {
					if(response.success){
						console.log("1");
						$("div.additinerary-error-message").find(".error-message").css("display", "block").html("添加成功！");
					}else{
						$("div.additinerary-error-message").find(".error-message").css("display", "block").html("Error: "+response.message);
					}
				},
				beforeSend: function() {
					
				},
				error: function(xhr, status, error) {
					$(".additinerary-error-message span").html("Error");
					console.log(xhr.responseText);
				}
			});
		}
		
	});
	
});

/**
 * close customized-travel dropdown
 */
function foldAreaList()
{
	$(".customized-travel-dropdown").css("display", "none");
	$(".customized-travel-manage-dropdown").css("display", "none");
	$(".shuttle-service-manage-dropdown").css("display", "none");
}
