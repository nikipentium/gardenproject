function forgotpass() {
	var email = document.getElementById("email").value;
	var status = document.getElementById("status");
	if (email == "") {
		status.innerHTML = "Type in your email address";
	} else {
		document.getElementById("forgotpassbtn").style.display = "none";
		status.innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "ajax/forgotpwd_emailcheck.php");
		ajax.onreadystatechange = function() {
			if (ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				var response = response.trim();
				alert(response);
				if (response == "success") {
					document.getElementById("forgotpassform").innerHTML = '<h3>Step 2. Check your email inbox in a few minutes</h3><p>You can close this window or tab if you like.</p>';
				} else if (response == "no_exist") {
					status.innerHTML = "Sorry that email address is not in our system";
				} else if (response == "email_send_failed") {
					status.innerHTML = "Mail function failed to execute";
				} else {
					status.innerHTML = "An unknown error occurred";
				}
			}
		};
		ajax.send("email=" + email);
	}
}