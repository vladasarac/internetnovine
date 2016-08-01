<?php

  //echo 'Prikazi sve tekstove Autora: '.$autor;
  //echo '<br>Autor ID: '.$autorid;
  
 /*  echo '<pre>';
    print_r($rezultat);
  echo '</pre>'; */

  /* echo '<pre>';
    print_r($rezultat1);
  echo '</pre>'; */
?>

<!--fajl prikazuje sve tekstove jednog autora koji stizu iz metoda rubrikaautor() u internetnovine kontroleru-->

   <!--div #dpi se pojavljuje samo u frontend view-ovima da bi bio izmeren ekran i u zavisnosti od velicine prikazuje se -
    - div #desno u velikim ekranima sa linkovima za tekstove sa najvise komentara i sl. a za male ekrane prikazuje se button -
	- na ciji klik se slideDown-uje div sa istim podatcima kao i u velikom-->
  <div id="dpi" style="height: 1in; width: 1in; left: 100%; position: fixed; top: 100%;"></div>

<?php  echo '<p id="baseurl" hidden>'.base_url().'</p>'; ?>

<?php
  // ako metod rubrikaautor() iz kontrolera internetnovine vrati varijablu $nematekstova znaci da nista nije nadjeno u bazi i onda prikazi tu poruku
  if(isset($nematekstova)){
    echo $rezultat;
  }else{ // ako nije stigla varijabla $nematekstova znaci da je nasao tekstove u bazi i onda ih izlistaj
?>

<div class="container naslovnaouter">

  <!--div koji prikazuje ime rubrike koja je trenutno selektovana-->
  <div class="col-xs-8">
    <br>
	    <div class="text-info bg-info"><!--div sa slikom autora i podatcima teksta(autor, rubrika i datum)-->
	      <img class="slikaautoratekst" src="<?=base_url()?>images/autori/<?php echo $rezultat[0]['id_autora'] ?>.jpg">
		  <div class="podatciiznadteksta">
		    <!--prikazi ime autora ciji tekstovi su zahtevani-->
		    <h3><i>Autor: </i><strong><?php echo $autor ?></strong></h3><br>
		  </div>
		</div>
	  <hr>
  </div>
  
  <!--prikazuj tekstove-->
  <?php
  // iteriraj kroz array sa podatcima tekstova koji je stigao iz kontrolera i prikazuj sliku naslov(koji je link ka teksu) i podnaslov teksta
  foreach($rezultat as $rez){ ?>
    <div class="row"><!--slika i naslov teksta--> 
      <div class="col-xs-8">
	    <?php if($rez['slike'] == 1 ){ // ako ima slike ?>
	      <img class="img-responsive img-rounded glavnaslikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rez['id_teksta'] ?>/1.jpg">
        <?php } else { // ako nema slike - logo ?>
		  <img class="img-responsive img-rounded" src="<?=base_url()?>images/newspaperlogo.jpg">
		<?php }?>
		<div class="naslovslikaglavna"> <!--div koji je na slici i prikazuje link-naslov -->
		  <a href="<?=base_url()?>internetnovine/tekst/<?php echo $rez['id_teksta'] ?>/<?php echo $rez['naslov'] ?>/<?php echo $rez['googlemap'] ?>/<?php echo $rez['id_autora'] ?>">
	        <h2 class="tekstnaslovaglavna"><?php echo $rez['naslov'] ?></h2>
		  </a>
        </div>
      </div>
    </div>
	
    <div class="row"> <!--podnaslov teksta--> 
      <div class="col-xs-8">
	    <h3 class="podnaslovglavna rockwell"><?php echo $rez['podnaslov'] ?></h3>
      </div>
    </div><br>
  <?php } //kraj foreach petlje koja iterira kroz pristigle tekstove i prikazuje ih ?>
  
  <!--paginacija-->
<?php if(strlen($pagination)): ?>
	  <div class="paginacijarubrika">
	    <h4>Idi na stranicu:</h4>
		<?php echo $pagination; ?>
	  </div><br><br>
<?php endif; ?>
  
</div><!--kraj diva .container-->



<?php }  //kraj else-a koji proverava ima li tekstova  ?>























