function emptyElement(x){
	document.getElementById(x).innerHTML = "";
}
function login(){
	var e = document.getElementById("email").value;
	var p = document.getElementById("password").value;
	if(e == "" || p == ""){
		document.getElementById("status").innerHTML = "Fill out all of the form data";
	} else {
		document.getElementById("loginbtn").style.display = "none";
		document.getElementById("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "ajax/login.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText.trim() == "login_failed"){
					document.getElementById("status").innerHTML = "Login unsuccessful, please try again.";
					document.getElementById("loginbtn").style.display = "block";
				} else if(ajax.responseText.trim() == "login_success"){
					window.location = "userlevel.php";
				}
				else
				{
					document.getElementById("status").innerHTML = "An unknown error has occurred.";
				}
	        }
        };
        ajax.send("e="+e+"&p="+p);
	}
}