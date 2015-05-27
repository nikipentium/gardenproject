function friendToggle(type,user,elem){
    var conf = confirm("Press OK to confirm the '"+type+"' action for user <?php echo $u; ?>.");
    if(conf != true){
        return false;
    }
    document.getElementById(elem).innerHTML = 'please wait ...';
    var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
        	alert(ajax.response.trim());
            if(ajax.responseText.trim() == "friend_request_sent"){
                document.getElementById(elem).innerHTML = 'OK Friend Request Sent';
            } else if(ajax.responseText.trim() == "unfriend_ok"){
                document.getElementById(elem).innerHTML = '<button onclick="friendToggle(\'friend\',\'<?php echo $u; ?>\',\'friendBtn\')">Request As Friend</button>';
            } else {
                document.getElementById(elem).innerHTML = 'Try again later';
            }
        }
    };
    ajax.send("type="+type+"&user="+user);
}
function subscribeToggle(type,user,elem){
    var conf = confirm("Press OK to confirm the '"+type+"' action for user <?php echo $u; ?>.");
    if(conf != true){
        return false;
    }
    document.getElementById(elem).innerHTML = 'please wait ...';
    var ajax = ajaxObj("POST", "php_parsers/subscribe_system.php");
    ajax.onreadystatechange = function() {
        if(ajaxReturn(ajax) == true) {
        	alert(ajax.response.trim());
            if(ajax.responseText.trim() == "subscribe_request_sent"){
                document.getElementById(elem).innerHTML = 'OK Subscribe Request Sent';
            } else if(ajax.responseText.trim() == "unsubscribe_ok"){
                document.getElementById(elem).innerHTML = '<button onclick="subscribeToggle(\'subscribe\',\'<?php echo $u; ?>\',\'subscribeBtn\')">subscribe</button>';
            } else {
                document.getElementById(elem).innerHTML = 'Try again later';
            }
        }
    };
    ajax.send("type="+type+"&user="+user);
}