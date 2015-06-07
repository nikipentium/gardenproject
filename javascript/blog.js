/*window init
 * in blog_comments.js
 * Add event handlers
 * onclick blogs menu button
 */

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
		ajax.send("blogTitle="+blogTitle+"&blogLink="+blogLink+"&blogBody="+blogBody+"&pid="+pid+"&blogTags="+jsonTags);
	}
}
function display_product_form(){
	//called on click add product button
	//create add product form
	//make a dropdown select box with all products in market
	//make a message box for expert to type message
	//submit button
	//on submit data gets stored in the blog_product table
	var product_form = document.getElementById('product_form');
	var html = "";
	html += '<div class="form-group" >';
	html += '<label class="control-label col-sm-12">Product Name:</label>';
	html += '<select class="form-control" name="productsMenu" id="productsMenu">';
    html += '<div class="form-group" >';
    html += '<label class="control-label col-sm-12">Message:</label>';  
	html += '<input  name="message" class="form-control" type="text" id="message" placeholder="Please mention the relation" size="255" />';                                              
	html += '</div>';           
	html +='<div class="form-group">';							                    
	html +='<input class="btn btn-default" type="submit" onclick="add_product()" name="button" id="button" value="Submit" /></div> ';								                                     
    product_form.innerHTML = html;    
    var productsMenu = document.getElementById('productsMenu'); 
	// get the product names from database using AJAX
	var ajax = ajaxObj("POST","ajax/updateProductOptions.php");//returns the product names in JSON
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax) == true){
			var response = ajax.responseText.trim();
			if(response != "no products"){
				var result = JSON.parse(response);	
				alert(result);
				for(i=0;i<result.length;i++) {
			    	productsMenu.options[productsMenu.options.length] = new Option(result[i], result[i]);
				}		
			}
		}
	};
	ajax.send(null);
}
function add_product(){
	//store input in database using ajax
	var productsMenu = document.getElementById('productsMenu');
	var option = productsMenu.options[productsMenu.selectedIndex].value;
	var message = document.getElementById('message').value;
	var product_form = document.getElementById('product_form');
	//alert(option+ " "+message+ " " +pageid);
	//send message,product name,blog_id using ajax to ajax/add_product_blog.php
	var ajax = ajaxObj("POST","ajax/add_product_blog.php");//returns the product names in JSON
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax) == true){
			var response = ajax.responseText.trim();
			alert(response);
		}
	};
	ajax.send("product_name="+option+"&message="+message+"&blog_id="+pageid);
}
function get_products(){
	//onload it will get the associated products from blog_product database
}
