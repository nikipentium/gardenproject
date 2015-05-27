function request_page(pn){
    var results_box = document.getElementById("results_box");
    var pagination_controls = document.getElementById("pagination_controls");
    results_box.innerHTML = "loading results ...";
    var ajax = ajaxObj("POST","php_parsers/blog_new_parse.php");
    ajax.open("POST", "php_parsers/pagination_parser.php", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
            var dataArray = ajax.responseText.split("||");
            var html_output = "";
            for(i = 0; i < dataArray.length - 1; i++){
                var itemArray = dataArray[i].split("|");
                html_output += "ID: "+itemArray[0]+" - Testimonial from <b>"+itemArray[1]+"</b><hr>";
            }
            results_box.innerHTML = html_output;
        }
    };
    ajax.send("rpp="+rpp+"&last="+last+"&pn="+pn);
    // Change the pagination controls
    var paginationCtrls = "";
    // Only if there is more than 1 page worth of results give the user pagination controls
    if(last != 1){
        if (pn > 1) {
            paginationCtrls += '<button onclick="request_page('+(pn-1)+')">&lt;</button>';
        }
        paginationCtrls += ' &nbsp; &nbsp; <b>Page '+pn+' of '+last+'</b> &nbsp; &nbsp; ';
        if (pn != last) {
            paginationCtrls += '<button onclick="request_page('+(pn+1)+')">&gt;</button>';
        }
    }
    pagination_controls.innerHTML = paginationCtrls;
}
