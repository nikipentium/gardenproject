/*window init
 * Add event handlers
 * onclick myblogs menu button
 */
window.onload = initPage;

function initPage() {
	// get elements that require events
	var addBlogButton = document.getElementById("addBlogButton");
	// set the handler for each element
	addBlogButton.onclick = createBlogHTML;
}

function createBlogHTML() {
	//should create a page title, link label, pagebody text area and create page button
	var page_middle = document.getElementById("blogBody");
	var comments = document.getElementById("commentSection");//hide that
	create_blog_html = "<div class='row'>";
	 create_blog_html += "<div class='col-md-12'>";
	 create_blog_html += "<form role='form' onsubmit='return false;' id='blogForm'>";
	 create_blog_html += "<div class='form-group'>";
	 create_blog_html += "<input type='text' class='form-control inline' id='blogTitle' placeholder='blog title'>";
	 create_blog_html += "</div>";
	 create_blog_html += "<div class='form-group'>";
	 create_blog_html += "<input type='text' class='form-control' id='blogLink' placeholder='blog link name'>";
	 create_blog_html += "</div>";
	 create_blog_html += "<div class='form-group'>";
	 create_blog_html += "<textarea class='form-control' rows='11' id='blogContent' placeholder='blog body'></textarea>";
	 create_blog_html += "</div>";
	 create_blog_html += "<div class='form-group'>";
	 create_blog_html += "<input type='text' class='form-control' id='blogTags' placeholder='blog tags - Eg. health apple banana'>";
	 create_blog_html += "</div>";
	 create_blog_html += "<button type='submit' id='submit' class='btn btn-default' onclick='createBlog()'>Publish Blog</button>&nbsp&nbsp<span id='status'></span>";
	 create_blog_html += "</form>";
	 create_blog_html += "</div>";
	 create_blog_html += "</div>";
	page_middle.innerHTML = create_blog_html;
	comments.innerHTML = "";
}
function createBlog(){
	//should launch ajax create blog.php in the middle page on create blog button it should send all data to php to store in database
	var createBtn = document.getElementById('submit');
	var status = document.getElementById('status');
	var blogForm =document.getElementById('blogForm');
	//data gathered from html form  
	var blogTitle = document.getElementById('blogTitle').value;
	var blogLink = document.getElementById('blogLink').value;
	var blogBody = document.getElementById('blogContent').value;
	var blogTags = document.getElementById('blogTags').value;
	//check if everything is filled
	if(blogTitle == "" || blogLink == "" || blogBody =="" || blogTags ==""){
		status.innerHTML = "Fill out all of the form data";	
	}
	else{
		//Break blog tags in to array //$jsonData = '{ "tags":["x","y","z"] }';
		blogTagArray = blogTags.split(" ");
		var obj = {tags:blogTagArray};
		jsonTags = JSON.stringify(obj);
		alert(jsonTags);
		//AJAX part
		submit.style.display ="none";
		status.innerHTML ="please wait...";
		var ajax = ajaxObj("POST","php_parsers/blog_new_parse.php");
		ajax.onreadystatechange = function(){
			if(ajaxReturn(ajax) == true){
				var response = ajax.responseText;
				var response = response.trim();
				alert(response);
				var responseArray = response.split("|");
				if(responseArray[1] == "success"){
					alert(responseArray[1]);
					blogForm.innerHTML = "View your blog<br/><a href='http://localhost/xampp/phptest/gardenproject/root/blogs.php?pid="+responseArray[0]+"'>Click here</a>";
					window.scrollTo(0,0);
				}
				else
				{
					status.innerHTML = response;
					window.scrollTo(0,0);
					submit.style.display = "block";
				}
			}
		};
		ajax.send("blogTitle="+blogTitle+"&blogLink="+blogLink+"&blogBody="+blogBody+"&blogTag="+jsonTags);
	}
}
function editBlogHTML() {
	//should create a page title, link label, pagebody text area and create page button
	var page_middle = document.getElementById("blogBody");
	var comments = document.getElementById("commentSection");//hide that
	create_blog_html = "<div class='row'>";
	 create_blog_html += "<div class='col-md-12'>";
	 create_blog_html += "<form role='form' onsubmit='return false;' id='blogForm'>";
	 create_blog_html += "<div class='form-group'>";
	 create_blog_html += "<input type='text' class='form-control inline' id='blogTitle' placeholder='blog title'>";
	 create_blog_html += "</div>";
	 create_blog_html += "<div class='form-group'>";
	 create_blog_html += "<input type='text' class='form-control' id='blogLink' placeholder='blog link name'>";
	 create_blog_html += "</div>";
	 create_blog_html += "<div class='form-group'>";
	 create_blog_html += "<textarea class='form-control' rows='11' id='blogContent' placeholder='blog body'></textarea>";
	 create_blog_html += "</div>";
	 create_blog_html += "<div class='form-group'>";
	 create_blog_html += "<input type='text' class='form-control' id='blogTags' placeholder='blog tags - Eg. health apple banana'>";
	 create_blog_html += "</div>";
	 create_blog_html += "<button type='submit' id='submit' class='btn btn-default'>Publish Blog</button>&nbsp&nbsp<span id='status'></span>";
	 create_blog_html += "</form>";
	 create_blog_html += "</div>";
	 create_blog_html += "</div>";
	page_middle.innerHTML = create_blog_html;
	comments.innerHTML = "";
}
function getdata(pid){
	editBlogHTML();
	var editBtn = document.getElementById('submit');
	editBtn.onclick=function(){editBlog(pid);};
	var blogTitle = document.getElementById('blogTitle');
	var blogLink = document.getElementById('blogLink');
	var blogBody = document.getElementById('blogContent');
	var blogTags = document.getElementById('blogTags');
	var ajax = ajaxObj("POST","php_parsers/get_blog_data.php");
		ajax.onreadystatechange = function(){
			if(ajaxReturn(ajax) == true){
				var response = ajax.responseText;
				var response = response.trim();
				if(response.trim() == "0 results"){
					status.innerHTML = response;
				}
				else
				{
					var array = response.split("|");
					blogTitle.value = array[0];
					blogLink.value = array[1];
					blogBody.value = array[2];
					var tags ="";
					for(i=3;i<array.length;i++)
					{
						if(i!=array.length-1){
							tags += array[i]+" ";
						}
						else{
							tags += array[i];
						}
					}
					blogTags.value = tags;
				}
			}
		};
		ajax.send("pid="+pid);
}
function editBlog(pid)
{
	var createBtn = document.getElementById('submit');
	var status = document.getElementById('status');
	var blogForm =document.getElementById('blogForm');
	var blogTitle = document.getElementById('blogTitle').value;
	var blogLink = document.getElementById('blogLink').value;
	var blogBody = document.getElementById('blogContent').value;
	var blogTags = document.getElementById('blogTags').value;
	//check if everything is filled
	if(blogTitle == "" || blogLink == "" || blogBody =="" || blogTags ==""){
		status.innerHTML = "Fill out all of the form data";	
	}
	else
	{
		blogTagArray = blogTags.split(" ");
		var obj = {tags:blogTagArray};
		jsonTags = JSON.stringify(obj);
		submit.style.display ="none";
		status.innerHTML ="please wait...";
		var ajax = ajaxObj("POST","php_parsers/blog_edit_parse.php");
		ajax.onreadystatechange = function(){
			if(ajaxReturn(ajax) == true){
				var response = ajax.responseText;
				var response = response.trim();
				if(response.trim() == "success"){
					alert(response);
					status.innerHTML = response;
				}
				else
				{
					status.innerHTML = response;
					window.scrollTo(0,0);
					submit.style.display = "block";
				}
			}
		};
		ajax.send("blogTitle="+blogTitle+"&blogLink="+blogLink+"&blogBody="+blogBody+"&pid="+pid+"&blogTags="+jsonTags);
	}
}