jQuery(document).ready(function($) {
	
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
		}
	})
	
});


/**
 * fetch all areas from db, and apply to "customized travel service" drop down list
 */
function listAreas()
{
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
}

/**
 * close customized-travel dropdown
 */
function foldAreaList()
{
	$(".customized-travel-dropdown").css("display", "none");
}
