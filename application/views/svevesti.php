<?php
  /* echo '<pre>';
    print_r($rezultat);
  echo '</pre>';
  //echo $nematekstova;
  foreach($rezultat as $rez){
    $datum = strtotime($rez['datum']);
	$formatirandatum = date('d/m/Y H:i', $datum);
    echo 'Tekst id: '.$rez['id_teksta'].', Datum: '.$formatirandatum.'<hr>';
	
  } */
?>

<?php
  // ako metod svevesti() iz kontrolera internetnovine vrati varijablu $nematekstova znaci da nista nije nadjeno u bazi i onda prikazi tu poruku
  if(isset($nematekstova)){
    echo $rezultat;
  }else{ // ako nije stigla varijabla $nematekstova znaci da je nasao tekstove u bazi i onda ih izlistaj
?>


  <!--div #dpi se pojavljuje samo u frontend view-ovima da bi bio izmeren ekran i u zavisnosti od velicine prikazuje se -
    - div #desno u velikim ekranima sa linkovima za tekstove sa najvise komentara i sl. a za male ekrane prikazuje se button -
	- na ciji klik se slideDown-uje div sa istim podatcima kao i u velikom-->
  <div id="dpi" style="height: 1in; width: 1in; left: 100%; position: fixed; top: 100%;"></div>
<?php  echo '<p id="baseurl" hidden>'.base_url().'</p>'; ?>
<div class="container naslovnaouter">

<!--prikazuj tekstove-->
  <?php
  // iteriraj kroz array sa podatcima tekstova koji je stigao iz kontrolera i prikazuj sliku naslov(koji je link ka teksu) i podnaslov teksta
    foreach($rezultat as $rez){ 
    $datum = strtotime($rez['datum']); // podesi datum da ga prikazuje u citljivom formatu
	$formatirandatum = date('d/m/Y H:i', $datum);
  ?>

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
	    <p class="datumispodteksta text-info"><strong>Datum: </strong><i><?php echo $formatirandatum ?> h</i></p>
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

  
  
  
  