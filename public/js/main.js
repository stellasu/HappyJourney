
/*
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
		},
		beforeSend: function() {
			console.log("fetching areas ...");
		},
		error: function(xhr, status, error) {
			console.log(xhr.responseText);
		}
	});
}
