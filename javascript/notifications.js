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