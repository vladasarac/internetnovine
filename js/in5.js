﻿var Internetnovine5 = function(){ 

  // var sirina  = $( window ).width();
  // alert(sirina);
  
  //-------------------------------------------------------------------------------------------------------------------------
  // jquery za "desnidiv"
  //-------------------------------------------------------------------------------------------------------------------------
  var dpi_x = document.getElementById('dpi').offsetWidth;
  var dpi_y = document.getElementById('dpi').offsetHeight;
  //  MERI CEO EKRAN
  // var widthekrana = screen.width / dpi_x;
  // var heightekrana = screen.height / dpi_y;
  //  MERI BROWSER
  var widthekrana = $(window).width() / dpi_x;
  var heightekrana = $(window).height() / dpi_y;
  //alert('Sirina inch: '+widthekrana+', visina inch: '+heightekrana);
  
  
  //if(widthekrana > 10.5){
  if(widthekrana < 10.5 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
     // MALI EKRAN !!!! Ako je ekran manji od 10 incha(ili priblizno) prikazace se dugmad na ciji klik se prikazuje,div sa formom 
	 // za pretragu, div sa 5 najkomentarisanijih tekstova, div sa listom sa temperaturama za 5 gradova u Srbiji(Bg,Ns,Nis,Kg i Kop) i kursnom listom
     //alert('mali ekran');
	$('.col-xs-8').removeClass('col-xs-8').addClass('col-xs-12');//promeni velicinu div-ova da zauzmu vise mesta posto nije potrebno mesto sa desne strane za div #desno 
	$('.col-xs-4').removeClass('col-xs-4').addClass('col-xs-6');
	$('.podnaslov').remove(); // ukloni podnaslove posto ce biti prikazivani samo naslovi na glavnoj str za sve tekstove osim glavnog kom se prikazuje podnaslov
	var naslovglavna = $('.tekstnaslovaglavna').text();
	//$('.tekstnaslovaglavna').replaceWith("<h3 class='tekstnaslovaglavna'>"+naslovglavna+"</h3>"); // naslov na glavnoj strani ce biti <h3>
	var content = '';// napravi dugmad koja idu iznad footera (posto je mali ekran)
	content += '<br><div id="pretragamaliekran">';//dugme na ciji klik iskace ispod njega forma za pretragu IN
	content += '<button id="pretragamaliekranbtn" type="button" class="btn btn-primary btn-lg btn-block">Pretraži IN</button>';
	content += '</div>';
	content += '<br><div id="listanajvisekomentara">';//dugme na ciji klik iskace ispod njega lista sa linkovima za 5 najkomentarisanijih tekstova
	content += '<button id="najvisekomentarabtn" type="button" class="btn btn-primary btn-lg btn-block">Najviše Komentara</button>';
	content += '</div>';
	content += '<br><div id="listaprognozakurs">';//dugme na ciji klik iskace ispod njega lista sa temperaturama za 5 gradova u Srbiji(Bg,Ns,Nis,Kg i Kop)
	content += '<button id="vremenskaprognozakurs" type="button" class="btn btn-primary btn-lg btn-block">Prognoza & Kurs</button>';
	content += '</div>';
	$(content).insertBefore('.footer'); // ubaci ga    
	
  }else{
    //  VELIKI EKRAN !!!! Ako je ekran sirine vece od 10 incha(ili priblizno tome) ubacuje se div "desno" koji je vidljiv u frontend view-ovima u kom se -
    // - prikazuje forma za pretragu sajta, 5 linkova za tekstove sa najvise komentara, zatim temperature za 5 gradova u Srbiji(Bg,Ns,Nis,Kg i Kop) i kursna lista
	var baseurl = $('#baseurl').text();
	var content = '';
	content += '<div id="desno">';// napravi div desno u kom se prikazuju linkovi za tekstovve sa najvise komentara,...
	content += '<div id="pretragadesno" class="input-append">'; // div u kom je forma tj input za retragu koji poziva metod pretraga() u kontroleru da uradi upit u bazi po unetom pojmu
	content += '<h4>Pretraži IN</h4>';
	content += '<form id="formazapretragu" method="post" action="'+baseurl+'internetnovine/pretraga">';
    content += '<input type="text" name="unoszapretragu" id="unoszapretragu"/>';
    content += '<button id="pretragadesnobtn" class="btn text-info glyphicon glyphicon-search"></button>';
	content += '</form>';
	content += '<div id="pretragaerror"></div>'; // div u koji se ubacuje error poruka ako mis udje na submitbtn a nista nije uneto u polje
	content += '<hr></div>'; // kraj div-a #pretragadesno
	content += '</div>'; // kraj div-a #desno
	$(content).appendTo('.navbar'); // ubaci ga 
	//
	//var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/najvisekomentara'; // napravi url za AJAX
	
	$.get(url, function(o){
	  console.log(o);
	  var tabelanajvisekomentara = ''; // varijabla u kojoj se crta div #tabelanajvisekomentara
	  tabelanajvisekomentara += '<h4>Najviše Komentara</h4>';
	  tabelanajvisekomentara += '<div id="tabelanajvisekomentara">';//napravi div koji se ubacuje u div #desno na velikim ekranima u kom ce biti tabela sa linkovima za 5 tekstova sa najvise komentara 
	  tabelanajvisekomentara += '<table class="table table-hover">'; // pocetak tabele
	  for(var i = 0; i < o['tekstovi'].length; i++){ //iteriraj kroz objekat vracen iz kontrolera -	
	    // preradi datum da bude citljiv
        var godina = o['tekstovi'][i].datum.substring(0, 4); // izvuci godinu
		var mesec = o['tekstovi'][i].datum.substring(5, 7); // izvuci mesec
		var dan = o['tekstovi'][i].datum.substring(8, 10); // izvuci dan
		var sati = o['tekstovi'][i].datum.substring(11, 19); // // izvuci sate minute i sekunde
		var datum = dan+'/'+mesec+'/'+godina+'  '+sati+' h'; // napravi novi datum 
	    // - i za svaki tekst vracen iz metoda internetnovine/najvisekomentara() napravi <tr> u kom je link sa naslovom teksta i brojem komentara koji poziva metodtekst() internetnovine.php kontrolera	    
        tabelanajvisekomentara += '<tr>';
	    tabelanajvisekomentara += '<td>'; //ubaci u linkov href atribut podatke koje zahteva metod tekst()
		tabelanajvisekomentara += '<a href="'+baseurl+'internetnovine/tekst/'+o['tekstovi'][i].id_teksta+'/'+o['tekstovi'][i].naslov+'/'+o['tekstovi'][i].googlemap+'/'+o['tekstovi'][i].id_autora+'">';
		tabelanajvisekomentara += o['tekstovi'][i].naslov+' (<i>'+o['tekstovi'][i].komentar+'</i>)';
		tabelanajvisekomentara += '<br><p class="malidatum"><i><strong>Datum:</strong> '+datum+'</i></p>';
		tabelanajvisekomentara += '</a>';
		tabelanajvisekomentara += '</td>';
	    tabelanajvisekomentara += '</tr>'    
	  }
	  tabelanajvisekomentara += '</table><hr>'; // kraj tabele
      tabelanajvisekomentara += '</div>'; //kraj diva #tabelanajvisekomentara
	  $(tabelanajvisekomentara).appendTo('#pretragadesno'); // ubaci napravljenu tabelu u div #desno
	}, 'json'); 
	
	//Prognoza i kursna lista, skida je metod prognozakurs() iz kontrolera skidajuci podatke sa RHMZ sajta i kurs sa NBS sajta isto metod prognozakurs()
	var prognozaurl = baseurl+'internetnovine/prognozakurs'; // napravi url za AJAX
	$.get(prognozaurl, function(o){
	  console.log(o);
	  //alert('Bg: '+o.bg+', Ns: '+o.ns+', Kop: '+o.kop+', Kg:'+o.kg+', Nis'+o.nis);
	  var listaprognoza = '<h4>Vreme Srbija</h4>';	  
	  listaprognoza += '<div id="listaprognozaVE">';  
	  listaprognoza += '<ul class="list-unstyled">'; // ubaci podatke za prognozu vracene iz kontrolera u listu i nakaci na div #tabelanajvisekomentara koji prikazuje tabelu sa najkomentarisanijim tekstovima
	  listaprognoza += '<li><h3 class="text-info">Beograd '+o.bg+' &#8451;</h3></li>';
	  listaprognoza += '<li><h3 class="text-info">Novi Sad '+o.ns+' &#8451;</h3></li>';
	  listaprognoza += '<li><h3 class="text-info">Niš '+o.nis+' &#8451;</h3></li>';
	  listaprognoza += '<li><h3 class="text-info">Kragujevac '+o.kg+' &#8451;</h3></li>';
	  listaprognoza += '<li><h3 class="text-info">Kopaonik '+o.kop+' &#8451;</h3></li>';
      listaprognoza += '</ul>';	  
	  listaprognoza += '</div>'; //kraj diva #listaprognozaVE
	  listaprognoza += '<hr><h4>Kursna Lista</h4>';
	  listaprognoza += '<div id="kursnalistaVE">';  
	  listaprognoza += '<ul class="list-unstyled">'; // ubaci podatke za valute
	  listaprognoza += '<li><h3 class="text-info">1EUR : '+o.eur+' RSD</h3></li>';
	  listaprognoza += '<li><h3 class="text-info">1USD : '+o.usd+' RSD</h3></li>';	  
	  listaprognoza += '<li><h3 class="text-info">1CHF : '+o.chf+' RSD</h3></li>';
	  listaprognoza += '<li><h3 class="text-info">1GBP : '+o.gbp+' RSD</h3></li>';
      listaprognoza += '</ul>';	  
	  listaprognoza += '</div>'; //kraj diva #kursnalistaVE
	  $(listaprognoza).appendTo('#desno');
	}, 'json'); 
	 
  }
  
  // HENDLERI ZA KLIK NA BUTTON-e NA MALOM EKRANU(pretraga, najkomentarisaniji i prognoza & kurs)
  //klik na dugme 'Pretraži IN' na malom ekranu koje izbacuje ispod sebe formu za pretragu
  $('body').on('click', '#pretragamaliekranbtn', function(e){
    //alert('pretraga');
	$(this).prop('disabled', true);//zabrani klik na dugme #najvisekomentarabtn
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/pretraga'; // napravi url za AJAX
	var formapretragame = ''; // nacrtaj formu za pretragu
    formapretragame += '<div id="pretragamaliekraninner" class="text-center">';
	formapretragame += '<h4 id="formapretragamenaslov">Pretraži IN</h4>';
    formapretragame += '<form id="formazapretragume" method="post" action="'+baseurl+'internetnovine/pretraga">';
    formapretragame += '<input type="text" name="unoszapretragu" id="unoszapretragu"/>';
    formapretragame += '<button id="pretragamesubmit" class="btn text-info glyphicon glyphicon-search"></button>';
	formapretragame += '</form>';
	formapretragame += '<div id="pretragaerrorme"></div>';
	formapretragame += '<hr><div class="text-center"><a href="#"  class="xpretragamaliekraninner lead text-info"><h4>Zatvori</h4></a></div>';
    formapretragame += '</div>';
	$(formapretragame).insertAfter('#pretragamaliekran'); // ubaci je ipod div-a koji prikazuje dugme #pretragamaliekranbtn
  });
  
  //submit forme za pretragu na malom ekranu tj validacija 
  $('body').on('click', '#pretragamesubmit', function(e){
    var unetozapretragu = $('#unoszapretragu').val(); // uzmi userov unos 
	  if(unetozapretragu == ""){ // ako je prazan
		$('#pretragaerrorme').html('<p class="text-danger">Morate uneti pojam za pretragu.</p>'); // izbaci error poruku u div koji je ispod inputa za unos pojma za pretragu 
		e.preventDefault(); // spreci submit forme
	  }else if(unetozapretragu != ""){ // aka je user uneo nesto
	    $('#pretragaerrorme').html(''); //isprazni errror div koji je ispod inputa za unos pojma za pretragu
	  }
  });
  
  // klik na link 'Zatvori' ispod forme za pretragu na malom ekranu(klik na dugme 'Pretraži IN' na malom ekranu)
  $('body').on('click', '.xpretragamaliekraninner', function(e){
    e.preventDefault();
	$('#pretragamaliekraninner').slideUp('slow', function(){ // slideUp-uj div #listanajvisekomentarainner -
	  $('#pretragamaliekraninner').remove(); // - ukloni ga 
	});
	$('#pretragamaliekranbtn').prop('disabled', false);//dozvoli klik na dugme #najvisekomentarabtn
  });
  
  
  // klik na button #najvisekomentara koji postoji samo na malom ekranu i treba da prikaze 5 linkova za tekstove sa najvise komentara
  $('body').on('click', '#najvisekomentarabtn', function(e){
    //alert('najvise komentara');
	$(this).prop('disabled', true);//zabrani klik na dugme #najvisekomentarabtn
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/najvisekomentara'; // napravi url za AJAX
	$.get(url, function(o){
	  console.log(o);
	  var tabelanajvisekomentara = ''; // varijabla u kojoj se crta div #tabelanajvisekomentara
	  tabelanajvisekomentara += '<div id="listanajvisekomentarainner">';
	  tabelanajvisekomentara += '<h4 id="listanajvisekomentaranaslov">Najviše Komentara</h4>';	  
	  tabelanajvisekomentara += '<dl>'; // dl je description list
	  for(var i = 0; i < o['tekstovi'].length; i++){ //iteriraj kroz objekat vracen iz kontrolera -	
	    // preradi datum da bude citljiv
        var godina = o['tekstovi'][i].datum.substring(0, 4); // izvuci godinu
		var mesec = o['tekstovi'][i].datum.substring(5, 7); // izvuci mesec
		var dan = o['tekstovi'][i].datum.substring(8, 10); // izvuci dan
		var sati = o['tekstovi'][i].datum.substring(11, 19); // // izvuci sate minute i sekunde
		var datum = dan+'/'+mesec+'/'+godina+'  '+sati+' h'; // napravi novi datum 
		tabelanajvisekomentara += '<a href="'+baseurl+'internetnovine/tekst/'+o['tekstovi'][i].id_teksta+'/'+o['tekstovi'][i].naslov+'/'+o['tekstovi'][i].googlemap+'/'+o['tekstovi'][i].id_autora+'">';
		tabelanajvisekomentara += '<dt>';
		tabelanajvisekomentara += '<h4>'+o['tekstovi'][i].naslov+' (<i>'+o['tekstovi'][i].komentar+'</i>)</h4>';
	    tabelanajvisekomentara += '</dt>';
		tabelanajvisekomentara += '<dd>';
		tabelanajvisekomentara += '<i><strong>Datum:</strong> '+datum+'</i>';
		tabelanajvisekomentara += '</dd><hr>';
		tabelanajvisekomentara += '</a>';		
	  }
	  tabelanajvisekomentara += '</dl>';
	  tabelanajvisekomentara += '<div class="text-center"><a href="#"  class="xlistanajvisekomentarainner lead text-info"><h4>Zatvori</h4></a></div>';
	  tabelanajvisekomentara += '</div>';
	  $(tabelanajvisekomentara).insertAfter('#listanajvisekomentara'); //ubaci div #listanajvisekomentarainner sa dl sa linovima za tekstove sa najvise komentara ispod diva u kom je dugme #najvisekomentarabtn
	}, 'json'); 
  });
  
  // klik na link 'Zatvori' ispod liste sa najkomentarisanijim tekstovima(klik na dugme 'Najviše Komentara' na malom ekranu)
  $('body').on('click', '.xlistanajvisekomentarainner', function(e){
    e.preventDefault();
	$('#listanajvisekomentarainner').slideUp('slow', function(){ // slideUp-uj div #listanajvisekomentarainner -
	  $('#listanajvisekomentarainner').remove(); // - ukloni ga 
	});
	$('#najvisekomentarabtn').prop('disabled', false);//dozvoli klik na dugme #najvisekomentarabtn
  });
  
  
  
  // klik na button #vremenskaprognozakurs koji postoji samo na malom ekranu i treba da prikaze listu sa temperaturama za 5 gradova u srbiji koja je skinuta u metodu prognoza() u kontroleru (podatci sa RHMZ sajta)
  // i listu sa kursnom listom sa NBS sajta
  $('body').on('click', '#vremenskaprognozakurs', function(e){
    $(this).prop('disabled', true);//zabrani klik na dugme #najvisekomentarabtn
    var baseurl = $('#baseurl').text();
    var prognozaurl = baseurl+'internetnovine/prognozakurs'; // napravi url za AJAX
	$.get(prognozaurl, function(o){
	  console.log(o);
	  //alert('Bg: '+o.bg+', Ns: '+o.ns+', Kop: '+o.kop+', Kg:'+o.kg+', Nis'+o.nis);
	  var listaprognoza = '<div id="listaprognozainner">';
	  listaprognoza += '<h4 id="listaprognozanaslov">Vreme Srbija</h4>';
	  listaprognoza += '<ul class="list-unstyled">'; // ubaci podatke vracene iz kontrolera u listu i nakaci na div #listaprognoza koji prikazuje dugme 'Vremenska Prognoza' na malom ekranu
	  listaprognoza += '<li><h3 class="text-info">Beograd '+o.bg+' &#8451;</h3></li><hr>';
	  listaprognoza += '<li><h3 class="text-info">Novi Sad '+o.ns+' &#8451;</h3></li><hr>';
	  listaprognoza += '<li><h3 class="text-info">Niš '+o.nis+' &#8451;</h3></li><hr>';
	  listaprognoza += '<li><h3 class="text-info">Kragujevac '+o.kg+' &#8451;</h3></li><hr>';
	  listaprognoza += '<li><h3 class="text-info">Kopaonik '+o.kop+' &#8451;</h3></li><hr>';
      listaprognoza += '</ul>';
	  listaprognoza += '<h4 id="listaprognozanaslov">Kursna Lista</h4>';
	  listaprognoza += '<ul class="list-unstyled">'; // ubaci podatke za kurs
	  listaprognoza += '<li><h3 class="text-info">1EUR : '+o.eur+' RSD</h3></li><hr>';
	  listaprognoza += '<li><h3 class="text-info">1USD : '+o.usd+' RSD</h3></li><hr>';
	  listaprognoza += '<li><h3 class="text-info">1CHF : '+o.chf+' RSD</h3></li><hr>';
	  listaprognoza += '<li><h3 class="text-info">1GBP : '+o.gbp+' RSD</h3></li><hr>';
      listaprognoza += '</ul>';
	  listaprognoza += '<div class="text-center"><a href="#"  class="xlistaprognozainner lead text-info"><h4>Zatvori</h4></a></div>';
	  listaprognoza += '</div>'; // kraj div-a #listaprognoza
	  $(listaprognoza).appendTo('#listaprognozakurs');
	}, 'json'); 
  });
  
  // klik na link 'Zatvori' ispod liste sa prognozom i kursnom listom na malom ekranu(klik na dugme 'Vremenska Prognoza' na malom ekranu)
  $('body').on('click', '.xlistaprognozainner', function(e){
    e.preventDefault();
	$('#listaprognozainner').slideUp('slow', function(){ // slideUp-uj div #listaprognozainner -
	  $('#listaprognozainner').remove(); // - ukloni ga 
	});
	$('#vremenskaprognozakurs').prop('disabled', false);//dozvoli klik na dugme #vremenskaprognoza
  });
  
  
  
  // VALIDACIJA UNOSA ZA PRETRAGU VELIKI EKRAN
  $('#formazapretragu').submit(function(e){   
    var unetozapretragu = $('#unoszapretragu').val(); // uzmi userov unos 
	  if(unetozapretragu == ""){ // ako je prazan
		$('#pretragaerror').html('<p class="text-danger">Morate uneti pojam za pretragu.</p>'); // izbaci error poruku u div koji je ispod inputa za unos pojma za pretragu 
		e.preventDefault(); // spreci submit forme
	  }else if(unetozapretragu != ""){ // aka je user uneo nesto
	    $('#pretragaerror').html(''); //isprazni errror div koji je ispod inputa za unos pojma za pretragu
	  }
  });
  
  // mozda nacin da se detektuje da korisnik koristi mobilni
  /* if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
    alert('mobilni');
  }else{
    alert('nije mobilni');
  } */
  
  
};  






























