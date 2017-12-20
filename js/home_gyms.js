function valuta(valutazione, ID_Palestra) {
	for(i=0;i<5;++i)
		document.getElementsByClassName('img'+i)[0].style.display="none";

	var item = document.getElementsByClassName('tmp')[0];
	var txt = document.createTextNode("La tua valutazione è "+valutazione);
	item.replaceChild(txt, item.childNodes[0]);

	var xhr= new XMLHttpRequest();
	var data= "val="+valutazione+"&pal="+ID_Palestra;
	xhr.open("GET", "php/action/valutazione-process.php?"+data, true);
	xhr.send();
}

function seleziona(valutazione) {
	for (i=0; i<5; i++){
		if (i<=valutazione)
			document.getElementsByClassName('img'+i)[0].src = "images/icon/icon_full.png";
		else
			document.getElementsByClassName('img'+i)[0].src = "images/icon/icon_clear.png";
	}
}
function restore(valutazione){
	for(i=4;i>=0;--i){
		if(i>=valutazione)
			document.getElementsByClassName('img'+i)[0].src="images/icon/icon_clear.png";
		else
			document.getElementsByClassName('img'+i)[0].src="images/icon/icon_full.png";
	}
}

function modifica_corso(corso){
	var t1 = document.getElementsByClassName(corso)[0];
	t1.style.display="none";
	var t2 = document.getElementsByClassName('tupla'+corso)[0];
	t2.style.display="inline-block";
}

function goback(corso){
	var t1 = document.getElementsByClassName(corso)[0];
	t1.style.display="block";
	var t2 = document.getElementsByClassName('tupla'+corso)[0];
	t2.style.display="none";
}
