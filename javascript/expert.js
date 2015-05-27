/*window init
 * Add event handlers
 * onclick myblogs menu button
 */
window.onload = initPage;

function initPage() {
	// get elements that require events
	var viewBlogButton = document.getElementById("viewBlogButton");
	// set the handler for each element
	viewBlogButton.onclick = displayBlogs;
}

function displayBlogs() {
	//in the middle page div it should use pagination to display blogs
	window.location = "blogs.php";
}
