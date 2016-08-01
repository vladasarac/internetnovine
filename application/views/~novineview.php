<?php
  // echo '<h1>Pozdrav iz InternetNovina</h1>';
  // echo base_url();
  // echo '<form action="'.base_url().'internetnovine/index2">';
  // echo '<input type="submit" id="index2" class="btn btn-primary" value="index2!">';
  // echo '</form>';
  
  echo '<pre>';
    print_r($rezultat);
  echo '</pre>';

  // echo '<h1>'.$rezultat['politika'][0]['naslov'].'</h1>';
?>



<div class="container naslovnaouter">
  <!--<div class="row col-xs-12 glavniceonaslovna">-->
   <!--<div class="col-xs-10">-->
    <div class="row"><!--slika i naslov glavnog teksta--> 
      <div class="col-xs-8">
	    <img class="img-responsive img-rounded" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['glavni'][0]['id_teksta'] ?>/1.jpg">
        <div class="naslovslikaglavna">
	      <h2 class="tekstnaslovaglavna"><?php echo $rezultat['glavni'][0]['naslov'] ?></h2>
        </div>
      </div>
  
    </div>
    <div class="row"> <!--podnaslov glavnog teksta--> 
      <div class="col-xs-8">
	    <h3 class="podnaslovglavna rockwell"><?php echo $rezultat['glavni'][0]['podnaslov'] ?></h3>
      </div>
    </div>
  
  
  
  <!--RUBRIKA POLITIKA-->
  <div class="row"><!--div za prikaz rubrike politika-->
    <div class="col-xs-8 rubrikapolitikanaslovna">
	  <h3 class="imerubrikanaslovna">Politika</h3>
    </div>
  </div><!--kraj div-a za prikaz rubrike politika-->
  
  
  <div class="row"><!-- slika i naslov rubrika politika -->
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['politika'][0]['id_teksta'] ?>/1.jpg">
        <div class="naslovrubrikanaslovna">
	      <h3><?php echo $rezultat['politika'][0]['naslov'] ?></h3>
        </div>
    </div>
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['politika'][1]['id_teksta'] ?>/1.jpg">
        <div class="naslovrubrikanaslovna">
	      <h3><?php echo $rezultat['politika'][1]['naslov'] ?></h3>
        </div>
    </div>
  </div><!-- kraj slika i naslov rubrika politika -->
  
  <div class="row"> <!--podnaslov politika--> 
      <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['politika'][0]['podnaslov'] ?></h4>
      </div>
	  <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['politika'][1]['podnaslov'] ?></h4>
      </div>
  </div><!-- kraj podnaslov-a politika --> 
  
  
  <!--RUBRIKA SVET-->
  <div class="row"><!--div za prikaz rubrike Svet-->
    <div class="col-xs-8 rubrikasvetnaslovna">
	  <h3 class="imerubrikanaslovna">Svet</h3>
    </div>
  </div><!--kraj div-a za prikaz rubrike Svet-->
  
  
  <div class="row"><!-- slika i naslov rubrika svet -->
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['svet'][0]['id_teksta'] ?>/1.jpg">
        <div class="naslovrubrikanaslovna">
	      <h3><?php echo $rezultat['svet'][0]['naslov'] ?></h3>
        </div>
    </div>
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['svet'][1]['id_teksta'] ?>/1.jpg">
      <div class="naslovrubrikanaslovna">
	    <h3><?php echo $rezultat['svet'][1]['naslov'] ?></h3>
      </div>
    </div>
  </div><!-- kraj slika i naslov rubrika svet -->
  
  <div class="row"> <!--podnaslov svet--> 
      <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['svet'][0]['podnaslov'] ?></h4>
      </div>
	  <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['svet'][1]['podnaslov'] ?></h4>
      </div>
  </div><!-- kraj podnaslov-a svet --> 
  
  
  
  <!--RUBRIKA HRONIKA-->
  <div class="row"><!--div za prikaz rubrike Hronika-->
    <div class="col-xs-8 rubrikahronikaslovna">
	  <h3 class="imerubrikanaslovna">Hronika</h3>
    </div>
  </div><!--kraj div-a za prikaz rubrike Hronika-->
  
  
  <div class="row"><!-- slika i naslov rubrika hronika -->
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['hronika'][0]['id_teksta'] ?>/1.jpg">
        <div class="naslovrubrikanaslovna">
	      <h3><?php echo $rezultat['hronika'][0]['naslov'] ?></h3>
        </div>
    </div>
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['hronika'][1]['id_teksta'] ?>/1.jpg">
      <div class="naslovrubrikanaslovna">
	    <h3><?php echo $rezultat['hronika'][1]['naslov'] ?></h3>
      </div>
    </div>
  </div><!-- kraj slika i naslov rubrika hronika -->
  
  <div class="row"> <!--podnaslov hronika--> 
      <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['hronika'][0]['podnaslov'] ?></h4>
      </div>
	  <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['hronika'][1]['podnaslov'] ?></h4>
      </div>
  </div><!-- kraj podnaslov-a hronika --> 
  
  
  <!--RUBRIKA ZABAVA-->
  <div class="row"><!--div za prikaz rubrike Zabava-->
    <div class="col-xs-8 rubrikazabavanaslovna">
	  <h3 class="imerubrikanaslovna">Zabava</h3>
    </div>
  </div><!--kraj div-a za prikaz rubrike Zabava-->
  
  
  <div class="row"><!-- slika i naslov rubrika zabava -->
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['zabava'][0]['id_teksta'] ?>/1.jpg">
        <div class="naslovrubrikanaslovna">
	      <h3><?php echo $rezultat['zabava'][0]['naslov'] ?></h3>
        </div>
    </div>
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['zabava'][1]['id_teksta'] ?>/1.jpg">
      <div class="naslovrubrikanaslovna">
	    <h3><?php echo $rezultat['zabava'][1]['naslov'] ?></h3>
      </div>
    </div>
  </div><!-- kraj slika i naslov rubrika zabava -->
  
  <div class="row"> <!--podnaslov zabava--> 
      <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['zabava'][0]['podnaslov'] ?></h4>
      </div>
	  <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['zabava'][1]['podnaslov'] ?></h4>
      </div>
  </div><!-- kraj podnaslov-a zabava --> 
  
  
  <!--RUBRIKA SPORT-->
  <div class="row"><!--div za prikaz rubrike Sport-->
    <div class="col-xs-8 rubrikasportnaslovna">
	  <h3 class="imerubrikanaslovna">Sport</h3>
    </div>
  </div><!--kraj div-a za prikaz rubrike Sport-->
  
  
  <div class="row"><!-- slika i naslov rubrika sport -->
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['sport'][0]['id_teksta'] ?>/1.jpg">
        <div class="naslovrubrikanaslovna">
	      <h3><?php echo $rezultat['sport'][0]['naslov'] ?></h3>
        </div>
    </div>
    <div class="col-xs-4">
	  <img class="img-responsive img-rounded slikalinknaslovna" src="<?=base_url()?>images/tekstovi/<?php echo $rezultat['sport'][1]['id_teksta'] ?>/1.jpg">
      <div class="naslovrubrikanaslovna">
	    <h3><?php echo $rezultat['sport'][1]['naslov'] ?></h3>
      </div>
    </div>
  </div><!-- kraj slika i naslov rubrika sport -->
  
  <div class="row"> <!--podnaslov sport--> 
      <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['sport'][0]['podnaslov'] ?></h4>
      </div>
	  <div class="col-xs-4">
	    <h4 class="rockwell"><?php echo $rezultat['sport'][1]['podnaslov'] ?></h4>
      </div>
  </div><!-- kraj podnaslov-a sport --> 

<!--</div>-->
 <!--PROBA PROBA-->
	  <!--<div class="col-xs-2 proba">
	    <p>Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		Posle priznanja Danijele Popli?anovi?, u?esnice rijalitija “Parovi”, da je za seks sa Zmajem od Šipova dobila novac od produkcije
		</p>
	  </div>-->
	  <!--</div>-->
	  </div>

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




















