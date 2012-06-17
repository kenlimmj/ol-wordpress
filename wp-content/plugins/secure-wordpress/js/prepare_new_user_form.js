/*$rev #1 07/16/2011 {c}*/
var recaptchaPublic = "6Ld1jcASAAAAAAyGvwtF6ujAd0yf3jFlj220qcrZ";
var waiting = 0;
function ifLoaded(){
	waiting++;
	if(waiting>=20){
		return false
	}
	if(	typeof(wsd_form_fields) != 'undefined' && 
			typeof(Recaptcha) != 'undefined' && 
			document.getElementById("sw_wsd_new_user_form") &&
			typeof(wsd_commonPasswords) != 'undefined')
		constructForm();
	else
		setTimeout(ifLoaded, 250);
}
function addInputElement(holder, name, type, label, description){
	var inputRow = document.createElement("TR");
	var cell = document.createElement("TH");
	cell.innerHTML = '<label for="' + name + '">' + label + ':</label>'
	cell.setAttribute("scope", "row");
	inputRow.appendChild(cell);
	var cell = document.createElement("TD");
	if(name=="account_website")
	  cell.innerHTML = '<input id="wsd_' + name + '" name="' + name + '" type="' + type + '" class="regular-text" value="' + wordpress_site_name + '"/>' +
										 (description?'<label for"' + name + '">' + description + '</label>':'');
	else
		cell.innerHTML = '<input id="wsd_' + name + '" name="' + name + '" type="' + type + '" class="regular-text"/>' +
										 (description?'<label for"' + name + '">' + description + '</label>':'');
	inputRow.appendChild(cell);
	holder.appendChild(inputRow);
}
function constructForm(){
	var inputHolder = document.getElementById("wsd_new_user_form_dynamic_inputs_table");
	if(!inputHolder)return false;
	for(var i=0; i<wsd_form_fields.length; i++){
		addInputElement(inputHolder, wsd_form_fields[i].name, wsd_form_fields[i].type, wsd_form_fields[i].label, wsd_form_fields[i].descr);
	}
	Recaptcha.create(recaptchaPublic, "wsd_new_user_form_captcha_div", {theme: "red"});
	formReady = true;
	if(img=document.getElementById("img_loading_animation"))img.style.display="none";
	if(div=document.getElementById("wsd_new_user_form_div"))div.style.visibility="visible";
}
setTimeout(ifLoaded, 250);