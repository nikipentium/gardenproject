/*window init
 * Add event handlers
 * onkeyup restrict function for username,email,city and profession
 * onblur check if username is taken
 * onblur check if email is valid
 * onblur check password strength
 * onblur confirm password match passwords
 * check if male or female is selected
 * onclick signup button check if full form is filled
 * all fine then call AJAX
 * Ajax stores everything in database
 * sends email
 */
window.onload = initPage;

function initPage() {
  // get elements that require events
  var username = document.getElementById("username");
  var email = document.getElementById("email");
  var password = document.getElementById("password");
  var confirmpwd = document.getElementById("confirmPassword");
  var submit = document.getElementById("signupButton");
	
  // set the handler for each element
  username.onblur = usernameCheck;//AJAX
  email.onblur = emailCheck;
  password.onblur =	passwordStrength;
  confirmpwd.onblur = passwordMatch;
  submit.onclick = signup;
      
}
function restrict(elem){
	var tf = document.getElementById(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "]/gi;
	} else if(elem == "username" || elem == "city" || elem == "profession"){
		rx = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
	document.getElementById(x).innerHTML = "";
}
function usernameCheck(){
	var username = document.getElementById("username");
	if(username.value != ""){
		var status=document.getElementById("unamestatus");
		status.innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "ajax/usernamecheck.php");
        ajax.onreadystatechange = function(){
	        if(ajaxReturn(ajax) == true) {
	        	//alert(ajax.responseText);
	            if(ajax.responseText.trim() == "wrongSize"){
	            	status.innerHTML="the username should be 3 to 16 characters long";
	            	username.innerHTML = "";
	            	username.value="";
	            }
	            else if(ajax.responseText.trim() == "invalid"){
	            	status.innerHTML="username cannot begin with a number";
	            	username.innerHTML = "";
	            	username.value="";
	            }
	            else if(ajax.responseText.trim() == "true"){
	            	status.innerHTML=username.value+" is ok";
	            }
	            else{
	            	status.innerHTML=username.value+" is taken";
	            	username.innerHTML = "";
	            	username.value="";
	            }
	        }
        };
        ajax.send("usernamecheck="+username.value);
	}
}

function emailCheck() {
	var email = document.getElementById("email");
	if (email.value != "") {
		var status = document.getElementById("emailstatus");
		//regular expression that validates email
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		if (re.test(email.value)) { // if it satisfies regular expression then check if its already in database
			status.innerHTML = 'checking ...';
			var ajax = ajaxObj("POST", "ajax/emailcheck.php");
			ajax.onreadystatechange = function() {
				if (ajaxReturn(ajax) == true) {
					//alert(ajax.responseText);
					if (ajax.responseText.trim() == "true") {
						status.innerHTML = email.value + " is ok";
					} else {
						status.innerHTML = email.value + " is taken";
						email.innerHTML = "";
						email.value = "";
					}
				}
			};
			ajax.send("emailcheck=" + email.value);
		} else {
			status.innerHTML = 'invalid';
		}
	}
}

function passwordStrength()
{
	var password = document.getElementById("password");
	if (password.value != "") {
		var status = document.getElementById("passwordstatus");
		if (password.value.length < 8) {
			status.innerHTML = 'password has to be more than 8 characters';
			password.innerHTML = "";
	       	password.value="";
		} else {
			status.innerHTML = 'password OK';
		}
	}
}
function passwordMatch()
{
	var password = document.getElementById("password");
	var confirmpwd = document.getElementById("confirmPassword");
	var status = document.getElementById("matchstatus");
	if (confirmpwd.value != "") {
		if (password.value == confirmpwd.value) {
			status.innerHTML = 'passwords match';
		} else {
			status.innerHTML = 'passwords do not match';
			password.innerHTML = "";
	        password.value="";
	        confirmpwd.innerHTML = "";
	        confirmpwd.value="";
		}
	}
}
function genderCheck(){//called by submit button
	var status = document.getElementById("genderstatus");
	var maleCheck = document.getElementById("male");
	var femaleCheck = document.getElementById("female");
	var result = '';
	if(maleCheck.checked == false && femaleCheck.checked == false){
		status.innerHtml = "please select one option";
	}
	else if(maleCheck.checked == true)
	{
		result = 'm';
	}
	else if(femaleCheck.checked == true){
		result = 'f';
	}
	return result;
}
function signup(){
	var submit = document.getElementById("signupButton");
	var status = document.getElementById("signupstatus");
	var signupForm = document.getElementById("signupForm");
	//gather entered information
	var username = document.getElementById("username").value;
 	var email = document.getElementById("email").value;
 	var password = document.getElementById("password").value;
 	var confirmpwd = document.getElementById("confirmPassword").value;
	var country = document.getElementById("country").value;
  	var state = document.getElementById("state").value;
 	var city = document.getElementById("city").value;
 	var profession= document.getElementById("profession").value;
  	var dob= document.getElementById("dob").value;
  	var gender = genderCheck();
  	alert(username+"\n"+email+"\n"+password+"\n"+country+"\n"+state+"\n"+city+"\n"+profession+"\n"+dob+"\n"+gender);
	//all fields should be filled , passwords match then process forms using ajax
	if(username == "" || email == "" || password == "" || confirmpwd == "" || country == "" || state == "" || city == "" || profession == "" || dob == "" || gender == ""){
		status.innerHTML = "Fill out all of the form data";
	}
	else {
		submit.style.display = "none";
		status.innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "ajax/signupSubmit.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	        	var response = ajax.responseText;
				var response = response.trim();
	            if(response.trim() == "success"){
	            	alert(response);
					status.innerHTML = response;
					signupForm.innerHTML = "OK "+username+", check your email inbox and junk mail box at <u>"+email+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
				} else {
					alert(response);
					window.scrollTo(0,0);
					submit.style.display = "block";
				}
	        }
        };
        ajax.send("username="+username+"&email="+email+"&password="+password+"&country="+country+"&state="+state+"&city="+city+"&gender="+gender+"&profession="+profession+"&age="+dob);
	}
}

