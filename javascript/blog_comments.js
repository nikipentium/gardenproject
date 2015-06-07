window.onload = initPage;
function initPage(){
	//for blog.js included in blog.php script
	// get elements that require events
	var addBlogButton = document.getElementById("addBlogButton");
	// set the handler for each element
	addBlogButton.onclick = createBlogHTML;
	display_prev_comments();
}
function display_prev_comments(){
	//display previous comments
	var prev_comment_box = document.getElementById('prev_comments');
	var action = "load_comments";
	var ajax = ajaxObj("POST","php_parsers/blog_comment_system.php");
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax)==true){
			//alert(ajax.responseText);
			var response = JSON.parse(ajax.responseText);//osid,author,data,postdate
			//alert(response);	
			for(i=0;i<response.length;i++){					
					var comment = document.createElement("div");
					//reply stuff starts
					var reply_box = document.createElement("div");
					reply_box.id="reply_box"+response[i].osid;
					var reply_html = "<div id='reply_text_box"+response[i].osid+"'><textarea id='reply_text"+response[i].osid+"' class='form-control' onkeyup='statusMax(this,250)' placeholder='Hi "+viewer+", please type in your reply'></textarea></div>";
					reply_html += "<div><button class='btn btn-primary' onclick=post_reply('reply_post','b',"+response[i].osid+","+blog_id+",'"+viewer+"') id='reply_button"+response[i].osid+"' >Reply</button></div><hr />";
					reply_box.innerHTML = reply_html;
					//alert(response[i].replies[i].osid+" "+response[i].replies[i].author);
					//reply stuff ends
					var html = "";
					comment.id="comment_"+response[i].osid;
					html += "<p>comment by "+response[i].author+" on "+response[i].postdate+" </p>";
					html += "<p>"+response[i].data+"</p><hr/>";
					comment.innerHTML = html;
					comment.appendChild(reply_box);
					prev_comment_box.appendChild(comment);
					var reply_text_box = document.getElementById('reply_text_box'+response[i].osid);
					for(k=0;k<response[i].replies.length;k++){
						var rhtml="";
						var reply = document.createElement("div");					
						reply.id = "comment"+response[i].replies[k].osid+"reply"+k;
						rhtml += "<p>Reply by "+response[i].replies[k].author+" on "+response[i].replies[k].postdate+" </p>";
						rhtml += "<p>"+response[i].replies[k].data+"</p>";
						reply.innerHTML = rhtml;
						parentDiv = reply_text_box.parentNode;
						alert(parentDiv.id);	
						parentDiv.insertBefore(reply,reply_text_box);	
					}
			}			
		}
	};
	ajax.send("action="+action+"&blog_id="+blog_id);
}
function comments(owner,id,poster){
	var comment_box = document.getElementById('comment_box');
	blog_owner = owner;
	blog_id = id;
	viewer = poster; 	
	if(blog_owner == "no")
	{
		//display text area to get new comment (only from non owners)
		var comment_input_html = "<div><textarea id='comment_text' class='form-control' onkeyup='statusMax(this,250)' placeholder='Hi "+viewer+", do you have a query?'></textarea></div>";
		comment_input_html += "<div><button class='btn btn-primary' id='post_btn'>Post</button></div><hr />"; 
		comment_box.innerHTML = comment_input_html;                          
		var post_btn = document.getElementById('post_btn');
		var type = 'a';
		var action = 'comment_post';
		post_btn.onclick = function(){
			post_comment(action,type,blog_id,viewer);
		};
	}
}
function post_comment(action,type,blog_id,viewer){ // adds comment to blog_comments database
	var comment_text = document.getElementById('comment_text').value;
	var prev_comment_box = document.getElementById('prev_comments');
	//alert(comment_text + " "+type+ " "+blog_id+ " "+viewer+" "+action);
	var ajax = ajaxObj("POST","php_parsers/blog_comment_system.php");
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax)==true){
			//var response = ajax.responseText;
			var response = JSON.parse(ajax.responseText);//latest comment osid,author,data,postdate
			//alert(response);	
			//alert(response[0].osid);
			var comment = document.createElement("div");
			comment.id="comment_"+response[0].osid;
			var reply_box = document.createElement("div");
			reply_box.id="reply_box"+response[0].osid;
			var reply_html = "<div id='reply_text_box"+response[0].osid+"'><textarea id='reply_text"+response[0].osid+"' class='form-control' onkeyup='statusMax(this,250)' placeholder='Hi "+viewer+", please type in your reply'></textarea></div>";
			reply_html += "<div><button class='btn btn-primary' onclick=post_reply('reply_post','b',"+response[0].osid+","+blog_id+",'"+viewer+"') id='reply_button"+response[0].osid+"' >Reply</button></div><hr />";
			reply_box.innerHTML = reply_html;
			var html = "";
			html += "<p>comment by "+response[0].author+" on "+response[0].postdate+" </p>";
			html += "<p>"+response[0].data+"</p>";
			comment.innerHTML = html;
			comment.appendChild(reply_box);				
			prev_comment_box.insertBefore(comment,prev_comment_box.firstChild);			
		}
	};
	ajax.send("action="+action+"&type="+type+"&blog_id="+blog_id+"&viewer="+viewer+"&data="+comment_text);
}
function post_reply(action,type,osid,blog_id,viewer){ // adds reply to blog_comments database
	var reply_box = document.getElementById('reply_box'+osid);
	var reply_text = document.getElementById('reply_text'+osid).value;
	var reply_text_box = document.getElementById('reply_text_box'+osid);
	alert(reply_text + " "+type+ " "+osid+ " "+viewer+" "+action);
	var ajax = ajaxObj("POST","php_parsers/blog_comment_system.php");
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax)==true){
			var response = JSON.parse(ajax.responseText);//latest reply osid,author,data,postdate
			//var response = ajax.responseText;
			alert(response);
			var reply = document.createElement("div");
			alert(response[0].osid);
			reply.id="reply"+response[0].osid;
			var html = "";
			html += "<p>Reply by "+response[0].author+" on "+response[0].postdate+" </p>";
			html += "<p>"+response[0].data+"</p>";
			reply.innerHTML = html;
			parentDiv = reply_text_box.parentNode;
			alert(parentDiv.id);	
			parentDiv.insertBefore(reply,reply_text_box);			
		}
	};
	ajax.send("action="+action+"&type="+type+"&blog_id="+blog_id+"&osid="+osid+"&viewer="+viewer+"&data="+reply_text);
}
function statusMax(field, maxlimit) {
    if (field.value.length > maxlimit){
        alert(maxlimit+" maximum character limit reached");
        field.value = field.value.substring(0, maxlimit);
    }
}
