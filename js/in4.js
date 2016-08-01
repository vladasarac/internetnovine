var Internetnovine4 = function(){



    
  //-------------------------------------------------------------------------------------------------------------------------
  // KOMENTARISANJE TEKSTA U tekst.php 
  //-------------------------------------------------------------------------------------------------------------------------
  
  // klik na btn #komentarisibtn koje je ispod teksta da bi se prikazala forma za komentarisanje
  $('#komentarisibtn').on('click', function(){
    //alert('komentar');
	$(this).addClass('hidden'); // sakrij button koji je kliknut
	$('#dodajkomentar').hide().removeClass('hidden').slideDown('slow'); // sakrij div koji prikazuje formu(iako ima kklasu hidden posto se radi slideDown), ukloni klasu hidden i slideDown-uj ga
  });  
  
  // cancel komentarisanja teksta
  $('#cancelxkomentara').on('click', function(e){
    e.preventDefault(); // posto je link
    $('#dodajkomentar').slideUp('slow', function(){ // slideUp-uj div sa formom za komentarisanje
	  $('#dodajkomentar').addClass('hidden'); // dodaj mu klasu hidden da bi opet radilo slideDown ako se opet klikne dugme za komentarisanje
	  $('#komentarisibtn').removeClass('hidden'); // dugmetu koje na klik prikazuje formu koje sada sakriveno skini klasu hidden da bi opet bilo vidljivo
	}); 
  });
  
  // funkcija za validaciju emaila
  function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
  }
  
  // validacija unosa u formu za komentarisanje texta kad mis udje na submit button ispod forme za komentarisanje
  $('body').on('mouseenter', '#dodajkomentardugme', function(e){
    var proverakomentatora;
    var br1 = parseInt($('#proverakomentatora').attr('br1')); // iz atributa inputa za unos broja za proveru uzmi br1 i br2(konvertuj ih u integer posto stizu kao stringovi)
	var br2 = parseInt($('#proverakomentatora').attr('br2'));
	var zbir = parseInt($('#proverakomentatora').val()); // uzmi userov unos
	if(zbir == br1 + br2){ // proveri da li je br1+br2 = userovom unosu
	   proverakomentatora = true; // ako jeste true
	}else{
	   proverakomentatora = false; // ako nije false
	}
    var emailkomentatora = $('#emailkomentatora').val();
	var proveraemailkomentatora = validateEmail(emailkomentatora); // zovi funkciju za proveru validnosti emaila sa regexp-om
	var imekomentatora = $('#imekomentatora').val();
	var tekstkomentara = $('#tekstkomentara').val();
	 // proveri duzine unetih stringova u polja i proveri da li je email validan tj da li funkcija validateEmail() vraca true i da li je zbir br1 i br2 = userovom unosu 
	if(emailkomentatora.length < 1 || imekomentatora.length < 1 || tekstkomentara.length < 1 || proveraemailkomentatora != true || proverakomentatora != true){
	  $('#erorikomentar').html('<p class="lead text-center text-danger">Pogrešno ste popunili formu, pokušajte ponovo.</p>');
	  $(this).bind('click', false); // ako ne prodje if disable-uj button i izbaci poruku da ima gresaka
	 // ako je sve u redu isprazni div sa errorima i dozvoli klik na submit button u formi za unos komentara  
	}else if(emailkomentatora.length >= 1 && imekomentatora.length >= 1 && tekstkomentara.length >= 1 && proveraemailkomentatora == true && proverakomentatora == true){ // ako je sve OK dozvoli da se klikne na button
	  $('#erorikomentar').html('');
	  $(this).unbind('click', false);
	}
  });
  
  //submit forme za komentarisanje tj upisivanje komentara u bazu
  $('body').on('click', '#dodajkomentardugme', function(e){
    $(this).bind('click', false); //zabrani klik na ovo dugme
    e.preventDefault();
	var id_teksta = $('#proverakomentatora').attr('id_teksta'); // uzmi userov input u formu za komentarisanje i ovde uzmi id teksta koji se komentarise
	var ime_komentatora = $('#imekomentatora').val(); // ime
	var email_komentatora = $('#emailkomentatora').val(); // mail
	var komentar = $('#tekstkomentara').val(); // komentar
	//alert('id_teksta: '+id_teksta+', ime_komentatora: '+ime_komentatora+', email_komentatora: '+email_komentatora+', komentar: '+komentar);
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/dodajkomentar'; // napravi url za AJAX
	//alert(url);
	postData = { // array sa podatcima koji se salju u kontroler pa u model
	  id_teksta: id_teksta,
	  ime_komentatora: ime_komentatora,
	  email_komentatora: email_komentatora,
	  komentar: komentar
	};
	$.post(url, postData, function(o){ // salji AJAX  u metod dodajkomentar() koji zove istoimeni metod modela i upisuje ove podatke u tabelu 'komentari' i povecava za 1 kolonu komentar u 'tekst' tabeli gde je id_teksta = id_teksta vraca 1 ako upise, 2 ako ne upise
	  console.log(o); 
	  if(o.rezultat == 1){ // ako je komentar uspesno upisan u bazu
	    alert('Uspešno ste uneli komentar!')
		$('#dodajkomentar').slideUp('slow'); // slideUp-uj div sa formom za komentarisanje
		$('#dodajkomentar').remove(); // ukloni ga
		$('#divzakomentarbtn').remove(); // ukloni ga
	  }else if(o.rezultat == 2){ // ako komentar nije upisan u bazu
	    alert('Došlo je do greške, pokušajte ponovo.');
		$('#dodajkomentardugme').unbind('click', false);
	  }
	}, 'json');
  });
  
  // PRIKAZ KOMENTARA
  // u skrivenom <p> na stranici tekst.php je broj komentara teksta koji se prikazuje, selektuj ga u varijablu 
  var brojkomentara = parseInt($('#brojkomentara').text());
  // ako ima komentara prikazi prva 3 i ako ima vise od 3 dodaj dugme na ciji klik se prikazuju i ostali
  if(brojkomentara > 0){
    //alert(brojkomentara);
	var id_teksta = $('#brojkomentara').attr('id_teksta');
	postData = { // array sa podatcima koji se salju u kontroler pa u model
	  id_teksta: id_teksta
	};
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prvatrikomentara'; // napravi url za AJAX
	$.post(url, postData, function(o){ // salji AJAX u metod prvatrikomentara u kontroleru koji izvlaci najnovija 3 komentara za tekst koji se trenutno prikazuje u tekst.php
	  //console.log(o); 
	  var content = '';
	  for(var i = 0; i < o[0].length; i++){ // za svaki od 3 najnovija komentara vracena iz baze napravi div koji ga prikazuje
	    // konvertuj datum da bude citljiv korisniku
	    var godina = o[0][i].datum_komentara.substring(0, 4); // izvuci godinu
		var mesec = o[0][i].datum_komentara.substring(5, 7); // izvuci mesec
		var dan = o[0][i].datum_komentara.substring(8, 10); // izvuci dan
		var sati = o[0][i].datum_komentara.substring(11, 19); // // izvuci sate minute i sekunde
		var datumkomentara = dan+'/'+mesec+'/'+godina+'  '+sati+' h'; // napravi novi datum 	    
		content += '<div id="komentar_'+o[0][i].id_komentara+'">'; //napravi div koji prikazuje ime_komentatora, datum i sam komentar
		content += '<strong>Ime:</strong><em>'+o[0][i].ime_komentatora+', </em>';
		content += '<strong>Datum:</strong><em>'+datumkomentara+'</em>';
		content += '<p class="text-info">'+o[0][i].komentar+'</p>'
		content += '</div><hr>';//kraj diva koji prikazuje jedan komentar	
	  }
	  if(brojkomentara > 3){ // ako ima vise od 3 komentara dodaj btn kojim se prikazuju i ostali komentari
		  content += '<button id="svikomentari" type="button" class="btn btn-default center-block">Prikazi Sve Komentare</button>';
		}
	  $(content).appendTo('#komentari'); // ubaci izgenerisan HTML u div #komentari na dnu tekst.php view-a
	}, 'json');
  }

  // klik na dugme #svikomentari koje postoji samo ako tekst ima vise od 3 komentara
  $('body').on('click', '#svikomentari', function(e){
    e.preventDefault();	
	$(this).remove();
	var id_teksta = $('#brojkomentara').attr('id_teksta');
	postData = { // array sa podatcima koji se salju u kontroler pa u model(samo id teksta)
	  id_teksta: id_teksta
	};
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/svikomentari'; // napravi url za AJAX
	$.post(url, postData, function(o){ 
	  console.log(o);
	  var content = '';
	  for(var i = 0; i < o[0].length; i++){ // za svaki od komentara vracena iz baze napravi div koji ga prikazuje
	    // konvertuj datum da bude citljiv korisniku
	    var godina = o[0][i].datum_komentara.substring(0, 4); // izvuci godinu
		var mesec = o[0][i].datum_komentara.substring(5, 7); // izvuci mesec
		var dan = o[0][i].datum_komentara.substring(8, 10); // izvuci dan
		var sati = o[0][i].datum_komentara.substring(11, 19); // // izvuci sate minute i sekunde
		var datumkomentara = dan+'/'+mesec+'/'+godina+'  '+sati+' h'; // napravi novi datum 	    
		content += '<div id="komentar_'+o[0][i].id_komentara+'">'; //napravi div koji prikazuje ime_komentatora, datum i sam komentar
		content += '<strong>Ime:</strong><em>'+o[0][i].ime_komentatora+', </em>';
		content += '<strong>Datum:</strong><em>'+datumkomentara+'</em>';
		content += '<p class="text-info">'+o[0][i].komentar+'</p>'
		content += '</div><hr>';//kraj diva koji prikazuje jedan komentar	
	  }
	  $(content).appendTo('#komentari'); // ubaci izgenerisan HTML na kraj div-a #komentari koji vec prikazuje prva 3 komentara na dnu tekst.php view-a
	}, 'json');
  });
  





 
  //-------------------------------------------------------------------------------------------------------------------------
  // tekst.php
  //-------------------------------------------------------------------------------------------------------------------------
  
  // dodavanje width i height atributa yt videu pri prikazivanju jednog teksta u tekst.php
  var widthyt = 0;
  var heighthyt = 0;
  $("#glavnaslikatekst").load(function(){ // kad se ucita glavna slika u tekstu
    widthyt = $(this).width(); // uzmi sirinu i visinu slike
    heighthyt = $(this).height();
	// dodaj <iframe> #ytvideotekst elementu koji prikazuje yt video novu sirinu i visinu koje su iste kao kod naslovne slike
	$('#ytvideotekst').css("width", widthyt);
	$('#ytvideotekst').css("height", heighthyt);
	// div-u #map-canvastekst podesi visinu i sirinu koje su iste kao kod naslovne slike
	$('#map-canvastekst').css("width", widthyt);
    $('#map-canvastekst').css("height", heighthyt);
  });
  

  //-------------------------------------------------------------------------------------------------------------------------
  // FUNKCIJA ZA PRIKAZ GOOGLE MAP-e
  //-------------------------------------------------------------------------------------------------------------------------
  
  // proveri da li tekst ima dodat googlemap da bi se kod za crtanje googlemapa izvrsio samo ako ima posto ako nema napravi -
  // - error i ostatak ovog fajla ne radi  
  var gmprovera = parseInt($('#gmprovera').text());//iz skrivenog p u tekst.php uzmi vrednost 1 ako ima GM ili 0 ako nema GM
  if(gmprovera == 1){ // ako ima GM crttaj mapu
    // uzmi iz skrivenih paragrafa koji su dodati ako tekst ima mapu latitudu, longitudu i zoom
    var x = $('#latituda').text();
    var y = $('#longituda').text();
    var zoomMape = parseInt($('#zoom').text()); // zoom prebaci u integer posto je uzet kao string
    var center = new google.maps.LatLng(x, y);
    var map;
  
    // podesavanje mape  
    function initializeMap(){
	  map = new google.maps.Map(document.getElementById('map-canvastekst'), {
	    center: center,
	    zoom:  zoomMape,
	    mapTypeId: google.maps.MapTypeId.HYBRID
	  });

    }
    // ako postoji div #map-canvastekst (a postoji ako tekst ima mapu i ako sam tekst ima string "ovdestavigm") pozovi funkciju koja crta mapu
    if($('#map-canvastekst') != null){
      initializeMap();
    }
  }
/*   
  //-------------------------------------------------------------------------------------------------------------------------
  // KOMENTARISANJE TEKSTA U tekst.php 
  //-------------------------------------------------------------------------------------------------------------------------
  
  // klik na btn #komentarisibtn koje je ispod teksta da bi se prikazala forma za komentarisanje
  $('#komentarisibtn').on('click', function(){
    //alert('komentar');
	$(this).addClass('hidden'); // sakrij button koji je kliknut
	$('#dodajkomentar').hide().removeClass('hidden').slideDown('slow'); // sakrij div koji prikazuje formu(iako ima kklasu hidden posto se radi slideDown), ukloni klasu hidden i slideDown-uj ga
  });  
  
  // cancel komentarisanja teksta
  $('#cancelxkomentara').on('click', function(e){
    e.preventDefault(); // posto je link
    $('#dodajkomentar').slideUp('slow', function(){ // slideUp-uj div sa formom za komentarisanje
	  $('#dodajkomentar').addClass('hidden'); // dodaj mu klasu hidden da bi opet radilo slideDown ako se opet klikne dugme za komentarisanje
	  $('#komentarisibtn').removeClass('hidden'); // dugmetu koje na klik prikazuje formu koje sada sakriveno skini klasu hidden da bi opet bilo vidljivo
	}); 
  });
  
  // funkcija za validaciju emaila
  function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
  }
  
  // validacija unosa u formu za komentarisanje texta kad mis udje na submit button ispod forme za komentarisanje
  $('body').on('mouseenter', '#dodajkomentardugme', function(e){
    var proverakomentatora;
    var br1 = parseInt($('#proverakomentatora').attr('br1')); // iz atributa inputa za unos broja za proveru uzmi br1 i br2(konvertuj ih u integer posto stizu kao stringovi)
	var br2 = parseInt($('#proverakomentatora').attr('br2'));
	var zbir = parseInt($('#proverakomentatora').val()); // uzmi userov unos
	if(zbir == br1 + br2){ // proveri da li je br1+br2 = userovom unosu
	   proverakomentatora = true; // ako jeste true
	}else{
	   proverakomentatora = false; // ako nije false
	}
    var emailkomentatora = $('#emailkomentatora').val();
	var proveraemailkomentatora = validateEmail(emailkomentatora); // zovi funkciju za proveru validnosti emaila sa regexp-om
	var imekomentatora = $('#imekomentatora').val();
	var tekstkomentara = $('#tekstkomentara').val();
	 // proveri duzine unetih stringova u polja i proveri da li je email validan tj da li funkcija validateEmail() vraca true i da li je zbir br1 i br2 = userovom unosu 
	if(emailkomentatora.length < 1 || imekomentatora.length < 1 || tekstkomentara.length < 1 || proveraemailkomentatora != true || proverakomentatora != true){
	  $('#erorikomentar').html('<p class="lead text-center text-danger">Pogrešno ste popunili formu, pokušajte ponovo.</p>');
	  $(this).bind('click', false); // ako ne prodje if disable-uj button i izbaci poruku da ima gresaka
	 // ako je sve u redu isprazni div sa errorima i dozvoli klik na submit button u formi za unos komentara  
	}else if(emailkomentatora.length >= 1 && imekomentatora.length >= 1 && tekstkomentara.length >= 1 && proveraemailkomentatora == true && proverakomentatora == true){ // ako je sve OK dozvoli da se klikne na button
	  $('#erorikomentar').html('');
	  $(this).unbind('click', false);
	}
  });
  
  //submit forme za komentarisanje tj upisivanje komentara u bazu
  $('body').on('click', '#dodajkomentardugme', function(e){
    $(this).bind('click', false); //zabrani klik na ovo dugme
    e.preventDefault();
	var id_teksta = $('#proverakomentatora').attr('id_teksta'); // uzmi userov input u formu za komentarisanje i ovde uzmi id teksta koji se komentarise
	var ime_komentatora = $('#imekomentatora').val(); // ime
	var email_komentatora = $('#emailkomentatora').val(); // mail
	var komentar = $('#tekstkomentara').val(); // komentar
	//alert('id_teksta: '+id_teksta+', ime_komentatora: '+ime_komentatora+', email_komentatora: '+email_komentatora+', komentar: '+komentar);
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/dodajkomentar'; // napravi url za AJAX
	//alert(url);
	postData = { // array sa podatcima koji se salju u kontroler pa u model
	  id_teksta: id_teksta,
	  ime_komentatora: ime_komentatora,
	  email_komentatora: email_komentatora,
	  komentar: komentar
	};
	$.post(url, postData, function(o){ // salji AJAX  u metod dodajkomentar() koji zove istoimeni metod modela i upisuje ove podatke u tabelu 'komentari' i povecava za 1 kolonu komentar u 'tekst' tabeli gde je id_teksta = id_teksta vraca 1 ako upise, 2 ako ne upise
	  console.log(o); 
	  if(o.rezultat == 1){ // ako je komentar uspesno upisan u bazu
	    alert('Uspešno ste uneli komentar!')
		$('#dodajkomentar').slideUp('slow'); // slideUp-uj div sa formom za komentarisanje
		$('#dodajkomentar').remove(); // ukloni ga
		$('#divzakomentarbtn').remove(); // ukloni ga
	  }else if(o.rezultat == 2){ // ako komentar nije upisan u bazu
	    alert('Došlo je do greške, pokušajte ponovo.');
		$('#dodajkomentardugme').unbind('click', false);
	  }
	}, 'json');
  });
  
  // PRIKAZ KOMENTARA
  // u skrivenom <p> na stranici tekst.php je broj komentara teksta koji se prikazuje, selektuj ga u varijablu 
  var brojkomentara = parseInt($('#brojkomentara').text());
  // ako ima komentara prikazi prva 3 i ako ima vise od 3 dodaj dugme na ciji klik se prikazuju i ostali
  if(brojkomentara > 0){
    //alert(brojkomentara);
	var id_teksta = $('#brojkomentara').attr('id_teksta');
	postData = { // array sa podatcima koji se salju u kontroler pa u model
	  id_teksta: id_teksta
	};
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prvatrikomentara'; // napravi url za AJAX
	$.post(url, postData, function(o){ // salji AJAX u metod prvatrikomentara u kontroleru koji izvlaci najnovija 3 komentara za tekst koji se trenutno prikazuje u tekst.php
	  //console.log(o); 
	  var content = '';
	  for(var i = 0; i < o[0].length; i++){ // za svaki od 3 najnovija komentara vracena iz baze napravi div koji ga prikazuje
	    // konvertuj datum da bude citljiv korisniku
	    var godina = o[0][i].datum_komentara.substring(0, 4); // izvuci godinu
		var mesec = o[0][i].datum_komentara.substring(5, 7); // izvuci mesec
		var dan = o[0][i].datum_komentara.substring(8, 10); // izvuci dan
		var sati = o[0][i].datum_komentara.substring(11, 19); // // izvuci sate minute i sekunde
		var datumkomentara = dan+'/'+mesec+'/'+godina+'  '+sati+' h'; // napravi novi datum 	    
		content += '<div id="komentar_'+o[0][i].id_komentara+'">'; //napravi div koji prikazuje ime_komentatora, datum i sam komentar
		content += '<strong>Ime:</strong><em>'+o[0][i].ime_komentatora+', </em>';
		content += '<strong>Datum:</strong><em>'+datumkomentara+'</em>';
		content += '<p class="text-info">'+o[0][i].komentar+'</p>'
		content += '</div><hr>';//kraj diva koji prikazuje jedan komentar	
	  }
	  if(brojkomentara > 3){ // ako ima vise od 3 komentara dodaj btn kojim se prikazuju i ostali komentari
		  content += '<button id="svikomentari" type="button" class="btn btn-default center-block">Prikazi Sve Komentare</button>';
		}
	  $(content).appendTo('#komentari'); // ubaci izgenerisan HTML u div #komentari na dnu tekst.php view-a
	}, 'json');
  }

  // klik na dugme #svikomentari koje postoji samo ako tekst ima vise od 3 komentara
  $('body').on('click', '#svikomentari', function(e){
    e.preventDefault();	
	$(this).remove();
	var id_teksta = $('#brojkomentara').attr('id_teksta');
	postData = { // array sa podatcima koji se salju u kontroler pa u model(samo id teksta)
	  id_teksta: id_teksta
	};
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/svikomentari'; // napravi url za AJAX
	$.post(url, postData, function(o){ 
	  console.log(o);
	  var content = '';
	  for(var i = 0; i < o[0].length; i++){ // za svaki od komentara vracena iz baze napravi div koji ga prikazuje
	    // konvertuj datum da bude citljiv korisniku
	    var godina = o[0][i].datum_komentara.substring(0, 4); // izvuci godinu
		var mesec = o[0][i].datum_komentara.substring(5, 7); // izvuci mesec
		var dan = o[0][i].datum_komentara.substring(8, 10); // izvuci dan
		var sati = o[0][i].datum_komentara.substring(11, 19); // // izvuci sate minute i sekunde
		var datumkomentara = dan+'/'+mesec+'/'+godina+'  '+sati+' h'; // napravi novi datum 	    
		content += '<div id="komentar_'+o[0][i].id_komentara+'">'; //napravi div koji prikazuje ime_komentatora, datum i sam komentar
		content += '<strong>Ime:</strong><em>'+o[0][i].ime_komentatora+', </em>';
		content += '<strong>Datum:</strong><em>'+datumkomentara+'</em>';
		content += '<p class="text-info">'+o[0][i].komentar+'</p>'
		content += '</div><hr>';//kraj diva koji prikazuje jedan komentar	
	  }
	  $(content).appendTo('#komentari'); // ubaci izgenerisan HTML na kraj div-a #komentari koji vec prikazuje prva 3 komentara na dnu tekst.php view-a
	}, 'json');
  });
   */
  
  
  
};






































