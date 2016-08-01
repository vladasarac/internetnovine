<?php
  // echo '<h1>Pozdrav iz InternetNovina</h1>';
  // echo base_url();
  // echo '<form action="'.base_url().'internetnovine/index2">';
  // echo '<input type="submit" id="index2" class="btn btn-primary" value="index2!">';
  // echo '</form>';
  
  // echo '<pre>';
    // print_r($rezultat);
  // echo '</pre>';

  // echo '<h1>'.$rezultat['politika'][0]['naslov'].'</h1>';
?>
   
   
   
  <!--div #dpi se pojavljuje samo u frontend view-ovima da bi bio izmeren ekran i u zavisnosti od velicine prikazuje se -
    - div #desno u velikim ekranima sa linkovima za tekstove sa najvise komentara i sl. a za male ekrane prikazuje se button -
	- na ciji klik se slideDown-uje div sa istim podatcima kao i u velikom-->
  <div id="dpi" style="height: 1in; width: 1in; left: 100%; position: fixed; top: 100%;"></div>

<!--GLAVNI TEKST-->

<div class="container naslovnaouter">

    <div class="row"><!--slika i naslov glavnog teksta--> 
      <div class="col-xs-8">
	    <?php if($rezultat[0][1][0]['slike'] == 1 ){ // ako ima slike ?>
	      <img class="img-responsive img-rounded glavnaslikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat[0][1][0]['id_teksta'] ?>/1.jpg">
        <?php } else { // ako nema slike ?>
		  <img class="img-responsive img-rounded" src="<?=base_url()?>images/newspaperlogo.jpg">
		<?php }?>
		<div class="naslovslikaglavna">
		  <a href="<?=base_url()?>internetnovine/tekst/<?php echo $rezultat[0][1][0]['id_teksta'] ?>/<?php echo $rezultat[0][1][0]['naslov'] ?>/<?php echo $rezultat[0][1][0]['googlemap'] ?>/<?php echo $rezultat[0][1][0]['id_autora'] ?>">
	        <h2 class="tekstnaslovaglavna"><?php echo $rezultat[0][1][0]['naslov'] ?></h2>
		  </a>
        </div>
      </div>  
    </div>
	
	<div class="row"> <!--podnaslov glavnog teksta--> 
      <div class="col-xs-8">
	    <h3 class="podnaslovglavna rockwell"><?php echo $rezultat[0][1][0]['podnaslov'] ?></h3>
      </div>
    </div>
    
  
  <!--iteriraj po $rezultat[1] i prikazuj po 2 najnovija teksta izvucena iz baze za svaku rubriku i rubriku-->
  <?php foreach($rezultat[1] as $rez){ ?>
  
    <div class="row"><!--div za prikaz rubrike -->
      
	  <?php echo ' <div class="col-xs-8 rubrika'.strtolower($rez[0]['rubrika']).'naslovna">' ?>
	    <!--<a href="<? //=base_url()?>internetnovine/rubrika/<?php //echo $rez[0]['rubrika'] ?>"><!--link za metod koji izvlaci sve tekstove iz odredjene rubrike i prikazuje ih u rubrika.php view-u-->
	    <!--rubrikaautor() je univerzalni metod koji izvlaci tekstove ili jedne rubrike ili jednog autora-->
		<a href="<?=base_url()?>internetnovine/rubrikaautor/<?php echo $rez[0]['rubrika'] ?>/rubrika">  
		  <h3 class="imerubrikanaslovna"><?php echo $rez[0]['rubrika'] ?></h3>
		</a>
      </div>
    </div><!--kraj div-a za prikaz rubrike -->
  
    <div class="row"><!-- slika i naslov rubrika  -->
      <div class="col-xs-4"><!-- prvi tekst  -->
	    <?php if($rez[0]['slike'] == 1 ){ // ako ima slike ?>
	      <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rez[0]['id_teksta'] ?>/1.jpg">
        <?php } else { // ako nema slike ?>
		  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/newspaperlogo.jpg">
		<?php }?>
		  <div class="naslovrubrikanaslovna">
	        <a href="<?=base_url()?>internetnovine/tekst/<?php echo $rez[0]['id_teksta'] ?>/<?php echo $rez[0]['naslov'] ?>/<?php echo $rez[0]['googlemap'] ?>/<?php echo $rez[0]['id_autora'] ?>">
	          <h3 class="naslovglavna"><?php echo $rez[0]['naslov'] ?></h3>
			</a>
          </div>
      </div>
      <div class="col-xs-4"><!-- drugi tekst  -->
	    <?php if($rez[1]['slike'] == 1 ){ // ako ima slike ?>
	      <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rez[1]['id_teksta'] ?>/1.jpg">
        <?php } else { // ako nema slike ?>
		  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/newspaperlogo.jpg">
		<?php }?>  
		  <div class="naslovrubrikanaslovna">
		    <a href="<?=base_url()?>internetnovine/tekst/<?php echo $rez[1]['id_teksta'] ?>/<?php echo $rez[1]['naslov'] ?>/<?php echo $rez[1]['googlemap'] ?>/<?php echo $rez[1]['id_autora'] ?>">
	          <h3 class="naslovglavna"><?php echo $rez[1]['naslov'] ?></h3>
			</a>
          </div>
      </div>
    </div><!-- kraj slika i naslov rubrika  -->
  
    <div class="row podnaslov"> <!--podnaslovi --> 
        <div class="col-xs-4">
	      <h4 class="rockwell"><?php echo substr($rez[0]['podnaslov'], 0, 55).'...' ?></h4>
        </div>
	    <div class="col-xs-4">
	      <h4 class="rockwell"><?php echo substr($rez[1]['podnaslov'], 0, 55).'...' ?></h4>
        </div>
    </div><!-- kraj podnaslov-a  --> 
  
  <?php } //kraj foreach petlje ?>
  
</div>


<!--<script src="<?//=base_url()?>js/in5.js"></script>-->
<script>
    /* $(document).ready (function(){
      var ekran = $( window ).width();
	  //alert(ekran);
      var internetnovine5 = new Internetnovine5();
	  
	}); */
</script>




















  
  
  