function compila_form(id){
	var email = document.getElementById("select").value;
	var value = document.getElementsByClassName(email);

	var ul = document.getElementsByClassName("ul")[0];
	var li = ul.getElementsByTagName("li");

	// Controllo elemento per elemento
	for(var i=0; i<li.length; ++i){
		var input = li[i].getElementsByTagName("input");
		
		//Scorro i valori e seleziono quelli necessari
		for(var j=0; j<value.length; ++j){
			// Se è il checkbox per il sesso
			if(input.length==2){
				for(var k=0; k<input.length; ++k)
					if(input[k].type=="radio" && input[k].value=="Maschio" && value[j].value=="M")
						input[k].checked=true;
					else if(input[k].type=="radio" && input[k].value=="Femmina" && value[j].value=="F")
						input[k].checked=true;

				continue;
			}else{
				if(input[0].name==value[j].name)
					if(input[0].type=="date" || input[0].type=="text")
						input[0].value=value[j].value;
			}
		}
	}
}

function cambia_form(){
	var e_user = document.getElementsByClassName("existing_user")[0];
	var n_user = document.getElementsByClassName("new_user")[0];

	if(n_user.style.display!="none"){
		e_user.style.display="unset";
		n_user.style.display="none";
	}else{
		n_user.style.display="unset";
		e_user.style.display="none";
	}

}
