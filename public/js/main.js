
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
			console.log(serialize(response.results));
		},
		beforeSend: function() {
			console.log("fetching areas ...");
		},
		error: function() {
			console.log("error fetching areas");
		}
	});
}