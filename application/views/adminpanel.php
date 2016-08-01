
<?php
  if(isset($loginerror)){
    echo $loginerror;
  }
  if(isset($cestitka)){
    echo '<h3 id="logincestitka" class="text-success">Čestitamo, uspešno ste se ulogovali!</h3>';
  }
?>
<?php  echo '<p id="baseurl" hidden>'.base_url().'</p>'; ?>
<h1 id="adminpanel" class="bg-success text-info text-center">Admin Panel</h1>
<div id="adminportret">
  <img class="slikaadmina" src="<?=base_url()?>/images/autori/<?=$this->session->userdata('userid')?>.jpg">
  <p class="imeadmina text-success"><strong>Ime: </strong><?=$this->session->userdata('username')?></p>
  <p class="emailadmina text-success"><strong>Email: </strong><?=$this->session->userdata('usermail')?></p>
  <p id="tipadmin" class="skriven"><?=$this->session->userdata('type')?></p>
  <p id="idadmin" class="skriven"><?=$this->session->userdata('userid')?></p>
  <hr>
</div>
<p class="lead text-success">Autorski Nalozi</p>
<div id="praviautora">
  
  <?php
      // ako validacija ne prodje i vrati errore prikazi ih
      if(isset($validacijaerrors)){
	    echo '<div id="errori" class="bg-danger text-danger">';
		echo '<a class="text-danger" href="#"><p id="zatvorierrorporuku" class="lead text-right">X</p></a>';
	    echo '<h3 id="loginerror" class="text-danger">'.$validacijaerrors.'</h3>';
		echo '</div>';
      }
       //echo $this->session->userdata('type');
	
	  if(isset($uspeh) && $uspeh != ''){
	    echo '<div id="uspeh" class="bg-success text-danger">';
		echo '<a class="text-success" href="#"><p id="zatvoriuspehporuku" class="lead text-right">X</p></a>';
	    echo '<h3 id="uspehh3" class="text-center text-success">'.$uspeh.'</h3>';
		echo '<p id="uspehp" class="text-center text-success">Ime: '.$ime.', Email: '.$email.', Password: '.$pass.'</p>';	
		echo '</div>';		
      }
	  
	  if(isset($uspehemail) && $uspehemail != '' && isset($poslatoautorima)){
	    echo '<div id="uspeh" class="bg-success text-danger">';
		echo '<a class="text-success" href="#"><p id="zatvoriuspehporuku" class="lead text-right">X</p></a>';
	    echo '<h3 id="uspehh3" class="text-center text-success">'.$uspehemail.'</h3>';
		echo '<p id="uspehp" class="text-center text-success">Na adrese: '.$poslatoautorima.'</p>';	
		echo '</div>';		
      }
	  
	  
  ?>
  <button id="praviautoradugme" type="button" class="praviautorabtn btn btn-success btn-lg btn-block">Kreirajte Novog Autora</button>
  
 
</div><br>

<div id="prepraviautora">

<button id="prepraviautoradugme" type="button" class="praviautorabtn btn btn-success btn-lg btn-block">Prepravite Podatke Autora</button>
</div>
<br>

<div id="mailautoru">

<button id="mailautorudugme" type="button" class="praviautorabtn btn btn-success btn-lg btn-block">Posaljite Email Autorima</button>
</div>

<br>
<p class="lead text-success">Tekstovi</p>
<div id="neodobrenitekstovi">

<button id="neodobrenitekstovidugme" type="button" class="praviautorabtn btn btn-success btn-lg btn-block">Neodobreni Tekstovi</button>
</div>


<br>
<div id="odobrenitekstovi">
<button id="odobrenitekstovidugme" type="button" class="praviautorabtn btn btn-success btn-lg btn-block">Odobreni Tekstovi</button>
</div>

















