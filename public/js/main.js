jQuery(document).ready(function($) {
	
	/**
	 * open customized-travel dropdown 
	 */
	$(".customized-travel-dropdown-toggle").hover(function(e){
		e.preventDefault();
		$(".customized-travel-dropdown").css("display", "block");
	});
	
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
