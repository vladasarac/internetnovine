<?php
  if(isset($loginerror)){
    echo $loginerror;
  }
  if(isset($cestitka)){
    echo '<h3 id="logincestitka" class="text-success">Čestitamo, uspešno ste se ulogovali!</h3>';
  }
?>

<?php  echo '<p id="baseurl" hidden>'.base_url().'</p>'; ?>
<h1 id="autorpanel" class="bg-info text-info text-center">Autor Panel</h1>
<div id="autorportret">
  <img class="slikaautora" src="<?=base_url()?>/images/autori/<?=$this->session->userdata('userid')?>.jpg">
  <p class="imeautora text-info"><strong>Ime: </strong><?=$this->session->userdata('username')?></p>
  <p class="emailautora text-info"><strong>Email: </strong><?=$this->session->userdata('usermail')?></p>
  <p id="tipautor" class="skriven"><?=$this->session->userdata('type')?></p>
  <p id="idautor" class="skriven"><?=$this->session->userdata('userid')?></p>
  <hr>
</div>

<p class="lead text-info">Autorski Nalozi</p>
<div id="novitekst">
  
  <?php
      // ako validacija ne prodje i vrati errore prikazi ih
      if(isset($validacijaerrors)){
	    echo '<div id="errori" class="bg-danger text-danger">';
		//echo '<a class="text-danger" href="#"><p id="zatvoriautorpanelerrorporuku" class="lead text-right">X</p></a>';
	    echo '<h3 id="loginerror" class="text-danger">'.$validacijaerrors.'Ili ste - <br>Pogrešno ste popunili formu, pokušajte ponovo.</h3>';
		echo '</div>';
		
		if(isset($naslov) || isset($podnaslov) || isset($tekst)){
		  echo '<p id="nasloverror"  hidden>'.$naslov.'</p>';
		  echo '<p id="podnasloverror"  hidden>'.$podnaslov.'</p>';
		  echo '<p id="teksterror"  hidden>'.$tekst.'</p>';
		}
      }
	  
	  if(isset($uspeh) && $uspeh != ''){
	  
	    echo '<div id="uspeh" class="bg-success text-danger">';
		echo '<a class="text-success" href="#"><p id="zatvoriuspehautorporuku" class="lead text-right">X</p></a>';
	    echo '<h3 id="uspehh3" class="text-center text-success">'.$uspeh.'</h3>';
		echo '</div>';		
		//if(isset($slike)){print_r($slike);}
      }
	  
  ?>
  <!--<button id="novitekstdugme" type="button" class="praviautorabtn btn btn-info btn-lg btn-block">Novi Tekst</button>-->
  <input id="novitekstdugme" class="praviautorabtn btn btn-info btn-lg btn-block" value="Novi Tekst">
 <!--<div id="map-canvas"></div>-->
</div><br>

<div id="prepravitekst">
  
  <input id="prepravitekstdugme" class="praviautorabtn btn btn-info btn-lg btn-block" value="Prepravi Tekst">

</div><br><!--kraj div-a #prepravitekst -->





