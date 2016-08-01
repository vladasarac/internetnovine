var Internetnovine2 = function(){
  
  
  // forma za unos novog teksta tj klik na dugme #novitekstdugme
  $('#novitekstdugme').on('click', function(){
    //alert('Pozz iz novitekst');
	$(this).prop('disabled', true);
	$('#prepravitekstdugme').prop('disabled', true);
	var baseurl = $('#baseurl').text();
	//var url = baseurl+'internetnovine/praviautora';
	$('#novitekst').addClass('senka2');
	var idautora = $('#idautor').text();
	// ako ne prodje validacija u kontroleru u skrivene paragrafe su ubaceni prethodni unosi u polja u formi pa ih ovde izvlaci i ubacuje u polja u formi(naslov, podnaslov i tekst) da user ne bi morao opet da ih ukucava 
	var naslov = $('#nasloverror').text();
	var podnaslov = $('#podnasloverror').text();
	var tekst = $('#teksterror').text();
    var content = '';
	content += '<div id="formazanovitekst" class="control-group">';
	content += '<div class="text-right">';
	content += '<a href="#" id="novitekstcancelx"  class="lead text-danger">cancelX</a>';
	content += '</div>';
	content += '<form action="'+baseurl+'internetnovine/novitekst" enctype="multipart/form-data" class="form-horizontal" method="post">'; 
    content += '<input type="hidden" name="idautora" value="'+idautora+'">';
	content += '<div class="formaNT form-group">';
    content += '<p id="kreirajformap" class="lead text-info">U polja unesite podatke i kliknite dugme NoviTekst</p>';
    content += '</div>';		
	content += '<div class="formaNT form-group has-info">';
	content += '<label for="rubrika" class="col-sm-2 control-label">Rubrika :</label>';
	content += '<div class="col-sm-6">';
	content += '<select name="rubrika" id="rubrika" class="form-control">';
    content += '<option>Politika</option>';
    content += '<option>Svet</option>';
	content += '<option>Hronika</option>';
    content += '<option>Zabava</option>';
    content += '<option>Sport</option>'; 
    content += '</select>';
	content += '</div>';
	content += '</div>';
    content += '<div class="formaNT form-group has-info">';
	content += '<label for="naslov" class="col-sm-2 control-label">Naslov :</label>';
	content += '<div class="col-sm-6">';
	content += '<input name="naslov" type="text" class="form-control" id="naslov" value="'+naslov+'"><br>'; // ako je neuspela validacija prikazi sta je autor uneo u prethodnom pokusaju
	content += '</div>';
	content += '</div>';
    content += '<div class="formaNT form-group has-info">';
	content += '<label for="podnaslov" class="col-sm-2 control-label">Podnaslov :</label>';
	content += '<div class="col-sm-6">';
	content += '<textarea name="podnaslov" class="form-control" id="podnaslov">'+podnaslov+'</textarea><br>'; // ako je neuspela validacija prikazi sta je autor uneo u prethodnom pokusaju
	content += '</div>';
	content += '</div>';
	content += '<div class="formaNT form-group has-info">';
	content += '<label for="tekst" class="col-sm-2 control-label">Tekst :</label>';
	content += '<div class="col-sm-6">';
	content += '<textarea name="tekst" class="form-control" id="tekst">'+tekst+'</textarea><br>'; // ako je neuspela validacija prikazi sta je autor uneo u prethodnom pokusaju
	content += '</div>';
	content += '</div>';
    content += '<div id="dodajslike" class="formaNT form-group has-success">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	content += '<button id="dodajsliku"  class="btn btn-info">Dodaj Slike</button>';
	content += '</div></div>';
	content += '<div id="dodajYTdiv" class="formaNT form-group has-success">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	content += '<button id="dodajYT"  class="btn btn-info">Dodaj YouTube</button>';
	content += '</div></div>';
	
	content += '<div id="dodajmapu" class="formaNT form-group has-success">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	content += '<button id="dodajmapudugme"  class="btn btn-info">Dodaj Mapu</button>';
	content += '</div></div>';
	// div koji prikazuje google mapu i inpute za lat long i zoom level koji se popune klikom na mapu
	content += '<div id="mapspoljni" class="skriven col-sm-offset-2 col-sm-8">';
	content += '<a href="#" id="cancelxmape"  class="lead text-danger">cancelX</a>';
	content += '<div id="map-canvas" class="form-group "></div>'; // div u kom se crta googlemap-a
	content += '<div class="form-group has-info">';
	content += '<label for="latituda" class="col-sm-2 control-label">Latituda :</label>';
	content += '<div class="col-sm-4">';
	content += '<input name="latituda" type="text" class="form-control" id="latituda"><br>';
	content += '</div>';
	content += '</div>';
	content += '<div class="form-group has-info">';
	content += '<label for="longituda" class="col-sm-2 control-label">Longituda :</label>';
	content += '<div class="col-sm-4">';
	content += '<input name="longituda" type="text" class="form-control" id="longituda"><br>';
	content += '</div>';
	content += '</div>';
	content += '<div class="form-group has-info">';
	content += '<label for="zoom" class="col-sm-2 control-label">Zoom :</label>';
	content += '<div class="col-sm-2">';
	content += '<input name="zoom" type="text" class="form-control" id="zoom"><br>';
	content += '</div>';
	content += '</div>';
	content += '</div>';
	
    content += '<div class="formaNT form-group">';
    content += '<div class="col-sm-offset-2 col-sm-10">';
	content += '<button type="submit" id="napravitekst"  class="btn btn-success">NoviTekst</button>';
	content += '</div><br></div><br>'; 	
	content += '<div id="errorinovitekst"></div><br>';
	
		
	content += '</form></div>';
	$(content).insertAfter('#novitekst').hide().slideDown('500');
  });
  
  //klik na dugme #dodajsliku u formi za novi tekst koji prikazuje 2 inputa za upload file-a
  $('body').on('click', '#dodajsliku', function(e){
    e.preventDefault();
	$(this).prop('disabled', true);
    //alert('changed');
	var content = '';
	content += '<div id="dodavanjeslika" class="formaNT">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	content += '<a href="#" id="cancelxslike"  class="lead text-danger">cancelX</a>';
	content += '</div>';
	content += '<div class="form-group has-info">'; // dodaj prvu sliku
	content += '<label for="tekstslika1" class="col-sm-2 control-label">Slika1: '+' '+'</label>';
	content += '<div class="col-sm-6">';
	content += '<input type="file" name="tekstslika1" id="tekstslika1"><br>';
    content += '</div>';
	content += '</div>';
	content += '<div class="form-group has-info">'; // dodaj drugu sliku
	content += '<label for="tekstslika2" class="col-sm-2 control-label">Slika2: '+' '+'</label>';
	content += '<div class="col-sm-6">';
	content += '<input type="file" name="tekstslika2" id="tekstslika2"><br>';
    content += '</div>';
	content += '</div></div>';
	$(content).insertAfter('#dodajslike').hide().slideDown('500');
  });
  
   // klik na button #cancelxslike ako se odustane od unosa slike
  $('body').on('click', '#cancelxslike', function(e){
    e.preventDefault();
	$('#dodajsliku').prop('disabled', false);
	$('#dodavanjeslika').slideUp('slow', function(){
	  $('#dodavanjeslika').remove();  
	});
  });
  
  //-------------------------------------------------------------------------------------------------------------------------

  //klik na dugme #dodajYT u formi za novi tekst koji prikazuje input za YT link
  $('body').on('click', '#dodajYT', function(e){
    //alert('pozz iz dodaj YT');
    e.preventDefault();
	$(this).prop('disabled', true);
	var content = '';
	content += '<div id="dodavanjeYTlinka" class="formaNT">';
	content += '<div class="col-sm-offset-2 col-sm-10">';
	content += '<a href="#" id="cancelxyt"  class="lead text-danger">cancelX</a>';
	content += '</div>';
	content += '<div class="form-group has-info">';
	content += '<label for="YTlink" class="col-sm-2 control-label">YouTube Link :</label>';
	content += '<div class="col-sm-6">';
	content += '<input name="YTlink" type="text" class="form-control" id="YTlink"><br>'; 
	content += '</div>';
	content += '</div>';
	content += '</div>';
	$(content).insertAfter('#dodajYTdiv').hide().slideDown('500');
  
  });
  
   // klik na button #cancelxyt ako se odustane od unosa youtube linka
  $('body').on('click', '#cancelxyt', function(e){
    e.preventDefault();
	$('#dodajYT').prop('disabled', false);
	$('#dodavanjeYTlinka').slideUp('slow', function(){
	  $('#dodavanjeYTlinka').remove();  
	});
  });
  
  
  //-------------------------------------------------------------------------------------------------------------------------
  // klik na #zatvoriautorpanelerrorporuku u adminpanelu koji prikazuje error vracen od kontrolera , trenutno ne postoji ovaj cancelX
  $('body').on('click', '#zatvoriautorpanelerrorporuku', function(e){
    e.preventDefault();
	$('#errori').slideUp('slow', function(){
	  $(this).remove();  
	  var url = $('#baseurl').text()+'internetnovine/autorpanel'; // radi redirekciju na url adminpanel, da ne bi slao post u kontroler na refresh
      location.replace(url);
	});
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
	  $('#latituda').val(event.latLng.lat()); // popuni input za latitiudu
	  $('#longituda').val(event.latLng.lng()); // popuni input za longitudu
	  $('#zoom').val(map.getZoom()); // popuni input za zoom level
    });
   }
   //-------------------------------------------------------------------------------------------------------------------------
  // klik na button #dodajmapu koji prikazuje div sa googlemap-om na koju autor kline i sacuva koordinate i zoom level
  $('body').on('click', '#dodajmapudugme', function(e){
    $(this).prop('disabled', true);
    e.preventDefault();
	$('#mapspoljni').removeClass('skriven'); // otkrij div #mapspoljni u kom je div #map-canvas u kom se prikazuje mapa
	initialize(); // pozovi funkciju koja prikazuje mapu
  });
  //-------------------------------------------------------------------------------------------------------------------------
  // klik na cancelx google mape #cancelxmape ako se odustane od unsa mape
  $('body').on('click', '#cancelxmape', function(e){
    e.preventDefault();
	$('#mapspoljni').addClass('skriven'); // sakrij opet div koji prikazuje googlemap
	$('#dodajmapudugme').prop('disabled', false); // odblokiraj dugme za prikaz google map-a
	$('#latituda').val(''); //isprazni inpute za lat, long i zoom level
	$('#longituda').val('');
	$('#zoom').val('');
  });
  //-------------------------------------------------------------------------------------------------------------------------
  
  //-------------------------------------------------------------------------------------------------------------------------
  
  //kad mis udje iznad submit buton-a forme za unos teksta validiraj da li je forma popunjena
  $('body').on('mouseenter', '#napravitekst', function(e){
	var unetinaslov = $('#naslov').val(); // uzmi vrednosti iz inputa za naslov podnaslov i tekst
	var unetipodnaslov = $('#podnaslov').val(); 
	var unetitekst = $('#tekst').val();
    var unetilink = $('#YTlink').val();
	
    if(unetinaslov.length < 1 || unetipodnaslov.length < 1 || unetitekst.length < 1){ // ako nista nije uneto u nasov podnaslov i tekst inpute -
	  $('#errorinovitekst').html('<p class="lead text-center text-danger">Pogrešno ste popunili formu, pokušajte ponovo.</p>'); // izbaci poruku upozorenja
	  $(this).bind('click', false); // zabrani klik na submit button
	}else if($('#YTlink').length && unetilink.length < 1){ // ako postoji input za ytlink a u njega nista nije uneto
	  $('#errorinovitekst').html('<p class="lead text-center text-danger">Pogrešno ste popunili formu, pokušajte ponovo.</p>'); // izbaci poruku upozorenja
	  $(this).bind('click', false); // zabrani klik na submit button  
	}else if($('#YTlink').length == 0){ // ako ne postoji input za yt link
	  $('#errorinovitekst').html(''); // obrisi poruku upozorenja 
	  $(this).unbind('click', false); // dozvoli klik na submit button
	}else if(unetinaslov.length >= 1 || unetipodnaslov.length >= 1 || unetitekst.length >= 1){ // ako je sve OK dozvoli da se klikne na button
	  $('#errorinovitekst').html(''); // obrisi poruku upozorenja 
	  $(this).unbind('click', false); // dozvoli klik na submit button
	}
  }); 
  
  //-------------------------------------------------------------------------------------------------------------------------
  
  //klik na cancel forme za unos novog teksta
  $('body').on('click', '#novitekstcancelx', function(e){
    e.preventDefault();
	$('#errori').slideUp('slow', function(){
	  $('#errori').remove();  
	});
	$('#formazanovitekst').slideUp('slow', function(){
	  $('#formazanovitekst').remove();  
	});
	
	$('#novitekstdugme').prop('disabled', false);
	$('#prepravitekstdugme').prop('disabled', false);
	$('#novitekst').removeClass('senka2');
	$('#nasloverror').text(''); // isprazni inpute ako je nesto uneto
	$('#podnasloverror').text('');
	$('#teksterror').text('');
  });
  
  
  // klik na #zatvoriuspehautorporuku u adminpanelu koji prikazuje error vracen od kontrolera
  $('body').on('click', '#zatvoriuspehautorporuku', function(e){
    e.preventDefault();
	$('#uspeh').slideUp('slow', function(){
	  $(this).remove();  
	  var url = $('#baseurl').text()+'internetnovine/autorpanel'; // radi redirekciju na url autorpanel, da ne bi slao post u kontroler na refresh
      location.replace(url);
	});
  });

};

































