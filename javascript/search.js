function search(){
	var searchquery = document.getElementById("searchquery").value;
	var searchqueryArray = searchquery.split(" ");
	var ajax = ajaxObj("POST","search_blogs.php");
	ajax.onreadystatechange = function(){
			if(ajaxReturn(ajax) == true){
			}
		};
		ajax.send("query1="+searchqueryArray[0]+"&query2="+searchqueryArray[1]);
	}
