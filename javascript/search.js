function search(){
	
	var pageMiddle = document.getElementById("pageMiddle");
	pageMiddle.innerHTML="<div class='col-md-12'><div class='row'><div class='col-md-12'><div id='pagination_controls'></div></div></div><div class='row'><div class='col-md-12'><div id='results_box'></div></div></div></div>";
    var resultsbox = document.getElementById("results_box");
    var i=0;
    var html_output="";
	
	var searchquery = document.getElementById("searchquery").value;
	var searchOption = document.getElementById("searchOption").value;
	var searchqueryArray = searchquery.split(" ");
	var searchOptionJSON = {option : searchOption};
	var searchqueryJSON = {query : searchqueryArray};
	var option = JSON.stringify(searchOptionJSON);
	var query = JSON.stringify(searchqueryJSON);
	var ajax = ajaxObj("POST","search.php");

	ajax.onreadystatechange = function(){
			if(ajaxReturn(ajax) == true){
				var response = ajax.responseText.trim();
				alert(response);
				if(response == "no_results"){
					alert("no results found for your search query");
				}
				else{
					var searchResults = JSON.parse(response);	
					var total_rows = (Object.keys(searchResults).length);
					html_output += "There are "+total_rows+" search results <br/><hr/>";					
					//send results and search option to pagination.php using get
					//window.location = "search_pagination.php?searchOption="+searchOption;
					for(var obj in searchResults){	
					html_output += searchResults[obj].title+"<br/>";		
  					html_output +=" <a href='http://localhost/xampp/phptest/gardenproject/root/blogs.php?pid="+searchResults[obj].id+"'> click here to view blog </a><br/>The blog author is " +searchResults[obj].author+"<br/><hr/>";   
						//alert("Blog page link : "+results[obj].id+" blog author is " +results[obj].author + " blog is " +results[obj].blog.substring(0,5) +"...");
					}
					resultsbox.innerHTML=html_output;
				}
			}
		};
	//send array of queries and one option
	ajax.send("query="+query+"&option="+option);
}
/*function pagination(pn){
	var searchResults = search();
	var total_rows = (Object.keys(searchResults).length);
	var rpp = 2;
	var last = 	Math.ceil(total_rows/rpp);
    var pageMiddle = document.getElementById("pageMiddle");
    pageMiddle.innerHTML="<div class='row' id='pageMiddle'><div class='col-md-12'><div class='row'><div class='col-md-12'><div id='pagination_controls'></div></div></div><div class='row'><div class='col-md-12'><div id='results_box'></div></div></div></div></div>";
    var resultsbox = document.getElementById("results_box");
    var i=0;
    var html_output="";
   	for(i=pn*rpp;i<rpp+(pn*rpp);i++){
  		html_output +=" <a href='http://localhost/xampp/phptest/gardenproject/root/blogs.php?pid="+searchResults[i].id+"'> click here to view blog </a><br/>The blog author is " +searchResults[i].author+"<br/>";   
    }
    resultsbox.innerHTML=html_output;
    var paginationCtrls = "";
            // Only if there is more than 1 page worth of results give the user pagination controls
            if(last != 1){
                if (pn > 1) {
                    paginationCtrls += '<button onclick="request_page('+(pn-1)+')">&lt;back</button>';
                }
                paginationCtrls += ' &nbsp; &nbsp; <b>Page '+pn+' of '+last+'</b> &nbsp; &nbsp; ';
                if (pn != last) {
                    paginationCtrls += '<button onclick="request_page('+(pn+1)+')">&gt;front</button>';
                }
            }
    pagination_controls.innerHTML = paginationCtrls;
}
*/