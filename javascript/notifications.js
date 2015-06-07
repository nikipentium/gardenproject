window.onload = initPage;
function initPage(){
	get_num_notes();
}
function friendReqHandler(action, reqid, user1, elem) {
	var conf = confirm("Press OK to '" + action + "' this friend request.");
	if (conf != true) {
		return false;
	}
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if (ajaxReturn(ajax) == true) {
			if (ajax.responseText == "accept_ok") {
				_(elem).innerHTML = "<b>Request Accepted!</b><br />Your are now friends";
			} else if (ajax.responseText == "reject_ok") {
				_(elem).innerHTML = "<b>Request Rejected</b><br />You chose to reject friendship with this user";
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	};
	ajax.send("action=" + action + "&reqid=" + reqid + "&user1=" + user1);
}
function get_num_notes(){
	//gets the did_read count from notifications table when page loads in num_notes_container using AJAX
	var num_notes = document.getElementById('num_notes');
	var ajax = ajaxObj("POST","ajax/get_note_count.php");
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax) == true){
			var response = ajax.responseText.trim();
			//alert(response);
			num_notes.innerHTML = "You have <strong style = 'color: red'>"+response+"</strong> un-read notifications";
		}
	};
	ajax.send(null);
}
function update_num_notes(id){
	//updates the did_read count from notifications table when user clicks button using AJAX
	alert(id);
	var ajax = ajaxObj("POST","ajax/update_note_count.php");
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax) == true){
			get_num_notes();			
			window.location = "blogs.php?pid="+id;
		}
	};
	ajax.send("id="+id);
}
function display_notes(){
	//called when indian science button is clicked
	var num_notes_container = document.getElementById('num_notes_container');
	num_notes_container.style.display = "block";
	var notesBox = document.getElementById('notesBox');
	notesBox.innerHTML = "";
	notesBox.className = "col-md-12 myborder";
	var friendReqBox = document.getElementById('friendReqBox');
    friendReqBox.innerHTML = "";
    var html = "<h2>Blog Notifications</h2>";
    var ajax = ajaxObj("POST","ajax/get_notifications.php");
    ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax) == true){
			var response = JSON.parse(ajax.responseText.trim());
			alert(response);
			for(var i=0;i<response.length;i++){
				html += response[i] + "<br/><hr/>";
			}
		}
		notesBox.innerHTML = html;
	};
	ajax.send(null);
}
