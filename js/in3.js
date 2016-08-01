var Internetnovine3 = function(){
  
  // klik na dugme #prepravitekstdugme tj. prikaz tekstova ulogovanog autora koje zatim klikom na dugme -
  // - #prikazitekstzaprepravku tj 'Prepravi Tekst' moze da prepravi u formi koja se pojavi
  $('#prepravitekstdugme').on('click', function(){
    $(this).prop('disabled', true);
    $('#novitekstdugme').prop('disabled', true);
    //alert('Pozz iz prepravitekst');
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prikazitekstovejednogautora';
	$('#prepravitekst').addClass('senka2');
	var idautora = $('#idautor').text();
	var rezpostr = 10;
	//alert('url: '+url+', idautora: '+idautora+', rezpostr: '+rezpostr)
	postData = {
	  id: idautora, // u model tj kontroler se salje id autora da bi iz tabele izvukao samo njegove tekstove
	  limit: rezpostr,
	  offset: 0
	};
	$.post(url, postData, function(o){ // salji AJAX
	  console.log(o);
	  var total = o[1];
	  var content = '';
	  content += '<div id="prepravketekstova">';
	  content += '<div id="tekstovizaprepravkuspoljni">';
	  content += '<div class="text-right"><a href="#" id="cancelxtekstovizaprepravku" class="lead text-danger">cancelX</a></div>';
	  content += '<div id="tekstovizaprepravkuinner">';
	  for(var i = 0; i < o[0].length; i++){ // iteriraj po array-u koji je stigao iz kontrolera i prikazuj naslov, id, rubriku, autora i datum teksta
	    content += '<div id="tekstzaprepravku'+o[0][i].id_teksta+'">';
		if(o[0][i].odobren == 1){ // ako je tekst oddobren zeleni naslov
	      content += '<h3  id="tekstzaprepravkunaslov'+o[0][i].id_teksta+'" class="text-success">Naslov: '+o[0][i].naslov+'</h3>';
		}else if(o[0][i].odobren == 0){ // ako je neodobren crveni naslov
		  content += '<h3  id="tekstzaprepravkunaslov'+o[0][i].id_teksta+'" class="text-danger">Naslov: '+o[0][i].naslov+'</h3>';
		}
		content += '<p class="text-info"><strong>id: </strong><em>'+o[0][i].id_teksta+'</em>,<strong> Rubrika: </strong><em>'+o[0][i].rubrika+'</em>, <strong>Datum:</strong> <em>'+o[0][i].datum+'</em></p>';
		content += '<button type="button" id="prikazitekstzaprepravku'+o[0][i].id_teksta+'"  class="prikazitekstzaprepravku btn btn-info btn-xs" id_teksta="'+o[0][i].id_teksta+'" odobren="'+o[0][i].odobren+'" rubrika="'+o[0][i].rubrika+'" naslov="'+o[0][i].naslov+'" podnaslov="'+o[0][i].podnaslov+'" tekst="'+o[0][i].tekst+'" mapa="'+o[0][i].googlemap+'" slike="'+o[0][i].slike+'" yt="'+o[0][i].yt+'">Prepravi Tekst</button>';
	    content += '</div><hr>'; // kraj div-a #id_teksta koji prikazuje naslov i osnovne podatke i dugme prikazi tekst 
	 }
	  content += '</div>'; // kraj div-a #tekstovizaprepravkuinner
	  content += '</div>';// kraj div-a #tekstovizaprepravkuspoljni
	  content += '<div id="paginacijtekstoviprepravkadiv" class="light-theme"></div><br><br>';
	  content += '</div>'; // kraj div-a #prepravketekstova
      $(content).insertAfter('#prepravitekst').hide().slideDown('500');
	  
	  if(o[1] > rezpostr){ // ako vrati vise rezultata nego sto je dozvoljeno po stranici generisi linkove za paginaciju    
		//console.log(paginacijaIme);
		brstranica = Math.ceil(total / rezpostr);
		console.log('brstranica: '+brstranica);
		var listalinkova = '';
		for(var p = 1; p <= brstranica; p++){
		  listalinkova += '<a href="'+p+'" class="paginacijatekstoviprepravkalink';
		  if(p == 1){
		    listalinkova += ' currenttekstoviprepravka'; // prvom linku dodaj klasu current
		  }
		  listalinkova += '">'+p+'</a>';
		}
		$('#paginacijtekstoviprepravkadiv').html(listalinkova);
	  }
	}, 'json');
  });
  
  // ako link za paginaciju tekstova za prepravku ima klasu currenttekstoviprepravka tj user je na toj stranici i mis udje na njega zabrani klik na njega
  $('body').on('mouseenter', '.paginacijatekstoviprepravkalink', function(e){
    if($(this).hasClass('currenttekstoviprepravka')){
	  $(this).bind('click', false);
	}
  });
  // ako link za paginaciju tekstova za prepravku ima klasu currenttekstoviprepravka tj user je na toj stranici i mis izadje sa njega dozvoli opet klik na njega
  $('body').on('mouseleave', '.paginacijatekstoviprepravkalink', function(e){
    if($(this).hasClass('currenttekstoviprepravka')){
	  $(this).unbind('click', false); 
	}
  });
  
  // klik na link za paginaciju
  $('body').on('click', '.paginacijatekstoviprepravkalink', function(e){ 
    e.preventDefault();	
    $('html, body').animate({ scrollTop: $('#prepravitekst').offset().top}, 'fast'); // skroltop na vrh div-a #prepravitekst
    
	$('.currenttekstoviprepravka').removeClass('currenttekstoviprepravka'); // linku koji je bio aktivan tj imao klasu current skini istu
	$(this).addClass('currenttekstoviprepravka'); // dodaj klasu current linku na koji je kliknuto
	var rezpostr = 10;
	var idautora = $('#idautor').text();
	var baseurl = $('#baseurl').text();
	var url = baseurl+'internetnovine/prikazitekstovejednogautora';
	var cur_page = $(this).attr('href'); // uzmi vrednost linka koji je kliknut
	var skip = ((cur_page - 1) * rezpostr); // napravi offset
	postData = {
	  id: idautora, // u model tj kontroler se salje id autora da bi iz tabele izvukao samo njegove tekstove
	  limit: rezpostr,
	  offset: skip
	};
	$.post(url, postData, function(o){ // salji AJAX u kontroler
      $('#tekstovizaprepravkuspoljni').html(''); // isprazni div #tekstovizaprepravkuspoljni posto se pravi novi div #tekstovizaprepravkuinner
      var content = '';
	  content += '<div class="text-right"><a href="#" id="cancelxtekstovizaprepravku"  class="lead text-danger">cancelX</a></div>';
	  content += '<div id="tekstovizaprepravkuinner">';
	  for(var i = 0; i < o[0].length; i++){ // iteriraj po array-u koji je stigao iz kontrolera i prikazuj naslov, id, rubriku, autora i datum teksta
	    content += '<div id="tekstzaprepravku'+o[0][i].id_teksta+'">';
	    if(o[0][i].odobren == 1){ // ako je tekst oddobren zeleni naslov
	      content += '<h3  id="tekstzaprepravkunaslov'+o[0][i].id_teksta+'" class="text-success">Naslov: '+o[0][i].naslov+'</h3>';
		}else if(o[0][i].odobren == 0){ // ako je neodobren crveni naslov
		  content += '<h3  id="tekstzaprepravkunaslov'+o[0][i].id_teksta+'" class="text-danger">Naslov: '+o[0][i].naslov+'</h3>';
		}
		content += '<p class="text-info"><strong>id: </strong><em>'+o[0][i].id_teksta+'</em>,<strong> Rubrika: </strong><em>'+o[0][i].rubrika+'</em>, <strong>Datum:</strong> <em>'+o[0][i].datum+'</em></p>';
		content += '<button type="button" id="prikazitekstzaprepravku'+o[0][i].id_teksta+'"  class="prikazitekstzaprepravku btn btn-info btn-xs" id_teksta="'+o[0][i].id_teksta+'" odobren="'+o[0][i].odobren+'" rubrika="'+o[0][i].rubrika+'" naslov="'+o[0][i].naslov+'" podnaslov="'+o[0][i].podnaslov+'" tekst="'+o[0][i].tekst+'" mapa="'+o[0][i].googlemap+'" slike="'+o[0][i].slike+'" yt="'+o[0][i].yt+'">Prepravi Tekst</button>';
	    content += '</div><hr>'; // kraj div-a #id_teksta koji prikazuje naslov i osnovne podatke i dugme prikazi tekst 
	 }
	  content += '</div>'; // kraj div-a #tekstovizaprepravkuinner
	  $('#tekstovizaprepravkuspoljni').html(content); // ubaci content u div #tekstovizaprepravkuspoljni koji je na pocetku $.post-a ispraznjen
	}, 'json');
  });
  
  
  
  //-------------------------------------------------------------------------------------------------------------------------
  
  //klik na cancelx liste tekstova autora koji su prikazani posle klika na dugme #prepravitekstdugme
  $('body').on('click', '#cancelxtekstovizaprepravku', function(e){
    e.preventDefault();
	$('#errori').slideUp('slow', function(){
	  $('#errori').remove();  
	});
	$('#prepravketekstova').slideUp('slow', function(){
	  $('#prepravketekstova').remove();  
	});
	
	$('#prepravitekstdugme').prop('disabled', false);
	$('#novitekstdugme').prop('disabled', false);
	$('#prepravitekst').removeClass('senka2');
  });
  
  //-------------------------------------------------------------------------------------------------------------------------
  //prikaz forme za prepravljanje teksta tj klik na dugme .prikazitekstzaprepravku
  $('body').on('click', '.prikazitekstzaprepravku', function(e){
    $('.prikazitekstzaprepravku').prop('disabled', true); 
	var baseurl = $('#baseurl').text();
	var idautora = $('#idautor').text();
	var idteksta = $(this).attr('id_teksta'); // uzmi id teksta iz atributa dugmeta
	var odobren = $(this).attr('odobren'); // proveri da li je tekst odobren
	var rubrika = $(this).attr('rubrika'); // uzmi rubriku iz atributa dugmeta
	var naslov = $(this).attr('naslov'); // uzmi naslov teksta iz atributa dugmeta
	var podnaslov = $(this).attr('podnaslov'); // uzmi podnaslov teksta iz atributa dugmeta
	var tekst = $(this).attr('tekst'); // uzmi text teksta iz atributa dugmeta
	var slike = $(this).attr('slike'); // proveri da li ima slike
	var yt = $(this).attr('yt'); // proveri da li ima yt
	var mapa = $(this).attr('mapa'); // proveri da li ima mapu
	if(odobren == 1){ // dodaj crvenu ili zelenu senku naslovu teksta da se zna da je aktivan
	  $('#tekstzaprepravkunaslov'+idteksta).addClass("zelenasenka");
	}else{
	  $('#tekstzaprepravkunaslov'+idteksta).addClass("crvenasenka");
	}
    var content = '';
	content += '<div id="formazaprepravku" class="control-group">';
	content += '<div class="text-right">';
	content += '<a href="#" id="prepravitextcancelx" idteksta="'+idteksta+'" odobren="'+odobren+'" class="lead text-danger crvenasenka">X</a>';
	content += '</div>';
	content += '<form action="'+baseurl+'internetnovine/prepravitekst" enctype="multipart/form-data" class="form-horizontal" method="post">'; 
    content += '<input type="hidden" name="idautora" value="'+idautora+'">';
	content += '<input type="hidden" name="idtekstaprepravka" value="'+idteksta+'">';
	content += '<input type="hidden" name="stareslike" value="'+slike+'">';
	content += '<input type="hidden" name="starigm" value="'+mapa+'">';
	content += '<div class="formapoljeprepravka form-group">';
    content += '<p id="kreirajformap" class="lead text-info">U polja unesite podatke i kliknite dugme PrepraviTekst</p>';
    content += '</div>';		
	content += '<div class="formapoljeprepravka form-group has-info">';
	content += '<label for="rubrikaprepravka" class="col-sm-2 control-label">Rubrika :</label>';
	content += '<div class="col-sm-6">';
	content += '<select name="rubrikaprepravka" id="rubrikaprepravka" class="form-control">';
	//selektuj odgovarajucu rubriku
	if(rubrika == 'Politika'){
	  content += '<option selected>Politika</option>';
	}else if(rubrika != 'Politika'){
      content += '<option>Politika</option>';
	}
	if(rubrika == 'Svet'){
	  content += '<option selected>Svet</option>';
	}else if(rubrika != 'Svet'){
      content += '<option>Svet</option>';
	}
	if(rubrika == 'Hronika'){
	  content += '<option selected>Hronika</option>';
	}else if(rubrika != 'Hronika'){
	  content += '<option>Hronika</option>';
	}
	if(rubrika == 'Zabava'){
	  content += '<option selected>Zabava</option>';
	}else if(rubrika != 'Zabava'){
      content += '<option>Zabava</option>';
	}
	if(rubrika == 'Sport'){
	  content += '<option selected>Sport</option>'; 
	}else if(rubrika != 'Sport'){
      content += '<option>Sport</option>'; 
	}
    content += '</select>';
	content += '</div>';
	content += '</div>';
    content += '<div class="formapoljeprepravka form-group has-info">';
	content += '<label for="naslovprepravka" class="col-sm-2 control-label">Naslov :</label>';
	content += '<div class="col-sm-6">';
	content += '<input name="naslovprepravka" type="text" class="form-control" id="naslovprepravka" value="'+naslov+'"><br>'; // ako je neuspela validacija prikazi sta je autor uneo u prethodnom pokusaju
	content += '</div>';
	content += '</div>';
    content += '<div class="formapoljeprepravka form-group has-info">';
	content += '<label for="podnaslovprepravka" class="col-sm-2 control-label">Podnaslov :</label>';
	content += '<div class="col-sm-6">';
	content += '<textarea name="podnaslovprepravka" class="form-control" id="podnaslovprepravka">'+podnaslov+'</textarea><br>'; // ako je neuspela validacija prikazi sta je autor uneo u prethodnom pokusaju
	content += '</div>';
	content += '</div>';
	content += '<div class="formapoljeprepravka form-group has-info">';
	content += '<label for="tekstprepravka" class="col-sm-2 control-label">Tekst :</label>';
	content += '<div class="col-sm-6">';
	content += '<textarea name="tekstprepravka" class="form-control" id="tekstprepravka">'+tekst+'</textarea><br>'; // ako je neuspela validacija prikazi sta je autor uneo u prethodnom pokusaju
	content += '</div>';
	content += '</div>';
    content += '<div id="dodajslikeprepravka" class="formapoljeprepravka form-group has-success">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	if(slike == 1){ // ako tekst vec ima neke slike onda je tekst na button-u Prepravi Slike
	  content += '<button id="dodajslikuprepravka"  class="btn btn-warning">Prepravi Slike</button>';
	}else{          // ako text nema slike onda je tekst na button-u Dodaj Slike
	  content += '<button id="dodajslikuprepravka"  class="btn btn-info">Dodaj Slike</button>';
	}
	content += '</div></div>';
	content += '<div id="dodajYTdivprepravka" class="formapoljeprepravka form-group has-success">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	if(yt == 0){ // ako tekst nema YT link dodat onda je tekst na button-u Dodaj YouTube
	  content += '<button id="dodajYTprepravka" link="'+yt+'" class="btn btn-info">Dodaj YouTube</button>';
	}else{        // ako tekst vec ima YT link dodat onda je tekst na button-u Prepravi YouTube
	  content += '<button id="dodajYTprepravka" link="'+yt+'" class="btn btn-warning">Prepravi YouTube</button>';
	}
	content += '</div></div>';
	
	content += '<div id="dodajmapuprepravka" class="formapoljeprepravka form-group has-success">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	if(mapa == 1){ // ako tekst vec ima dodatu mapu onda je tekst na button-u Prepravi Mapu
	  content += '<button id="dodajmapudugmeprepravka"  class="btn btn-warning">Prepravi Mapu</button>';
	}else{         // ako tekst nema dodatu mapu onda je tekst na button-u Dodaj Mapu
	  content += '<button id="dodajmapudugmeprepravka"  class="btn btn-info">Dodaj Mapu</button>';
	}
	content += '</div></div>';
	// div koji prikazuje google mapu i inpute za lat long i zoom level koji se popune klikom na mapu
	content += '<div id="mapspoljni" class="skriven col-sm-offset-2 col-sm-8">';
	content += '<a href="#" id="cancelxmape"  class="lead text-danger">cancelX</a>';
	content += '<div id="map-canvas" class="form-group"></div>'; // div u kom se crta googlemap-a
	content += '<div class="form-group has-info">';
	content += '<label for="latitudaprepravka" class="col-sm-2 control-label">Latituda :</label>';
	content += '<div class="col-sm-4">';
	content += '<input name="latitudaprepravka" type="text" class="form-control" id="latitudaprepravka"><br>';
	content += '</div>';
	content += '</div>';
	content += '<div class="form-group has-info">';
	content += '<label for="longitudaprepravka" class="col-sm-2 control-label">Longituda :</label>';
	content += '<div class="col-sm-4">';
	content += '<input name="longitudaprepravka" type="text" class="form-control" id="longitudaprepravka"><br>';
	content += '</div>';
	content += '</div>';
	content += '<div class="form-group has-info">';
	content += '<label for="zoomprepravka" class="col-sm-2 control-label">Zoom :</label>';
	content += '<div class="col-sm-2">';
	content += '<input name="zoomprepravka" type="text" class="form-control" id="zoomprepravka"><br>';
	content += '</div>';
	content += '</div>';
	content += '</div>';
	
    content += '<div class="formapoljeprepravka form-group">';
    content += '<div class="col-sm-offset-2 col-sm-10">';
	content += '<button type="submit" id="prepravitekst"  class="btn btn-success">PrepraviTekst</button>';
	content += '</div><br></div><br>'; 	
	content += '<div id="erroritekstprepravka"></div><br>';
	
		
	content += '</form></div>';
	$(content).insertAfter('#tekstzaprepravku'+idteksta).hide().slideDown('500'); // dodaj sve ispo div-a '#tekstzaprepravku'+id
  });	
  
  //-------------------------------------------------------------------------------------------------------------------------
  // DODAVANJE div-ova ZA UNOS SLIKA, YT LINKA I GOOGLE MAP-a
  //-------------------------------------------------------------------------------------------------------------------------
  
  //klik na dugme #dodajslikuprepravka u formi za prepravljanje teksta koji prikazuje 2 inputa za upload file-a
  $('body').on('click', '#dodajslikuprepravka', function(e){
    e.preventDefault();
	$(this).prop('disabled', true);
    //alert('changed');
	var content = '';
	content += '<div id="prepravljanjeslika" class="formapoljeprepravka">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	content += '<a href="#" id="cancelxslikeprepravka"  class="lead text-danger">cancelX</a>';
	content += '</div>';
	content += '<div class="form-group has-info">'; // dodaj prvu sliku
	content += '<label for="tekstslika1prepravka" class="col-sm-2 control-label">Slika1: '+' '+'</label>';
	content += '<div class="col-sm-6">';
	content += '<input type="file" name="tekstslika1prepravka" id="tekstslika1prepravka"><br>';
    content += '</div>';
	content += '</div>';
	content += '<div class="form-group has-info">'; // dodaj drugu sliku
	content += '<label for="tekstslika2prepravka" class="col-sm-2 control-label">Slika2: '+' '+'</label>';
	content += '<div class="col-sm-6">';
	content += '<input type="file" name="tekstslika2prepravka" id="tekstslika2prepravka"><br>';
    content += '</div>';
	content += '</div></div>';
	$(content).insertAfter('#dodajslikeprepravka').hide().slideDown('500');
  });
  
  // klik na dugme #dodajYTprepravka u formi za prepravljanje teksta koji prikazuje input za unos YT id-a
  $('body').on('click', '#dodajYTprepravka', function(e){
    e.preventDefault();
	var link = $(this).attr('link'); // proveri da li tekst vec ima unet link ka yt ako ima prikazi ga u input polju
	$(this).prop('disabled', true);
	var content = '';
	content += '<div id="dodavanjeYTlinkaprepravka" class="formapoljeprepravka">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	content += '<a href="#" id="cancelxytprepravka"  class="lead text-danger">cancelX</a>';
	content += '</div>';
	content += '<div class="form-group has-info">';
	content += '<label for="YTlinkprepravka" class="col-sm-2 control-label">YouTube Link :</label>';
	content += '<div class="col-sm-6">';
	if(link != 0){
	  content += '<input name="YTlinkprepravka" type="text" value="'+link+'" class="form-control" id="YTlinkprepravka"><br>'; 
	}else{
	  content += '<input name="YTlinkprepravka" type="text" class="form-control" id="YTlinkprepravka"><br>'; 
	}
	content += '</div>';
	content += '</div>';
	content += '</div>';
	$(content).insertAfter('#dodajYTdivprepravka').hide().slideDown('500');
  });
  
  //-------------------------------------------------------------------------------------------------------------------------
  // FUNKCIJA ZA PRIKAZ GOOGLE MAP-e
  //-------------------------------------------------------------------------------------------------------------------------
  var x = 44.818611; // koordinate beograda
  var y = 20.468056;
  var center = new google.maps.LatLng(x, y);
  var map;
	  
  function initialize(){
	var mapOptions = {
	      zoom: 14,
		  center: center,
		  //mapTypeId: google.maps.MapTypeId.TERRAIN 
		  //mapTypeId: google.maps.MapTypeId.SATELLITE
		  mapTypeId: google.maps.MapTypeId.HYBRID // HYBRID je mesavina satelitskog snimka i mape tako da ima ucrtane granice imena gradova i slicno...
	};
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	
	// hendler za klik na googlemap-u	
	google.maps.event.addListener(map, 'click', function(event){ 
	  $('#latitudaprepravka').val(event.latLng.lat()); // popuni input za latitiudu
	  $('#longitudaprepravka').val(event.latLng.lng()); // popuni input za longitudu
	  $('#zoomprepravka').val(map.getZoom()); // popuni input za zoom level
    });
  }
  //-------------------------------------------------------------------------------------------------------------------------
  //klik na button #dodajmapudugmeprepravka koji prikazuje div sa googlemap-om na koju autor kline i sacuva koordinate i zoom level
  $('body').on('click', '#dodajmapudugmeprepravka', function(e){
    $(this).prop('disabled', true);
    e.preventDefault();
	$('#mapspoljni').removeClass('skriven'); // otkrij div #mapspoljni u kom je div #map-canvas u kom se prikazuje mapa
	initialize(); // pozovi funkciju koja prikazuje mapu
  });
  
  //-------------------------------------------------------------------------------------------------------------------------
  
  //kad mis udje iznad submit buton-a forme za prepravku teksta validiraj da li je forma popunjena
  $('body').on('mouseenter', '#prepravitekst', function(e){
	var unetinaslov = $('#naslovprepravka').val(); // uzmi vrednosti iz inputa za naslov podnaslov i tekst
	var unetipodnaslov = $('#podnaslovprepravka').val(); 
	var unetitekst = $('#tekstprepravka').val();
    var unetilink = $('#YTlinkprepravka').val();
	
    //if(unetinaslov.length < 1 || unetipodnaslov.length < 1 || unetitekst.length < 1){ // ako nista nije uneto u nasov podnaslov i tekst inpute -
    if(null == unetinaslov || null == unetipodnaslov || null == unetitekst){  	 
	 $('#erroritekstprepravka').html('<p class="lead text-center text-danger">Pogrešno ste popunili formu, pokušajte ponovo.</p>'); // izbaci poruku upozorenja
	  $(this).bind('click', false); // zabrani klik na submit button
	}else if($('#YTlinkprepravka').length && unetilink.length < 1){ // ako postoji input za ytlink a u njega nista nije uneto
	  $('#erroritekstprepravka').html('<p class="lead text-center text-danger">Pogrešno ste popunili formu, pokušajte ponovo.</p>'); // izbaci poruku upozorenja
	  $(this).bind('click', false); // zabrani klik na submit button  
	}else if($('#YTlinkprepravka').length == 0){ // ako ne postoji input za yt link
	  $('#erroritekstprepravka').html(''); // obrisi poruku upozorenja 
	  $(this).unbind('click', false); // dozvoli klik na submit button
	}else if(unetinaslov.length >= 1 || unetipodnaslov.length >= 1 || unetitekst.length >= 1){ // ako je sve OK dozvoli da se klikne na button
	  $('#erroritekstprepravka').html(''); // obrisi poruku upozorenja 
	  $(this).unbind('click', false); // dozvoli klik na submit button
	}
  }); 
	
  //-------------------------------------------------------------------------------------------------------------------------
  //canceli i slicno u formi za prepravljanje teksta
  
  //klik na cancelX #prepravitextcancelx tj zatvori formu za prepravljanje jednog texta
  $('body').on('click', '#prepravitextcancelx', function(e){
    e.preventDefault();
	$('.prikazitekstzaprepravku').prop('disabled', false); // dozvoli opet klik na dugmad koja prikzuju forme za prepravku textova
	$('#formazaprepravku').slideUp('slow', function(){ // sakrij div koji prikazuje formu za prepravku teksta koji je trenutno aktivan
	  $('#formazaprepravku').remove(); // ukloni taj div 
	});
	var id = $(this).attr('idteksta');
	var odobren = $(this).attr('odobren'); // proveri da li je text odobren da bi znao koju senku da skines naslovu
	if(odobren == 1){
	  $('#tekstzaprepravkunaslov'+id).removeClass("zelenasenka");
	}else{
	  $('#tekstzaprepravkunaslov'+id).removeClass("crvenasenka");
	}
  }); 
  
  // klik na button #cancelxslikeprepravka ako se odustane od unosa slike
  $('body').on('click', '#cancelxslikeprepravka', function(e){
    e.preventDefault();
	$('#dodajslikuprepravka').prop('disabled', false);
	$('#prepravljanjeslika').slideUp('slow', function(){
	  $('#prepravljanjeslika').remove();  
	});
  });
  
  // klik na button #cancelxytprepravka ako se odustane od unosa youtube linka
  $('body').on('click', '#cancelxytprepravka', function(e){
    e.preventDefault();
	$('#dodajYTprepravka').prop('disabled', false);
	$('#dodavanjeYTlinkaprepravka').slideUp('slow', function(){
	  $('#dodavanjeYTlinkaprepravka').remove();  
	});
  });  
  
  // klik na cancelx google mape #cancelxmape ako se odustane od unsa mape
  $('body').on('click', '#cancelxmape', function(e){
    e.preventDefault();
	$('#mapspoljni').addClass('skriven'); // sakrij opet div koji prikazuje googlemap
	$('#dodajmapudugmeprepravka').prop('disabled', false); // odblokiraj dugme za prikaz google map-a
	$('#latituda').val(''); //isprazni inpute za lat, long i zoom level
	$('#longituda').val('');
	$('#zoom').val('');
  });
  
  
};











































