var Internetnovine = function(){

  //alert('pozdrav iz internetnovine funkcije!');
  
  // kad se u adminpanel view-u klikne dugme za kreiranje autora izbaci mu ovu formu
  $('#praviautoradugme').on('click', function(){
    var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/praviautora';
	$('#praviautora').addClass('senka');
	$(this).prop('disabled', true);
    var content = '';
	content += '<div id="formazaunosautora" class="control-group">';
	content += '<div class="text-right">';
	content += '<a href="#" id="cancelx"  class="lead text-danger">cancelX</a>';
	content += '</div>';
	// enctype="multipart/form-data" ovo je posto forma salje i uploadovanu sliku pa ona ide u $_FILES[] a ne u $_POST[]
	content += '<form action="'+baseurl+'internetnovine/praviautora" enctype="multipart/form-data" class="form-horizontal" method="post">'; 
    content += '<div class="form-group">';
    content += '<p id="kreirajformap" class="lead text-success">U polja unesite podatke i kliknite dugme Kreiraj</p>';
    content += '</div>';	
    content += '<div class="form-group has-success">';
	content += '<label for="email" class="col-sm-2 control-label">Email :</label>';
	content += '<div class="col-sm-6">';
	content += '<input name="email" type="text" class="form-control" id="email"><br>';
	content += '</div>';
	content += '</div>';
    content += '<div class="form-group has-success">';
	content += '<label for="ime" class="col-sm-2 control-label">Ime :</label>';
	content += '<div class="col-sm-6">';
	content += '<input name="ime" type="text" class="form-control" id="ime" ><br>';
	content += '</div>';
	content += '</div>';
	content += '<div class="form-group has-success">';
	content += '<label for="password" class="col-sm-2 control-label">Password :</label>';
	content += '<div class="col-sm-6">';
	content += '<input name="password" type="password" class="form-control" id="password" ><br>';
	content += '</div>';
	content += '</div>';
	content += '<div class="form-group has-success">';
	content += '<label for="slika" class="col-sm-2 control-label">Slika :'+' '+'</label>';
	content += '<div class="col-sm-6">';
	content += '<input type="file" name="slika" id="slika"><br>';
	content += '</div>';
	content += '</div>';
	content += '<div class="form-group has-success">';
	content += '<button type="submit" id="kreirajformabutton"  class="btn btn-success">Kreiraj</button>';
	content += '</div><br>';
	content += '</form>';
	content += '<div id="erori"></div>'; // div za error poruke koji se rade na blur polja
	content += '</div>';
		
	
	$(content).insertAfter('#praviautora').hide().slideDown('500');
  });
  //---------------------------------------------------------------------------------------------------------------------
  // VALIDACIJA FORME ZA KREIRANJE NOVOGAUTORA U ADMINPANEL-u
  //validiraj uneti Email na blur Email polja
  $('body').on('blur', '#email', function(e){
    var unetimail = $(this).val();
	if(!validateEmail(unetimail) || unetimail.length < 1){ // validiraj emial na blur (dole je funkicija koja to radi i vraca bulian), ako ne prodje validaciju izbaci poruku i disejbluj submit
	  $('#erori').html('<p class="lead text-center text-danger">U polje Email morate uneti validnu email adresu.</p>');
	  $('#kreirajformabutton').prop('disabled', true);
	}else{ // ako prodje validaciju obrisi poruku i ukini diable na submit
	  $('#erori').html('');
	  $('#kreirajformabutton').prop('disabled', false);
	}
  });
  // funkcija za validaciju unetog email-a
  function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
  }
  
  //validiraj uneto Ime na blur Ime polja
  $('body').on('blur', '#ime', function(e){
    var unetoime = $(this).val();
	 if(unetoime.length < 1){ // validiraj ime na blur ako nista ne unese izbaci poruku i disejbluj submit
	  $('#erori').html('<p class="lead text-center text-danger">U polje Ime morate uneti nesto...</p>');
	  $('#kreirajformabutton').prop('disabled', true);
	}else{ // ako prodje validaciju obrisi poruku i ukini diable na submit
	  $('#erori').html('');
	  $('#kreirajformabutton').prop('disabled', false);
	} 
  });
  
  //validiraj uneto pass na blur password polja
  $('body').on('blur', '#password', function(e){
    var unetipass = $(this).val();
	 if(unetipass.length < 6){ // validiraj password na blur ako unese manje od 6 karaktera izbaci poruku i disejbluj submit
	  $('#erori').html('<p class="lead text-center text-danger">U polje Password morate uneti najmanje 6 karaktera.</p>');
	  $('#kreirajformabutton').prop('disabled', true);
	}else{ // ako prodje validaciju obrisi poruku i ukini diable na submit
	  $('#erori').html('');
	  $('#kreirajformabutton').prop('disabled', false);
	} 
  });
  
  //kad mis udje iznad submit buton-a opet validiraj 
  $('body').on('mouseenter', '#kreirajformabutton', function(e){
	var unetiemail = $('#email').val();
	var proveramaila = validateEmail(unetiemail); // zovi funkciju za proveru validnosti emaila sa regexp-om
	var unetoime = $('#ime').val();
	var unetipass = $('#password').val();
    if(unetiemail.length < 1 || unetoime.length < 1 || unetipass.length < 6 || proveramaila != true){ // proveri duzine unetih stringova u polja i proveri da li je email validan tj da li funkcija validateEmail() vraca true 
	  $('#erori').html('<p class="lead text-center text-danger">Pogrešno ste popunili formu, pokušajte ponovo.</p>');
	  $(this).prop('disabled', true); // ako ne prodje if disable-uj button i izbaci poruku da ima gresaka
	}else{ // ako je sve OK dozvoli da se klikne na button
	  $('#erori').html('');
	  $(this).prop('disabled', false);
	}
  }); 

  //---------------------------------------------------------------------------------------------------------------------
  // PREPRAVLJANJE PODATAKA AUTORA U ADMINPANEL-u
  //---------------------------------------------------------------------------------------------------------------------
  // kad se u adminpanel view-u klikne dugme za prepravljanje podataka autora izbaci mu ovu formu
  $('#prepraviautoradugme').on('click', function(){
    $(this).prop('disabled', true); // disable-uj dugme #prepraviautoradugme
    var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prikaziautore';
	postData = {
	  provera: 1
	};
	console.log(postData);
	$.post(url, postData, function(o){
	  console.log(o);  
	  var content = '';
	  content += '<div id="listaautora" class="control-group">'; // pocetak div-a u koji ce uci lista autora koja se pravi u for petlji koja iterira po objektu vracenom iz kontrolera
	  content += '<div class="text-right"><a href="#" id="cancelx1"  class="lead text-danger">cancelX</a></div>';
	  for(var i = 0; i < o.length; i++){ //ako pronadje nesto, iteriraj po objektu i prikazi sliku, ime, id i email adresu	
        content += '<div id="autor'+o[i].userid+'" class="autordiv">';		
		content += '<img class="slikaautora" src="'+baseurl+'images/autori/'+o[i].userid+'.jpg?vreme='+$.now()+'">';
		content += '<p class="imeadmina text-success"><strong>Ime: </strong>'+o[i].username+' '+'<strong>Id: </strong>'+o[i].userid+'</p>';
        content += '<p class="emailadmina text-success"><strong>Email: </strong>'+o[i].usermail+'</p>';
		// button za pozivanje forme za promenu podataka autora, u sebi ima atribute sa imenom i id-em autora
		content += '<p id="menjajautorabutton" mailautora="'+o[i].usermail+'" imeautora="'+o[i].username+'" idautora="'+o[i].userid+'" class="text-right"><button type="button" class="btn btn-success btn-xs">Izmeni Podatke</button></p>';
		// button za brisanje autora u sebi ima atribute sa imenom i id-em autora
		content += '<p id="brisiautorabutton" imeautora="'+o[i].username+'"  brisanjeid="'+o[i].userid+'" class="text-right"><button type="button" class="btn btn-danger btn-xs">Obriši Autora!!!!</button></p><hr></div>';	
	  }
	  content += '</div>'; // zatvori div #formazaunosautora
	  $(content).insertAfter('#prepraviautora').hide().slideDown('500');
	  $('#prepraviautora').addClass('senka');
	}, 'json');
	
  });
  // klik na button za brisanje autora tj #brisiautorabutton
  $('body').on('click', '#brisiautorabutton', function(e){
    e.preventDefault();
	//alert('Brisi Autora');
	var id = $(this).attr('brisanjeid'); // dugme ima atriburte brisanjeid i imeautora koji cuvaju id i ime autora
	var ime = $(this).attr('imeautora');
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/obrisiautora'; // napravi url kom se salje post
	postData = { // posalji id filma u kontroler
	  id: id
	};
	if(confirm("Da Li Ste Sigurni Da Želite Da Obrišete Nalog Autora: "+ime+"?")){//izbaci confirm user-u da potvrdi brisanje, ako potvrdi salji $.post u kontroler u kom je id autora da dalje posalje u model koi brise i vraca 1 ako uspe brisanje
        //alert(id);
		$.post(url, postData, function(obj){
		  console.log(obj);
		  if(obj == 1){ // ako model vrati jedan tj brianje je uspesno
		    alert('Uspešno Ste Obrisali autora "'+ime+'".'); // izbaci alert da je autor obrisan
		    $('#autor'+id).fadeOut(1000, function(){
			  $('#autor'+id).remove();//remove-uj div autora koji je obrisan
			});		
		  }else{ // ako model ne vrati 1 tj dodje do neke greske prilikom brisanja iz baze
		    alert('doslo je do greske');
		  }
		}, 'json');
    }else{ // ako user klikne 'No' u confirmu
        return false;
    } 
  });
  
  //klik na button za menjanje podataka autora tj #menjajautorabutton
  $('body').on('click', '#menjajautorabutton', function(e){
    e.preventDefault();
	$(this).prop('disabled', true);
    var baseurl = $('#baseurl').text();
	var id = $(this).attr('idautora'); // dugme ima atriburte brisanjeid i imeautora koji cuvaju id i ime autora
	var ime = $(this).attr('imeautora');
	var mail = $(this).attr('mailautora');
	//alert(baseurl);
	var content = ''; // napravi formu za promenu podataka autora
	content += '<div id="formazaprepravkuautora'+id+'">';
	content += '<form method="post" class="form-inline" action="'+baseurl+'internetnovine/prepraviautora" enctype="multipart/form-data">';
	content += '<input type="hidden" id="idprepravka" name="idprepravka" value="'+id+'"> ';
    content += '<div class="form-group">';
    content += '<label for="imeprepravka">Ime</label>';
    content += '<input type="text" name="imeprepravka" class="form-control" id="imeprepravka" value="'+ime+'">';
    content += '</div>';
    content += '<div class="form-group">';
    content += '<label for="mailprepravka">Email</label>';
    content += '<input type="email" name="mailprepravka" class="form-control" id="mailprepravka" value="'+mail+'">';
    content += '</div>';
	content += '<div class="form-group">';
    content += '<label for="passprepravka">Pass</label>';
    content += '<input type="password" name="passprepravka" class="form-control" id="passprepravka">';
    content += '</div><br>';
	content += '<div class="form-group">';
	content += '<input type="file" name="slika2" id="slika2"><br>';
	content += '</div><br>';
    content += '<button id="izmenipodatke" idautora="'+id+'" type="submit" class="btn btn-default btn-success btn-xs">Izmeni</button>';
    content += '</form>';
	content += '<div id="erroriprepravke"></div>'; // div za prikaz errora pri popunjavanju forme
    content += '<hr></div>'; // kraj div-a #formazaprepravkuautora
	$(content).insertAfter('#autor'+id).hide().slideDown('500'); // dodaj formu ispod div-a koji prikazuje autora
  });
  // VALIDACIJA UNOSA U POLJA ZA PREPRAVLJANJE PODATAKA AUTORA
  // na blur Email polja u formi za prepravke validiraj email
  $('body').on('blur', '#mailprepravka', function(e){
    var unetimail = $(this).val();
	if(!validateEmail(unetimail) || unetimail.length < 1){ // validiraj emial na blur (dole je funkicija koja to radi i vraca bulian), ako ne prodje validaciju izbaci poruku i disejbluj submit
	  $('#erroriprepravke').html('<p class="lead text-center text-danger">U polje Email morate uneti validnu email adresu.</p>');
	  $('#izmenipodatke').prop('disabled', true);
	}else{ // ako prodje validaciju obrisi poruku i ukini diable na submit
	  $('#erroriprepravke').html('');
	  $('#izmenipodatke').prop('disabled', false);
	}
  });
  //validiraj uneto Ime na blur #imeprepravka polja
  $('body').on('blur', '#imeprepravka', function(e){
    var unetoime = $(this).val();
	 if(unetoime.length < 1){ // validiraj ime na blur ako nista ne unese izbaci poruku i disejbluj submit
	  $('#erroriprepravke').html('<p class="lead text-center text-danger">U polje Ime morate uneti nesto...</p>');
	  $('#izmenipodatke').prop('disabled', true);
	}else{ // ako prodje validaciju obrisi poruku i ukini diable na submit
	  $('#erroriprepravke').html('');
	  $('#izmenipodatke').prop('disabled', false);
	} 
  });
  //validiraj uneti pass na blur #passprepravka polja
  $('body').on('blur', '#passprepravka', function(e){
    var unetipass = $(this).val();
	 if(unetipass.length > 0 && unetipass.length < 6){ // validiraj password na blur ako unese manje od 6 karaktera izbaci poruku i disejbluj submit
	  $('#erroriprepravke').html('<p class="lead text-center text-danger">Ako zelite da menjate Password u polje Password morate uneti najmanje 6 karaktera.</p>');
	  $('#izmenipodatke').prop('disabled', true);
	}else{ // ako prodje validaciju obrisi poruku i ukini diable na submit
	  $('#erroriprepravke').html('');
	  $('#izmenipodatke').prop('disabled', false);
	} 
  });
  //kad mis udje iznad submit buton-a tj #izmenipodatke opet validiraj 
  $('body').on('mouseenter', '#izmenipodatke', function(e){
    //alert('pozz');
	var unetiemail = $('#mailprepravka').val();
	var proveramaila = validateEmail(unetiemail); // zovi funkciju za proveru validnosti emaila sa regexp-om
	var unetoime = $('#imeprepravka').val();
	var unetipass = $('#passprepravka').val();
	var proveripass = true;
	if(unetipass.length > 0 && unetipass.length < 6){//proveri da li je u pass uneto vise od 6 karaktera ili nista postto je i to validno
	  proveripass = false;
	}
    if(unetiemail.length < 1 || unetoime.length < 1 || proveripass != true || proveramaila != true){ // proveri duzine unetih stringova u polja i proveri da li je email validan tj da li funkcija validateEmail() vraca true 
	  $('#erroriprepravke').html('<p class="lead text-center text-danger">Pogrešno ste popunili formu, pokušajte ponovo.</p>');
	  $(this).prop('disabled', true); // ako ne prodje if disable-uj button i izbaci poruku da ima gresaka
	}else{ // ako je sve OK dozvoli da se klikne na button
	  $('#erroriprepravke').html('');
	  $(this).prop('disabled', false);
	}
  }); 
  
  //--------------------------------------------------------------------------------------------------------------------- 
  // SLANJE MAIL-OVA AUTORIMA U ADMINPANEL-u
  //---------------------------------------------------------------------------------------------------------------------
  // kad se u adminpanel view-u klikne dugme za slanje mail-ova autorima izbaci ovu formu
  $('#mailautorudugme').on('click', function(){
    //alert('pozz ia slanja mailova');
	$(this).prop('disabled', true); // disable-uj dugme #mailautorudugme
    var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prikaziautore'; // zovi metod koji vraca podatke autora iz baze
	postData = {
	  provera: 1
	};
	$.post(url, postData, function(o){
	  console.log(o);  
	  var content = ''; // forma za slanje maila, obavezna polja naslov i sadrzaj i mora se odabrati barem jedan autor koji prima mail, fajl za attach nije obavezan
	  content += '<div id="listaautorazamail" class="control-group">'; 
	  content += '<div class="text-right"><a href="#" id="cancelx2"  class="lead text-danger">cancelX</a></div>';
	  content += '<form action="'+baseurl+'internetnovine/saljimail" enctype="multipart/form-data" class="form-horizontal" method="post">';
	  content += '<div class="form-group">';
      content += '<p id="kreirajformap" class="lead text-success">Popunite formu, odaberite autora i kliknite "Posalji"</p>';
      content += '</div>';  
	  content += '<div class="form-group has-success">';
	  content += '<label for="naslovmaila" class="col-sm-2 control-label">Naslov :</label>';
	  content += '<div class="col-sm-6">';
	  content += '<input name="naslovmaila" type="text" class="form-control" id="naslovmaila"><br>';
	  content += '</div>';
	  content += '</div>';
	  content += '<div class="form-group has-success">';
	  content += '<label for="sadrzajmaila" class="col-sm-2 control-label">Sadrzaj :</label>';
	  content += '<div class="col-sm-6">';
	  content += '<textarea name="sadrzajmaila" class="form-control" id="sadrzajmaila"></textarea><br>';
	  content += '</div>';
	  content += '</div>';	  
	  content += '<div class="form-group has-success">'; // ako zeli da attach-uje fajl
	  content += '<label for="fajl" class="col-sm-2 control-label">File: '+' '+'</label>';
	  content += '<div class="col-sm-6">';
	  content += '<input type="file" name="fajl" id="fajl"><br>';
	  content += '</div>';
	  content += '</div>';	  
	  for(var i = 0; i < o.length; i++){ // checkbox-ovi gde admin bira autore kojima salje mail
	    content += '<div class="form-group has-success">';
		content += '<div class="col-sm-offset-2 col-sm-10">';
	    content += '<div class="checkbox">';
        content += '<label>';
        content += '<input type="checkbox" name="usermail[]" value="'+o[i].usermail+'">';
        content += o[i].username;
        content += '</label>';
        content += '</div>';
		content += '</div>';
		content += '</div>';
	  }
	  content += '<div class="form-group has-success">';
	  content += '<div class="col-sm-offset-2 col-sm-10">';
	  content += '<button type="submit" id="posaljimailbutton"  class="btn btn-success">Posalji</button>';
	  content += '</div></div><br>';  
	  content += '</form>';
	  content += '<div id="errorimaila"></div>'; 
	  content += '</div>'; // kraj div-a #listaautorazamail
	  $(content).insertAfter('#mailautoru').hide().slideDown('500');
	  $('#mailautoru').addClass('senka');
	}, 'json');
  });
  
  // VALIDACIJA UNOSA U POLJA ZA SLANJE EMAIL-a AUTORIMA U ADMIN PANEL-u
  //validiraj uneti naslov maila na blur #naslovmaila polja
  $('body').on('blur', '#naslovmaila', function(e){
    var naslovmaila = $(this).val();
	 if(naslovmaila.length < 1){ // validiraj ime na blur ako nista ne unese izbaci poruku i disejbluj submit
	  $('#errorimaila').html('<p class="lead text-center text-danger">Popunite polje Naslov.</p>');
	  $('#posaljimailbutton').prop('disabled', true);
	}else{ // ako prodje validaciju obrisi poruku i ukini diable na submit
	  $('#errorimaila').html('');
	  $('#posaljimailbutton').prop('disabled', false);
	} 
  });
  //validiraj uneti sadrzaj maila na blur #sadrzajmaila polja
  $('body').on('blur', '#sadrzajmaila', function(e){
    var sadrzajmaila = $(this).val();
	 if(sadrzajmaila.length < 1){ // validiraj ime na blur ako nista ne unese izbaci poruku i disejbluj submit
	  $('#errorimaila').html('<p class="lead text-center text-danger">Popunite polje Sadrzaj.</p>');
	  $('#posaljimailbutton').prop('disabled', true);
	}else{ // ako prodje validaciju obrisi poruku i ukini diable na submit
	  $('#errorimaila').html('');
	  $('#posaljimailbutton').prop('disabled', false);
	} 
  });
  //kad mis udje iznad submit buton-a tj #posaljimailbutton opet validiraj 
  $('body').on('mouseenter', '#posaljimailbutton', function(e){
    //alert('pozz');
	var naslov = $('#naslovmaila').val();
	var sadrzaj = $('#sadrzajmaila').val();
    // var primaoci = $('input[type="checkbox"]:checked').length;
	// alert(primaoci);
    if(naslov.length < 1 || sadrzaj.length < 1 || $('input[type="checkbox"]:checked').length < 1){ // proveri duzine unetih stringova u polja i proveri da li je cekiran neko od autora tj da li je odredjeno ko sve prima mail 
	  $('#errorimaila').html('<p class="lead text-center text-danger">Pogrešno ste popunili formu, pokušajte ponovo.</p>');
	  $(this).prop('disabled', true); // ako ne prodje if disable-uj button i izbaci poruku da ima gresaka
	}else{ // ako je sve OK dozvoli da se klikne na button
	  $('#errorimaila').html('');
	  $(this).prop('disabled', false);
	}
  });
  
  //-------------------------------------------------------------------------------------------------------
  
  
  // klik na link cancelX koji je u gornjem desnom uglu forme, samo radi slideUp() div-a sa formom i enable-uje dugme #praviautoradugme i onda radi remove() div-a sa formom
  $('body').on('click', '#cancelx', function(e){
    e.preventDefault();
	$('#formazaunosautora').slideUp('slow', function(){
	  $(this).remove();  
	  $('#praviautora').removeClass('senka');
	});
	$('#praviautoradugme').prop('disabled', false);	
  });
  // klik na link cancelX1 koji je u gornjem desnom uglu liste sa autorima, samo radi slideUp() div-a sa listom i enable-uje dugme #praviautoradugme i onda radi remove() div-a sa listom
  $('body').on('click', '#cancelx1', function(e){
    e.preventDefault();
	$('#listaautora').slideUp('slow', function(){
	  $(this).remove();  
	  $('#prepraviautora').removeClass('senka');
	});
	$('#prepraviautoradugme').prop('disabled', false);	
  });
  // klik na link cancelX2 koji je u gornjem desnom uglu forme za mailove autorima, samo radi slideUp() div-a sa listom i enable-uje dugme #mailautorudugme i onda radi remove() div-a sa listom
  $('body').on('click', '#cancelx2', function(e){
    e.preventDefault();
	$('#listaautorazamail').slideUp('slow', function(){
	  $(this).remove();  
	  $('#mailautoru').removeClass('senka');
	});
	$('#mailautorudugme').prop('disabled', false);	
  });
  
  // klik na #zatvorierrorporuku u adminpanelu koji prikazuje error vracen od kontrolera
  $('body').on('click', '#zatvorierrorporuku', function(e){
    e.preventDefault();
	$('#errori').slideUp('slow', function(){
	  $(this).remove();  
	  var url = $('#baseurl').text()+'internetnovine/adminpanel'; // radi redirekciju na url adminpanel, da ne bi slao post u kontroler na refresh
      location.replace(url);
	});
  });
  // klik na #zatvoriuspehporuku u adminpanelu koji prikazuje error vracen od kontrolera
  $('body').on('click', '#zatvoriuspehporuku', function(e){
    e.preventDefault();
	$('#uspeh').slideUp('slow', function(){
	  $(this).remove();  
	  var url = $('#baseurl').text()+'internetnovine/adminpanel'; // radi redirekciju na url adminpanel, da ne bi slao post u kontroler na refresh
      location.replace(url);
	});
  });
  
  //-------------------------------------------------------------------------------------------------------
  // RAD SA TEKSTOVIMA
  //-------------------------------------------------------------------------------------------------------
  
  // NEODOBRENI TEKSTOVI
  
  var rezpostr = 10;
  //klik na #neodobrenitekstovidugme dugme da prikaze tekstove koje admin jos uvek nije odobrio
  $('#neodobrenitekstovidugme').on('click', function(){
    var displej = $( window ).width();
    //alert(displej);
	$('#neodobrenitekstovi').addClass('senka'); // dodaj senka klasu div-u na koji ce se zakaciti prikaz neodobrenih tekstova
    $(this).prop('disabled', true); // disable-uj dugme #neodobrenitekstovidugme
    var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prikazineodobrenetekstove'; // zovi metod koji vraca podatke tekstova osim teksta
	
	postData = {
	  limit: rezpostr,
	  offset: 0
	};
	$.post(url, postData, function(o){
	  console.log(o); // u objektu su podatci teksta osim teksta samog i ukupan broj neodobrenih tekstova u bazi zbog paginacije
	  var total = o[1]; // broj neodobrenih tekstova u bazi
	  var content = '';
      content += '<div id="kontrolatekstova"><br>';
	  content += '<div id="tekstovispoljni">';
	  content += '<div class="text-right"><a href="#" id="cancelxneodobreni"  class="lead text-danger">cancelX</a></div>';
	  content += '<div id="tekstovi">';
      for(var i = 0; i < o[0].length; i++){ // iteriraj po array-u koji je stigao iz kontrolera i prikazuj naslov, id, rubriku, autora i datum teksta
	    content += '<div id="'+o[0][i].id_teksta+'">';
	    content += '<h3  id="naslov'+o[0][i].id_teksta+'" class="text-danger">Naslov: '+o[0][i].naslov+'</h3>';
		content += '<p class="text-info"><strong>id: </strong><em>'+o[0][i].id_teksta+'</em>,<strong> Rubrika: </strong><em>'+o[0][i].rubrika+'</em>, <strong>Autor:</strong> <em>'+o[0][i].username+'</em>, <strong>Datum:</strong> <em>'+o[0][i].datum+'</em></p>';
		content += '<button type="button" id="prikazineodobrentekst'+o[0][i].id_teksta+'"  class="prikazineodobrentekst btn btn-success btn-xs" id_teksta="'+o[0][i].id_teksta+'" podnaslov="'+o[0][i].podnaslov+'" mapa="'+o[0][i].googlemap+'" slike="'+o[0][i].slike+'" yt="'+o[0][i].yt+'">prikazi tekst</button>';
	    content += '<hr></div>'; // kraj div-a #id_teksta koji prikazuje naslov i osnovne podatke i dugme prikazi tekst 
	 }	
	  content += '</div>'; // kraj div-a #tekstovi
	  content += '</div>'; // kraj div-a #tekstovispoljni
      content += '<div id="paginacijadiv" class="light-theme"></div><br><br>';
	  content += '</div>';//kraj div-a #kontrolatekstova
      $(content).insertAfter('#neodobrenitekstovi').hide().slideDown('500');
	  
	  if(o[1] > rezpostr){ // ako vrati vise rezultata nego sto je dozvoljeno po stranici generisi linkove za paginaciju    
		//console.log(paginacijaIme);
		brstranica = Math.ceil(total / rezpostr);
		console.log('brstranica: '+brstranica);
		var listalinkova = '';
		for(var p = 1; p <= brstranica; p++){
		  listalinkova += '<a href="'+p+'" class="paginacijalink';
		  if(p == 1){
		    listalinkova += ' current'; // prvom linku dodaj klasu current
		  }
		  listalinkova += '">'+p+'</a>';
		}
		$('#paginacijadiv').html(listalinkova);
	  }
	}, 'json');
	
  });
  
  // ako link za paginaciju ima klasu current tj user je na toj stranici i mis udje na njega zabrani klik na njega
  $('body').on('mouseenter', '.paginacijalink', function(e){
    if($(this).hasClass('current')){
	  $(this).bind('click', false);
	}
  });
  // ako link za paginaciju ima klasu current tj user je na toj stranici i mis izadje sa njega dozvoli opet klik na njega
  $('body').on('mouseleave', '.paginacijalink', function(e){
    if($(this).hasClass('current')){
	  $(this).unbind('click', false); 
	}
  });
  
  // klik na link za paginaciju
  $('body').on('click', '.paginacijalink', function(e){ 
    e.preventDefault();
	//$("body, #kontrolatekstova").animate({ scrollTop: 370 }, "fast"); // animiraj skroltop na vrh diva	
	$("body, html").animate({ scrollTop: 370 }, "fast");
	$('.current').removeClass('current'); // linku koji je bio aktivan tj imao klasu current skini istu
	$(this).addClass('current'); // dodaj klasu current linku na koji je kliknuto
	var baseurl = $('#baseurl').text();//<p>#baseurl je skriveni paragraf koji echo-uje baseurl()
	var url = baseurl+'internetnovine/prikazineodobrenetekstove'; // zovi metod  
	var cur_page = $(this).attr('href'); // uzmi vrednost linka koji je kliknut
	var skip = ((cur_page - 1) * rezpostr); // napravi offset
	postData = {
	  limit: rezpostr,
	  offset: skip
	};
	
	$.post(url, postData, function(o){
	  console.log(o);
	  var total = o[1];
	  var content = '';
	  $('#tekstovispoljni').html('');
	  content += '<div class="text-right"><a href="#" id="cancelxneodobreni"  class="lead text-danger">cancelX</a></div>';
      content += '<div id="tekstovi">';
      for(var i = 0; i < o[0].length; i++){ 
	    content += '<div id="'+o[0][i].id_teksta+'">';
	    content += '<h3 id="naslov'+o[0][i].id_teksta+'" class="text-danger">Naslov: '+o[0][i].naslov+'</h3>';
		content += '<p class="text-info"><strong>id: </strong><em>'+o[0][i].id_teksta+'</em>,<strong> Rubrika: </strong><em>'+o[0][i].rubrika+'</em>, <strong>Autor:</strong> <em>'+o[0][i].username+'</em>, <strong>Datum:</strong> <em>'+o[0][i].datum+'</em></p>';
		content += '<button type="button" id="prikazineodobrentekst'+o[0][i].id_teksta+'" class="prikazineodobrentekst btn btn-success btn-xs" id_teksta="'+o[0][i].id_teksta+'" podnaslov="'+o[0][i].podnaslov+'" mapa="'+o[0][i].googlemap+'" slike="'+o[0][i].slike+'" yt="'+o[0][i].yt+'">prikazi tekst</button>';
	    content += '<hr></div>'; // kraj div-a #id_teksta koji prikazuje naslov i osnovne podatke i dugme prikazi tekst 
	  }	
	  content += '</div>';//kraj div-a #tekstovi
	  $('#tekstovispoljni').html(content);
	  
	}, 'json');
  });
  
  // klik na .prikazineodobrentekst dugme koji prikazuje podnaslov slike da li ima mapu, youtube snimak ako je dodat i sam tekst i nudi opciju za odobravanje teksta
  $('body').on('click', '.prikazineodobrentekst', function(e){  
    $(this).bind('click', false); //zabrani klik na ovo dugme
    var id = $(this).attr('id_teksta'); // uzmi iz atributa dugmeta .prikazineodobrentekst id_teksta
	var podnaslov = $(this).attr('podnaslov'); // uzmi iz atributa dugmeta .prikazineodobrentekst podnaslov
	var slike = $(this).attr('slike'); // uzmi iz atributa dugmeta .prikazineodobrentekst slike(ima/nema tj 1/0)
	var yt = $(this).attr('yt');  // uzmi iz atributa dugmeta .prikazineodobrentekst yt video id ako ima tj. ako nije = 0
	var mapa = $(this).attr('mapa'); // da li ima mapu (1/0)
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prikazineodobrentekst'; // zovi metod koji vraca samo tekst posto je sve ostalo vec uzeto iz baze i ili je prikazano ili je u attributima dugmeta .prikazineodobrentekst(podnaslov, id_teksta i slike(bull))
	//alert(baseurl);
	$("#naslov"+id).addClass("crvenasenka"); // daj naslovu teksta crvenu senku posto je sad tekst aktivan
	postData = {
	  id: id // u model tj kontroler se salje id teksta koji je izvucen iz id_teksta atributa dugmeta .prikazineodobrentekst
	};
	$.post(url, postData, function(o){ // salji AJAX
	  console.log(o);
	  var content = '';
	  content += '<div id="samotekst'+id+'" class="samotekst">'; // div u kom se prikazuju slike podnaslov i tekst
	  content += '<div class="text-right"><a href="#" id="xneodobrenitekst" idteksta="'+id+'"  class="crvenasenka lead text-danger">X</a></div>';
	  if(slike == 1){ // ako ima slike prikazi ih
	    content += '<br>'; 
	    content += '<img id="slikaodobravanje1" class="slikaodobravanje img-rounded" src="'+baseurl+'images/tekstovi/'+id+'/1.jpg">';
		content += '<img id="slikaodobravanje2" class="slikaodobravanje img-rounded" src="'+baseurl+'images/tekstovi/'+id+'/2.jpg">';
	 }else{ // ako nema slika samo udari <br> 
	    content += '<br>'; 
	  }
	  
	  if(mapa != 0){ // ako ima dodatu mapu prikazi poruku da ima mapu
	    content += '<p id="mapaprovera" class="text-success"><bold>googlemap: </bold><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></p>';
	  }
	  var displaywidth = $(window).width();
	  if(yt != 0){ // ako ima yt link tj yt id embed-uj YT video, u zavisnosti od velicine ekrana prikazi veci ili manji player
	    //alert(displaywidth);
	    if(displaywidth > 800){ // ako je ekran veci od 800 px
	      content += '<div id="prikaziytvideo">';
	      content += '<iframe width="420" height="315" src="http://www.youtube.com/embed/'+yt+'?autoplay=0" allowfullscreen></iframe>';
		  content += '</div>';
		}else{ // ako je ekran maji od 800 px
		  content += '<div id="prikaziytvideo">';
	      content += '<iframe width="220" height="140" src="http://www.youtube.com/embed/'+yt+'?autoplay=0" allowfullscreen></iframe>';
		  content += '</div>';
		}
	  }
	  content += '<h4 class="h4tekstzaodobravanje">'+podnaslov+'</h4>'; // prikazi podnaslov
	  content += '<p class="ptekstzaodobravanje">'+o[0][0].tekst+'</p><br>'; // prikazi tekst
	  
	  content += '<div class="odobriobrisi btn-group">';
	  content += '<button type="button" id="odobritekst'+id+'" idteksta="'+id+'" class="odobritekst btn btn-success btn-xs" >Odobri tekst</button>';
	  content += '<button type="button" id="obrisitekst'+id+'" idteksta="'+id+'" slike="'+slike+'" googlemap="'+mapa+'" class="obrisitekst btn btn-danger btn-xs" >Obrisi tekst</button>';
	  content += '</div>';
	  
	  content += '</div>'; // kraj div-a .samotekst
	  $(content).insertAfter('#'+id).hide().slideDown('500'); // dodaj posle div-a u kom su naslov, autor, datum i dugme za prikaz teksta
	}, 'json');	
  });
  
  // klik na button .odobritekst tj odobravanje nekog teksta
  $('body').on('click', '.odobritekst', function(e){
    $(this).bind('click', false); //zabrani klik na ovo dugme
    e.preventDefault();
	var id = $(this).attr('idteksta');
	//alert('odbravanje teksta'+id);
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/odobritekst'; //
	postData = {
	  id: id
	};
	$.post(url, postData, function(o){ // salji AJAX metodu odobritekst() koji UPDATE-uje kolonu odobren tekst tabele i menja je iz 0 u 1
	  console.log(o);
	  if(o == true){ // ako je model tj kontroler vratio true tj izvrsen je UPDATE
		$('#prikazineodobrentekst'+id).remove(); // ukloni dugme ispod naslova za prikaz teksta, skini crvenu senku i crveni font naslovu i daj mu zeleni font i zelenu senku i iza stavi glyphicon tj shtikliraj naslov
		$('#naslov'+id).removeClass('text-danger crvenasenka').addClass('text-success zelenasenka')
		               .append('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>');
	  }
	}, 'json');	
  });
  
  //klik na .obrisitekst button tj brisanje neodobrenog teksta
  $('body').on('click', '.obrisitekst', function(e){
    e.preventDefault();
	var id = $(this).attr('idteksta'); // uzmi iz dugmeta za brisanje id, i proveri da li ima slike i googlemap i salji u kontroler
	var slike = $(this).attr('slike');
	var mapa = $(this).attr('googlemap'); 
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/obrisitekst'; 
	//alert('brisanje teksta: '+id);
	postData = {
	  id: id,
	  slike: slike,
	  mapa: mapa
	};
	if(confirm("Da Li Ste Sigurni Da Želite Da Obrišete Ovaj Tekst?")){//izbaci confirm user-u da potvrdi brisanje
	  $.post(url, postData, function(o){ // salji AJAX u kontroler
	    console.log(o);
		if(o.rezultat == 1){ // ako obrise tj kontroler vrati 1
		  alert('Tekst je upešno obrisan!');
		  $('#samotekst'+id).fadeOut(1000, function(){ // ukloni divove koji su prikazivali naslov, tekst i slicno
		    $('#'+id).slideUp(1000, function(){
			  $('#'+id).remove();
			});
			$('#samotekst'+id).remove();
		  });
		}else{// ako ne obrise tj kontroler vrati 0
		  alert('Došlo je do greške pokusajte ponovo!'); // izbaci alert
		}
	  }, 'json');
    }else{ // ako user klikne 'No' u confirmu
        return false;
    } 	  
  });
  
  
  //klik na #cancelxneodobreni ako se odustane od odobravanja tekstova u adminpanelu
  $('body').on('click', '#cancelxneodobreni', function(e){
    e.preventDefault();
	$('#kontrolatekstova').slideUp('slow', function(){
	  $('#kontrolatekstova').remove();  
	  $('#neodobrenitekstovi').removeClass('senka');
	});
	$('#neodobrenitekstovidugme').prop('disabled', false);	
  });
  
  // klik #xneodobrenitekst tj zatvori div koji prikazuje slike i tekst teksta koji ceka na odobrenje
  $('body').on('click', '#xneodobrenitekst', function(e){
    e.preventDefault();
	var id = $(this).attr('idteksta');
	$("#naslov"+id).removeClass("crvenasenka");
	$('#samotekst'+id).slideUp('slow', function(){
	  $('#samotekst'+id).remove();
      $('#prikazineodobrentekst'+id).unbind('click', false); 	  
	});
  });
  
  //-------------------------------------------------------------------------------------------------------
  
  // ODOBRENI TEKSTOVI
  
  //klik na dugme #odobrenitekstovidugme u admin panelu za prikaz svih odobrenih tekstova iz baze
  $('#odobrenitekstovidugme').on('click', function(){
    //alert('odobrenitekstovi');
    $(this).prop('disabled', true); // disable-uj dugme #odobrenitekstovidugme
    var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prikaziodobrenetekstove'; // zovi metod koji vraca podatke odobrenih tekstova osim teksta
	$('#odobrenitekstovi').addClass('senka'); // dodaj senka klasu div-u na koji ce se zakaciti prikaz odobrenih tekstova
	postData = {
	  limit: rezpostr,
	  offset: 0
	};
	
	$.post(url, postData, function(o){ // Salji AJAX metodu prikaziodobrenetekstove() koji zove istoimeni metod u modelu i vadi sve osim samog teksta tekstova koji su odobreni iz tekst tabele 
	  console.log(o); // u objektu su podatci teksta osim teksta samog i ukupan broj neodobrenih tekstova u bazi zbog paginacije
	  var total = o[1]; // broj neodobrenih tekstova u bazi
	  var content = '';
      content += '<div id="odobreni">';
	  content += '<div id="odobrenitekstovispoljni">';
	  content += '<div class="text-right"><a href="#" id="cancelxodobreni"  class="lead text-danger">cancelX</a></div>';
	  content += '<div id="odobrenitekstoviinner">';
      for(var i = 0; i < o[0].length; i++){ // iteriraj po array-u koji je stigao iz kontrolera i prikazuj naslov, id, rubriku, autora i datum teksta
	    content += '<div id="odobreni'+o[0][i].id_teksta+'">';
		if(o[0][i].glavni == 1){
		  content += '<h2  id="odobreninaslov'+o[0][i].id_teksta+'" class="text-warning">Glavni Tekst: '+o[0][i].naslov+'</h2>';
		}else{
	      content += '<h3  id="odobreninaslov'+o[0][i].id_teksta+'" class="text-success">Naslov: '+o[0][i].naslov+'</h3>';
		}
		content += '<p class="text-info"><strong>id: </strong><em>'+o[0][i].id_teksta+'</em>,<strong> Rubrika: </strong><em>'+o[0][i].rubrika+'</em>, <strong>Autor:</strong> <em>'+o[0][i].username+'</em>, <strong>Datum:</strong> <em>'+o[0][i].datum+'</em></p>';
		content += '<button type="button" id="prikaziodobrentekst'+o[0][i].id_teksta+'"  class="prikaziodobrentekst btn btn-success btn-xs" id_teksta="'+o[0][i].id_teksta+'" glavni="'+o[0][i].glavni+'" podnaslov="'+o[0][i].podnaslov+'" mapa="'+o[0][i].googlemap+'" slike="'+o[0][i].slike+'" yt="'+o[0][i].yt+'" komentar="'+o[0][i].komentar+'">prikazi tekst</button>';
	    content += '</div><hr>'; // kraj div-a #id_teksta koji prikazuje naslov i osnovne podatke i dugme prikazi tekst 
	 }	
	  
	  content += '</div>'; // kraj div-a #odobrenitekstoviinner
	  content += '</div>'; // kraj div-a #odobrenitekstovispoljni
      content += '<div id="paginacijaodobrenidiv" class="light-theme"></div><br><br>';
	  content += '</div>';//kraj div-a #odobreni
      $(content).insertAfter('#odobrenitekstovi').hide().slideDown('500');
	  
	  if(o[1] > rezpostr){ // ako vrati vise rezultata nego sto je dozvoljeno po stranici generisi linkove za paginaciju    
		//console.log(paginacijaIme);
		brstranica = Math.ceil(total / rezpostr);
		console.log('brstranica: '+brstranica);
		var listalinkova = '';
		for(var p = 1; p <= brstranica; p++){
		  listalinkova += '<a href="'+p+'" class="paginacijaodobrenilink';
		  if(p == 1){
		    listalinkova += ' currentodobreni'; // prvom linku dodaj klasu current
		  }
		  listalinkova += '">'+p+'</a>';
		}
		$('#paginacijaodobrenidiv').html(listalinkova);
	  }
	}, 'json');
	
  });
  
  // ako link za paginaciju odobrenih tekstova ima klasu currentodobreni tj user je na toj stranici i mis udje na njega zabrani klik na njega
  $('body').on('mouseenter', '.paginacijaodobrenilink', function(e){
    if($(this).hasClass('currentodobreni')){
	  $(this).bind('click', false);
	}
  });
  // ako link za paginaciju odobrenih tekstova ima klasu currentodobreni tj user je na toj stranici i mis izadje sa njega dozvoli opet klik na njega
  $('body').on('mouseleave', '.paginacijaodobrenilink', function(e){
    if($(this).hasClass('currentodobreni')){
	  $(this).unbind('click', false); 
	}
  });
  
  // klik na link za paginaciju
  $('body').on('click', '.paginacijaodobrenilink', function(e){ 
    e.preventDefault();	
    $('html, body').animate({ scrollTop: $('#odobrenitekstovi').offset().top}, 'fast'); // skroltop na vrh div-a #odobrenitekstovi
    
	$('.currentodobreni').removeClass('currentodobreni'); // linku koji je bio aktivan tj imao klasu current skini istu
	$(this).addClass('currentodobreni'); // dodaj klasu current linku na koji je kliknuto
	var baseurl = $('#baseurl').text();//<p>#baseurl je skriveni paragraf koji echo-uje baseurl()
	var url = baseurl+'internetnovine/prikaziodobrenetekstove'; // zovi metod  
	var cur_page = $(this).attr('href'); // uzmi vrednost linka koji je kliknut
	var skip = ((cur_page - 1) * rezpostr); // napravi offset
	postData = {
	  limit: rezpostr,
	  offset: skip
	};
	
	$.post(url, postData, function(o){ // salji AJAX u kontroler
	  console.log(o);
	  var total = o[1];
	  var content = '';
	  $('#odobrenitekstovispoljni').html(''); // isprazni div #odobrenitekstovispoljni, pa ga opet nacrtaj sa novim pdatcima stiglim iz kontrolera tj modela
	  content += '<div class="text-right"><a href="#" id="cancelxodobreni"  class="lead text-danger">cancelX</a></div>';
      content += '<div id="odobrenitekstoviinner">';
      for(var i = 0; i < o[0].length; i++){ 
	    content += '<div id="odobreni'+o[0][i].id_teksta+'">';
	    if(o[0][i].glavni == 1){
		  content += '<h2  id="odobreninaslov'+o[0][i].id_teksta+'" class="text-warning">Glavni Tekst: '+o[0][i].naslov+'</h2>';
		}else{
	      content += '<h3  id="odobreninaslov'+o[0][i].id_teksta+'" class="text-success">Naslov: '+o[0][i].naslov+'</h3>';
		}
		content += '<p class="text-info"><strong>id: </strong><em>'+o[0][i].id_teksta+'</em>,<strong> Rubrika: </strong><em>'+o[0][i].rubrika+'</em>, <strong>Autor:</strong> <em>'+o[0][i].username+'</em>, <strong>Datum:</strong> <em>'+o[0][i].datum+'</em></p>';
		content += '<button type="button" id="prikaziodobrentekst'+o[0][i].id_teksta+'" class="prikaziodobrentekst btn btn-success btn-xs" id_teksta="'+o[0][i].id_teksta+'" glavni="'+o[0][i].glavni+'" podnaslov="'+o[0][i].podnaslov+'" mapa="'+o[0][i].googlemap+'" slike="'+o[0][i].slike+'" yt="'+o[0][i].yt+'" komentar="'+o[0][i].komentar+'">prikazi tekst</button>';
	    content += '</div><hr>'; // kraj div-a #id_teksta koji prikazuje naslov i osnovne podatke i dugme prikazi tekst 
	  }	
	  content += '</div>';//kraj div-a #tekstovi
	  $('#odobrenitekstovispoljni').html(content); // ubaci content u div #odobrenitekstovispoljni koji je na pocetku $.post-a ispraznjen
	  
	}, 'json');
  });
  
  // klik na .prikaziodobrentekst dugme koji prikazuje podnaslov slike da li ima mapu, youtube snimak ako je dodat i sam tekst i nudi opciju za zabranu teksta
  $('body').on('click', '.prikaziodobrentekst', function(e){
    $(this).bind('click', false); //zabrani klik na ovo dugme
    var id = $(this).attr('id_teksta'); // uzmi iz atributa dugmeta .prikaziodobrentekst id_teksta
	var glavni = $(this).attr('glavni'); // proveri da li je tekst glavni tekst
	var podnaslov = $(this).attr('podnaslov'); // uzmi iz atributa dugmeta .prikaziodobrentekst podnaslov
	var slike = $(this).attr('slike'); // uzmi iz atributa dugmeta .prikaziodobrentekst slike(ima/nema tj 1/0)
	var yt = $(this).attr('yt');  // uzmi iz atributa dugmeta .prikaziodobrentekst yt video id ako ima tj. ako nije = 0
	var mapa = $(this).attr('mapa'); // da li ima mapu (1/0)
	var komentar = $(this).attr('komentar'); // da li ima komentare(ovde je broj komentara tako da ako je 0 nema komentara ako je br veci od 0 onda ima komentara)
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prikazineodobrentekst'; // zovi metod koji vraca samo tekst posto je sve ostalo vec uzeto iz baze i ili je prikazano ili je u attributima dugmeta .prikaziodobrentekst(podnaslov, id_teksta i slike(bull)), pozivam opet prikazineodobrenitekst() metod posto radi posao da ne bih pravio isti as drugim naslovom
	//alert(url+' ID: '+id+' PODNASLOV: '+podnaslov+' SLIKE: '+slike+' YT: '+yt+' MAPA: '+mapa);
	if(glavni == 1){ // ako je tekst glavni tekst
	  $("#odobreninaslov"+id).addClass("zlatnasenka");// dodaj naslovu zlatnu senku
	}else{
	  $("#odobreninaslov"+id).addClass("zelenasenka"); // daj naslovu teksta zelenu senku posto je sad tekst aktivan
	}
	postData = {
	  id: id // u model tj kontroler se salje id teksta koji je izvucen iz id_teksta atributa dugmeta .prikaziodobrentekst
	};
	$.post(url, postData, function(o){ // salji AJAX
	  console.log(o);
	  var content = '';
	  content += '<div id="samoodobrenitekst'+id+'" class="samotekst">'; // div u kom se prikazuju slike podnaslov i tekst
	  content += '<div class="text-right"><a href="#" id="xodobrenitekst'+id+'" glavni="'+glavni+'" idteksta="'+id+'" class="xodobrenitekst zelenasenka lead text-success">X</a></div>';
	  if(slike == 1){ // ako ima slike prikazi ih
	    content += '<br>'; 
	    content += '<img id="slikazabrana1" class="slikaodobravanje img-rounded" src="'+baseurl+'images/tekstovi/'+id+'/1.jpg">';
		content += '<img id="slikazabrana2" class="slikaodobravanje img-rounded" src="'+baseurl+'images/tekstovi/'+id+'/2.jpg">';
	 }else{ // ako nema slika samo udari <br> 
	    content += '<br>'; 
	  }
	  
	  if(mapa != 0){ // ako ima dodatu mapu prikazi poruku da ima mapu
	    content += '<p id="mapaprovera" class="text-success"><bold>googlemap: </bold><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></p>';
	  }
	  var displaywidth = $(window).width();
	  if(yt != 0){ // ako ima yt link tj yt id embed-uj YT video, u zavisnosti od velicine ekrana prikazi veci ili manji player
	    //alert(displaywidth);
	    if(displaywidth > 800){ // ako je ekran veci od 800 px
	      content += '<div id="prikaziytvideo">';
	      content += '<iframe width="420" height="315" src="http://www.youtube.com/embed/'+yt+'?autoplay=0" allowfullscreen></iframe>';
		  content += '</div>';
		}else{ // ako je ekran maji od 800 px
		  content += '<div id="prikaziytvideo">';
	      content += '<iframe width="220" height="140" src="http://www.youtube.com/embed/'+yt+'?autoplay=0" allowfullscreen></iframe>';
		  content += '</div>';
		}
	  }
	  content += '<h4 class="h4tekstzaodobravanje">'+podnaslov+'</h4>'; // prikazi podnaslov
	  content += '<p class="ptekstzaodobravanje">'+o[0][0].tekst+'</p><br>'; // prikazi tekst
	  if(glavni == 1){ // ako je tekst glavni ne moze biti zabranjen dok se neki drugi tekst ne proglasi za glavni
	    content += '<div class="zabraniproglasiglavni btn-group">';
	    if(komentar > 0){ // ako tekst ima komentare dodaj dugme za prikaz komentara i njihovo eventualno brisanje
		  content += '<button type="button" id="komentariteksta'+id+'" idteksta="'+id+'" class="komentariteksta btn btn-info btn-xs" >Komentari</button>';
		}
	    content += '<br><p class="text-danger text-center">Ovo je glavni tekst, ako zelite da ga zabranite prvo morate neki drugi tekst proglasiti za Glavni!</p>';
	    content += '</div>';
	  }else{ // ako nije glavni ima dugme za zabranu, za proglasavanje teksta glavnim i ako ima komentare za pregled komentara 
	    content += '<div class="zabraniproglasiglavni btn-group">';
	    content += '<button type="button" id="zabranitekst'+id+'" idteksta="'+id+'" class="zabranitekst btn btn-danger btn-xs" >Zabrani tekst</button>';
	    content += '<button type="button" id="proglasiglavnim'+id+'" idteksta="'+id+'" class="proglasiglavnim btn btn-warning btn-xs" >Glavni tekst</button>';
	    if(komentar > 0){ // ako tekst ima komentare dodaj dugme za prikaz komentara i njihovo eventualno brisanje
		  content += '<button type="button" id="komentariteksta'+id+'" idteksta="'+id+'" class="komentariteksta btn btn-info btn-xs" >Komentari</button>';
		}
		content += '</div>';
	  }
	  content += '</div>'; // kraj div-a .samotekst
	  $(content).insertAfter('#odobreni'+id).hide().slideDown('500'); // dodaj posle div-a u kom su naslov, autor, datum i dugme za prikaz teksta
	}, 'json');
  });  
  
  // klik na button .zabrani tj zabrana nekog teksta
  $('body').on('click', '.zabranitekst', function(e){
    $(this).bind('click', false); //zabrani klik na ovo dugme
    e.preventDefault();
	var id = $(this).attr('idteksta');
	//alert('odbravanje teksta'+id);
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/zabranitekst'; //
	postData = {
	  id: id
	};
	$.post(url, postData, function(o){ // salji AJAX metodu zabrani() koji UPDATE-uje kolonu odobren tekst tabele i menja je iz 1 u 0
	  console.log(o);
	  if(o == true){ // ako je model tj kontroler vratio true tj izvrsen je UPDATE
		$('#prikaziodobrentekst'+id).remove(); // ukloni dugme ispod naslova za prikaz teksta, skini zelenu senku i zeleni font naslovu i daj mu crveni font i crvenu senku i iza stavi glyphicon tj pored naslova znak upozorenja
		$('#odobreninaslov'+id).removeClass('text-success zelenasenka').addClass('text-danger crvenasenka')
		               .append(' '+'<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>');
	  }
	}, 'json');	
  });
  
  // klik na button .proglasiglavnim tj proglasavanje teksta za glavni
  $('body').on('click', '.proglasiglavnim', function(e){
	$(this).prop('disabled', true);//zabrani klik na dugme
	e.preventDefault();
	var id = $(this).attr('idteksta');
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/proglasiglavni';
	postData = {
	  id: id
	};
	$.post(url, postData, function(o){ //kontroler vraca ture ili false
	  console.log(o);
	  $('h2.text-warning').removeClass('text-warning').addClass('text-success'); //naslovu koji je prikazivao dosadasnji glavni tekst skini zuta slova i daj mu zelena
	  $('.zlatnasenka').removeClass('zlatnasenka').addClass('zelenasenka'); // isto to sa senkom
	  $('#odobreninaslov'+id).addClass('text-warning zlatnasenka'); // tekstu koji je sad glavni daj zuta slova i zutu senku
	  $('#zabranitekst'+id).remove(); // ukloni dugmad za zabranu teksta, proglasavanje teksta glavnim i X za zatvaranje diva koji prikazuje tekst
	  $('#proglasiglavnim'+id).remove();
	  $('#xodobrenitekst'+id).remove();
	}, 'json');	
  });
  
  
  // klik na dugme .komentariteksta u odobrenim tekstovima u adminpanelu koje ce prikazati komentare (dugme postoji samo ako tekst ima komentare)
  $('body').on('click', '.komentariteksta', function(e){
    $(this).prop('disabled', true);//zabrani klik na dugme
	e.preventDefault();
	var id = $(this).attr('idteksta');
	//alert(id);
	postData = {
	  id: id
	};
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/svikomentariadminpanel';
	$.post(url, postData, function(o){ //kontroler vraca ture ili false
	  console.log(o);
	  var brkomentara = o[0].length;
	  var content = '';
	  content += '<div id="svikomentariadminpanel'+id+'" class="svikomentariadminpanel">';
	  content += '<p>Komentari ('+brkomentara+')</p>';
	  content += '<div class="text-right"><a href="#" id="xsvikomentari'+id+'" idteksta="'+id+'" class="xsvikomentari zelenasenka lead text-success">X</a></div>';
	  for(var i = 0; i < o[0].length; i++){
	    var godina = o[0][i].datum_komentara.substring(0, 4); // izvuci godinu
		var mesec = o[0][i].datum_komentara.substring(5, 7); // izvuci mesec
		var dan = o[0][i].datum_komentara.substring(8, 10); // izvuci dan
		var sati = o[0][i].datum_komentara.substring(11, 19); // // izvuci sate minute i sekunde
		var datum = dan+'/'+mesec+'/'+godina+'  '+sati+' h'; // napravi novi datum
		content += '<div id="komentar'+o[0][i].id_komentara+'" class="jedankomentaradminpanel">'; // div koji prikazuje jedan komentar i nudi link za njegovo brisanje
		content += '<div class="malidatum text-right"><a href="#" id="obrisikomentar'+o[0][i].id_komentara+'" id_komentara="'+o[0][i].id_komentara+'" id_teksta="'+o[0][i].id_teksta+'" class="obrisikomentar crvenasenka text-danger">Obriši Komentar</a></div>';
		content += '<br><p class="text-info"><strong>Komentar: </strong>'+o[0][i].komentar+'</p>';
		content += '<p class="malidatum text-info"><strong>Komentator: </strong>'+o[0][i].ime_komentatora+', <strong>datum: </strong>'+datum+'</p>';
		content += '</div>';//kraj div-a #komentar+id
	  } 
	  content += '<br></div>';//kraj div-a svikomentariadminpanel+id
	  $(content).appendTo('#samoodobrenitekst'+id); // nakaci na div koji prikazuje odobren tekst ciji su ovo komentari u adminpanelu
	}, 'json');	
  });
  
  //klik na link 'obrisi komentar' u adminpanelu ispo odobrenog teksta
  $('body').on('click', '.obrisikomentar', function(e){
    e.preventDefault();
	var id_komentara = $(this).attr('id_komentara'); //uzmi id komentara
	var id_teksta = $(this).attr('id_teksta'); // uzmi id teksta
	//alert(id_komentara);
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/obrisikomentar';
	postData = { //salji u kontroler id-eve komentara i teksta posto iz tabele 'komentari' brise red u kom je komentar u tabeli 'tekst' smanjuje kolonu 'komentar' za 1 
	  id_komentara: id_komentara,
	  id_teksta: id_teksta
	};
	$.post(url, postData, function(o){ //kontroler vraca ture ili false
	  console.log(o);
	  if(o == 1){ // ako je obrisao komentar
	    alert('Komentar je uspešno Obrisan!');
		$('#komentar'+id_komentara).slideUp('slow', function(){ // ukloni div koji ga je prikazivao
		  $('#komentar'+id_komentara).remove();
		});
	  }else if(o == 0){ // ako ne obrise izbaci alert
	    alert('Došlo je do greške u bazi. Pokušajte ponovo.')
	  }
	}, 'json');
  });
  
  
  
  //klik na #cancelxodobreni, zatvara div #odobreni koji prikazuje sve odobrene tekstove 
  $('body').on('click', '#cancelxodobreni', function(e){
    e.preventDefault();
	$('#odobreni').slideUp('slow', function(){
	  $('#odobreni').remove();  
	  $('#odobrenitekstovi').removeClass('senka');
	});
	$('#odobrenitekstovidugme').prop('disabled', false);
  });
  
  // klik #xodobrenitekst tj zatvori div koji prikazuje slike i tekst teksta koji je odobren i mozda ce biti zabranjen
  $('body').on('click', '.xodobrenitekst', function(e){
    e.preventDefault();
	var id = $(this).attr('idteksta'); // uzmi id odobrenog teksta
	var glavni = $(this).attr('glavni');
	$('#samoodobrenitekst'+id).slideUp('slow', function(){
	  $('#samoodobrenitekst'+id).remove();
      $('#prikaziodobrentekst'+id).unbind('click', false); 
	  if(glavni == 1){ // ako je tekst glavni skini naslovu zlatnu senku
	    $("#odobreninaslov"+id).removeClass("zlatnasenka zelenasenka");
	  }else{
	    $("#odobreninaslov"+id).removeClass("zelenasenka"); // skini naslovu teksta zelenu senku posto tekst vise nije aktivan
	  }
	});
  });
  
  // klik na link .xsvikomentari koji sklanja div koji prikazuje komentare jednog odobrenog teksta
  $('body').on('click', '.xsvikomentari', function(e){
    e.preventDefault();
	var id = $(this).attr('idteksta'); // uzmi id odobrenog teksta
	$('#svikomentariadminpanel'+id).slideUp('slow', function(){ // slideUp-uj div sa komentarima
	  $('#svikomentariadminpanel'+id).remove(); // ukloni ga
	  $('#komentariteksta'+id).prop('disabled', false); // dozvoli opet klik na dugme koje prikazuje komentare
	});
  });
  
};




































