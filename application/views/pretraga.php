<?php
 /*  echo '<pre>';
    //print_r($rezultat);
	echo '<br><hr>';
	//echo $num_rows;
	// echo '<br><hr>';
	// echo $query;
  echo '</pre>'; */
?>

<!--Fajl prikazuje rezultate pretrage koje mu vraca metod pretraga() iz kontrolera kad se submituje forma za pretragu u 
     divu #desno na Velikom Ekranu ili kad se posle klika na dugme pretraga submituje forma za pretragu na malom ekranu-->

  <div id="dpi" style="height: 1in; width: 1in; left: 100%; position: fixed; top: 100%;"></div>

<?php  echo '<p id="baseurl" hidden>'.base_url().'</p>'; ?>

<?php
  // ako metod rubrikaautor() iz kontrolera internetnovine vrati varijablu $nematekstova znaci da nista nije nadjeno u bazi i onda prikazi tu poruku
  if(isset($nemateksta)){
    echo '<h1 class="text-danger">'.$rezultat.'</h1>';
  }else{ // ako nije stigla varijabla $nematekstova znaci da je nasao tekstove u bazi i onda ih izlistaj
?>

<div class="container naslovnaouter">

  <!--div koji prikazuje pojam po kom je pretrazen sajt-->
  <div class="col-xs-8">
    <br>
	    <!--<div class="text-info bg-info">div sa slikom autora i podatcima teksta(autor, rubrika i datum)-->
		  <div class="podatciiznadtekstapretraga text-info bg-info">
		    <!--prikazi ime autora ciji tekstovi su zahtevani-->
		    <h3 class="text-center text-info"><i>Pretraga po: "</i><strong><?php echo $unoszapretragu ?></strong>",<i> Rezultata:</i> <strong><?php echo $num_rows ?></h3><br>
		  </div>
		<!--</div>-->
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



















