window.onload = initPage;

function initPage() {
	updateProductOptions();
	var productsMenu = document.getElementById('productsMenu'); 
	productsMenu.onblur=checkOther;
}

function updateProductOptions(){
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

function checkOther(){
	var productsMenu = document.getElementById('productsMenu');
	var product_name = document.getElementById('product_name'); 
	var category_box = document.getElementById('category_box'); 
	var sub_category_box = document.getElementById('sub_category_box'); 
	var image_box = document.getElementById('image_box'); 
	if(productsMenu.value=="other"){
		product_name.style.display = 'block';
		category_box.style.display = 'block';
		sub_category_box.style.display = 'block';	
		image_box.style.display = 'block';
	}
	else{
		product_name.style.display = 'none';
		category_box.style.display = 'none';
		sub_category_box.style.display = 'none';
		image_box.style.display = 'none';	
		//check if productsMenu value's id exists in product_owner then he does not have to add details..just update price and quantity
	}
}