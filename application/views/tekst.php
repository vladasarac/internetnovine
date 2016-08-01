
  <!--div #dpi se pojavljuje samo u frontend view-ovima da bi bio izmeren ekran i u zavisnosti od velicine prikazuje se -
    - div #desno u velikim ekranima sa linkovima za tekstove sa najvise komentara i sl. a za male ekrane prikazuje se button -
	- na ciji klik se slideDown-uje div sa istim podatcima kao i u velikom-->
  <div id="dpi" style="height: 1in; width: 1in; left: 100%; position: fixed; top: 100%;"></div>
  <p id="gmprovera" hidden><?=$rezultat[0][0]['googlemap'] ?></p><!--proveri da li tekst ima dodat googleMap da bi in4.js znao da li da crta mapu ili ne-->
  

<!--Prikaz jednog teksta-->
<?php  echo '<p id="baseurl" hidden>'.base_url().'</p>'; ?>
<div class="container">

  <div class="row"> 
    <div class="col-xs-8">
	  <!--ako ima slike dodate prikazi 1.jpg iz foldera za slike tog teksta a ako nema slike prikazi logo tj. newspaperlogo.jpg-->
	  <?php if($rezultat[0][0]['slike'] == 1 ){ ?>
        <img class="img-responsive img-rounded" id="glavnaslikatekst" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat[0][0]['id_teksta'] ?>/1.jpg">
      <?php }else{ ?>
	    <img class="img-responsive img-rounded" id="glavnaslikatekst" src="<?=base_url()?>images/newspaperlogo.jpg">
	  <?php } ?>

	  <h1 class="naslovtekst"><?php echo $rezultat[0][0]['naslov'] ?></h1><!--prikazi naslov teksta-->
	  
	  <hr>
	    <div class="text-info bg-info"><!--div sa slikom autora i podatcima teksta(autor, rubrika i datum)-->
	      <img class="slikaautoratekst" src="<?=base_url()?>images/autori/<?php echo $rezultat[0][0]['id_autora'] ?>.jpg">
		  <div class="podatciiznadteksta">
		    <!-- Autor i Rubrika su linkovi ka metodu rubrikaautor() u kontroleru koji vadi sve tekstove tog autora ili te rubrike u zavisnosti od parametra $provera koji moze biti autor ili rubrika(pogledaj metod u kontroleru) -->
	        <!--<strong>Autor:</strong> <em><a href="<?//=base_url()?>internetnovine/autor/<?php //echo $rezultat[0][0]['id_autora'] ?>/<?php //echo $rezultat[0][0]['username'] ?>"><?php //echo $rezultat[0][0]['username'] ?></a></em>,<br>-->
		    <strong>Autor:</strong> <em><a href="<?=base_url()?>internetnovine/rubrikaautor/<?php echo $rezultat[0][0]['id_autora'] ?>/autor/<?php echo $rezultat[0][0]['username'] ?>"><?php echo $rezultat[0][0]['username'] ?></a></em>,<br>
			<strong> Rubrika: </strong><em><a href="<?=base_url()?>internetnovine/rubrikaautor/<?php echo $rezultat[0][0]['rubrika'] ?>/rubrika"><?php echo $rezultat[0][0]['rubrika'] ?></a></em>,<br>  
	        <strong>Datum:</strong> <em><?php echo $rezultat[0][0]['datum'] ?> h</em>
		  </div>
		</div>
	  <hr>
	    
	  <h2 class="podnaslovglavna rockwell"><?php echo $rezultat[0][0]['podnaslov'] ?></h2><br><!--podnaslov-->
	  
      <?php      
	    // ako tekst ima slike i ako u tekstu postoji string  "ovdestavisliku" koji autor treba da ubaci na mesto na kom treba da bude slika u tekstu -
		// - umestoo stringa "ovdestavisliku" ubaci HTML za sliku 2.jpg iz foldera u kom su slike teksta koji se prikazuje(pronadji ga po id-u)
	    if(($rezultat[0][0]['slike'] == 1) && (strpos($rezultat[0][0]['tekst'], 'ovdestavisliku') !== false)){
		  $rezultat[0][0]['tekst'] = str_replace("ovdestavisliku", "</p><hr><img class='img-responsive img-rounded' src='".base_url()."images/tekstovi/".$rezultat[0][0]['id_teksta']."/2.jpg'><hr><p class='text-info tekstteksta'>", $rezultat[0][0]['tekst']);
		}
		
		// ako tekst ima yt video i u teksu postoji string "ovdestaviyt" koji autor treba da ubaci na mesto na kom treba da bude video u tekstu -
		// - umestoo stringa "ovdestaviyt" ubaci HTML za yt video, u in4.js je funkcija koja menja visinu i sirinu iframe-a u zavisnosti od velicine glavne slike na pocetku teksta
		if(($rezultat[0][0]['yt'] !== 0) && (strpos($rezultat[0][0]['tekst'], 'ovdestaviyt') !== false)){
		  $rezultat[0][0]['tekst'] = str_replace("ovdestaviyt", "</p><hr><div id='prikaziytvideo'><iframe id='ytvideotekst' width='420' height='315' src='http://www.youtube.com/embed/".$rezultat[0][0]['yt']."?autoplay=0' allowfullscreen></iframe></div><hr><p class='text-info tekstteksta'>", $rezultat[0][0]['tekst']);
		}
		
		//ako tekst ima googlemap i u teksu postoji string "ovdestavigm" crta se mapa, funkcija koja poziva googleMpa API je u fajlu in4.js
		// u in4.js je funkcija koja menja visinu i sirinu div-a #map-canvastekstu kom se prikazuje mapa u zavisnosti od velicine glavne slike na pocetku teksta
		if(($rezultat[0][0]['googlemap'] !== 0) && (strpos($rezultat[0][0]['tekst'], 'ovdestavigm') !== false)){
		   echo '<p id="latituda" class="skriven">'.$rezultat[0][0]['lat'].'</p>'; // skriveni paragrafi iz kojih jQuery in4.js uzima latitudu, longitudu i zoom za crtanje googleMpa-a
		   echo '<p id="longituda" class="skriven">'.$rezultat[0][0]['long'].'</p>';
		   echo '<p id="zoom" class="skriven">'.$rezultat[0][0]['zoom'].'</p>';
		   $rezultat[0][0]['tekst'] = str_replace("ovdestavigm", "</p><hr><div id='map-canvastekst'></div><hr><p class='text-info tekstteksta'>", $rezultat[0][0]['tekst']);
		}
		
	  ?>
	  <p class="text-info tekstteksta"><?php echo $rezultat[0][0]['tekst'] ?></p>
	  
	</div><!--kraj div-a .col-xs-8-->
  </div><!--kraj div-a .row-->
  
  
  <!--forma za komentrarisanje teksta koja je sakrivena dok se ne klikne dugme #komentarisibtn-->
  <div id="divzakomentarbtn" class="col-xs-8">
    <hr>
    <button type="button" id="komentarisibtn" class="btn btn-primary btn-lg">Dodajte Komentar</button><!--dugme koje na klik prikazuje formu, hendler u in4.js-->
    <hr>
  </div>
  <div id="dodajkomentar" class="hidden col-xs-8">
    <div class="text-right"><a href="#" id="cancelxkomentara" class="lead text-danger">X</a></div>
    <p class="text-info tekstteksta">Dodajte Komentar</p>
    <!--<div class="col-xs-8">-->
	  <form class="form-horizontal">
	    <div class="form-group has-info">
          <label for="imekomentatora" class="col-sm-2 control-label">Ime</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="imekomentatora" placeholder="">
          </div>
		</div>
		<div class="form-group has-info">
          <label for="emailkomentatora" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="emailkomentatora" placeholder="">
          </div>
        </div>
		<div class="form-group has-info">
          <label for="tekstkomentara" class="col-sm-2 control-label">Komentar</label>
          <div class="col-sm-6">
            <textarea class="form-control" id="tekstkomentara"></textarea>
          </div>
        </div>
		<div class="form-group has-info"><!--stizu 2 random broja od 1 do 10 iz kontrolera i njihov zbir da bi se proverilo da li tekst ukucava covek-->
          <label for="proverakomentatora" class="col-sm-2 control-label"><?php echo $br1.' + '.$br2.' = '; ?></label>
          <div class="col-sm-2">
            <input type="text" class="form-control" id="proverakomentatora" id_teksta="<?php echo $rezultat[0][0]['id_teksta']; ?>" br1="<?php echo $br1; ?>" br2="<?php echo $br2; ?>" zbir="<?php echo $zbir; ?>">
          </div>
        </div>
		<div class="form-group has-info">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" id="dodajkomentardugme"  class="btn btn-info">Dodaj Komentar</button>
          </div>
        </div>
		<div id="erorikomentar"></div><!--prikaz errora ako forma ne prodje validaciju u in4.js-->
	  </form>
	</div><!--kraj diva col-xs-8--> 
  </div> <!--kraj div-a #dodajkomentar--> 
  
  <br>
  <div id="komentari" class="col-xs-8"><h4>Komentari:</h4></div>
  <p id="brojkomentara" id_teksta="<?php echo $rezultat[0][0]['id_teksta']; ?>" hidden><?php echo $rezultat[0][0]['komentar']; ?></p>
  
</div><!--kraj div-a .container-->

 

<?php
  
  /* echo 'Prikazi tekst sa ID:'.$id_teksta;
  //echo $mapa;
  echo '<pre>';
    print_r($rezultat);
  echo '</pre>'; */
?>
































