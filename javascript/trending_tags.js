//ajax the trending tags from ajax/trending_tags.php script
//display the tags
function trending_tags(){
	//ajax the trending tags
	var ajax = ajaxObj('POST',"ajax/trending_tags.php");
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax)==true){
			var response = JSON.parse(ajax.responseText.trim()); // response is an array of tag_id,tag_name and tag_count (for all users)
			alert(response.length);
			//get results box DOM and display in some cool fashion there
			var results_box = document.getElementById('blogBody');
			var html = "";
			results_box.innerHTML = html;
			var p = document.createElement("p");
			results_box.appendChild(p);
			var total_tag_count = 0;
			for(i=0;i<response.length;i++){
				total_tag_count += parseInt(response[i].tag_count);
			}
			alert(total_tag_count);
			for(i=0;i<response.length;i++){
				//alert(response[i].tag_name);
				var element = document.createElement("a");
				var text = document.createTextNode(response[i].tag_name);
				element.appendChild(text);
				element.style.font = "italic bold "+(300*(response[i].tag_count/total_tag_count))+"px arial,serif";
				p.appendChild(element);
			}
			results_box.style.wordWrap = "break-word";
			//take the highest count as benchmark size of font for the tag display
			//then all the sizes of other tags will be percentages of it
		}
	};
	ajax.send(null);
}
