<!DOCTYPE html>
 <html lang="en">
 <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?=base_url()?>bootstrap-3.3.6-dist/css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="<?=base_url()?>bootstrap-3.3.6-dist/css/bootstrap-theme.min.css">
  
  <!--bootstrap maxcdn-->
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->
  
  <script src="<?=base_url()?>js/in.js"></script>
  <script src="<?=base_url()?>js/in2.js"></script>
  <script src="<?=base_url()?>js/in3.js"></script>
  <script src="<?=base_url()?>js/in4.js"></script>
  <script src="<?=base_url()?>js/in5.js"></script>
  <title><?=$vju;?></title>
  <link rel="stylesheet" href="<?=base_url()?>css/style.css">
  <link rel="stylesheet" href="<?=base_url()?>css/style2.css">
  <link rel="stylesheet" href="<?=base_url()?>css/simplePagination.css">
  <link type="text/css" rel="stylesheet" href="<?=base_url()?>flaviusmatis-simplePagination.js-307b5c6/simplePagination.css"/>
  <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
  <!--<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Roboto:400,700,700italic' rel='stylesheet' type='text/css'>
  <script src="<?//=base_url()?>/js/jquery-1.11.3.min.js"></script>-->
  
  <!--botstrapov js fajl-->
  <script src="<?=base_url()?>bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
  
  <script>
    $(document).ready (function(){
	  //alert('pozdrav');
	  setTimeout(function() {
        $("#logincestitka").slideUp(function(){
		  $("#logincestitka").remove();
		})
      }, 5000);
	  
	  var ekran = $( window ).width();
	  //alert(ekran);
	  //var gm = new Gm();
      // ako postoji ovaj div(#dpi) znaci da je otvorena neka od frontend stranica(novineview.php, tekst.php, autor.php, rubrika.php ili svevesti.php), 
	  // - tako da se ucitavaju i funkcije Internetnovine()(in4.js) i Internetnovine5()(in5.js) u kojima je jquery za frontend
	  if ($('#dpi').length){
	    var internetnovine5 = new Internetnovine5();
		var internetnovine4 = new Internetnovine4();
	  }else{ // ako je otvorena neka od admin stranica tj nema div-a #dpi ucitaj jquery za backend
	    var internetnovine = new Internetnovine();
	    var internetnovine2 = new Internetnovine2();
	    var internetnovine3 = new Internetnovine3();
	  }
	});
  </script>
 </head>
 
 
 
 <body>
    <!--proba merenja sirine ekrana u incima-->
    <!--<div id="dpi" style="height: 1in; width: 1in; left: 100%; position: fixed; top: 100%;"></div>-->
  <!--<p id="vju1" hidden><?//=$vju1;?></p>-->
  <div class="container">  
  <nav role="navigation" class="navbar navbar-inverse">
    <div class="navbar-header">
      <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle"><!--ako je mali ekran onda se aktivira ovaj deo i on ceo meni stavlja u dropdown koji se pojavljuje kad se klikne button-->
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
	  <a id="logolink" href="<?=base_url()?>">
      <img src="<?=base_url()?>images/internetnovine.png"><span class="glavninaslov">IN</span>
	  </a>
    </div>
  
    <div id="navbarCollapse" class="collapse navbar-collapse">
      <!--<ul class="nav nav-justified">-->
	 <ul class="nav navbar-nav">
        <li class="lead"><a href="<?=base_url()?>internetnovine/svevesti">Sve Vesti</a></li>
		<!--rubrikaautor() je univerzalni metod koji izvlaci tekstove ili jedne rubrike ili jednog autora-->
        <li class="lead"><a href="<?=base_url()?>internetnovine/rubrikaautor/Politika/rubrika">Politika</a></li>
        <li class="lead"><a href="<?=base_url()?>internetnovine/rubrikaautor/Svet/rubrika">Svet</a></li>
	    <li class="lead"><a href="<?=base_url()?>internetnovine/rubrikaautor/Zabava/rubrika">Zabava</a></li>
        <li class="lead"><a href="<?=base_url()?>internetnovine/rubrikaautor/Sport/rubrika">Sport</a></li>
		<li class="lead"><a href="<?=base_url()?>internetnovine/rubrikaautor/Hronika/rubrika">Hronika</a></li>
		<li class="liredakcija navbar-nav navbar-right">
		  <a data-toggle="dropdown" class="linkredakcija dropdown-toggle" href="#">Redakcija <b class="caret"></b></a>
		  <ul role="menu" class="dropdown-menu">
            <li><a href="<?=base_url()?>internetnovine/onama">O Nama</a></li>
            <li class="divider"></li>
			<?php
			// ako sesija nema 'userid' podesen, napravi link za LogIn
			if($this->session->userdata('userid') == false){
              echo '<li><a href="'.base_url().'internetnovine/loginforma">LogIn</a></li>';
			}elseif($this->session->userdata('userid') != false && $this->session->userdata('type') != 'admin' && $vju == 'IN Autor'){ // ako je 'userid' podesen tj neko je ulogovan, napravi link za LogOut
			  echo '<li><a href="'.base_url().'internetnovine/logout">LogOut</a></li>';
			}elseif($this->session->userdata('userid') != false && $this->session->userdata('type') == 'autor' && $vju != 'IN Autor'){ // ako je 'userid' podesen tj neko je ulogovan, ako je tip usera autor a nije u IN Autor b\view napravi link za logout i za autorpanel tj IN Autor
			  echo '<li><a href="'.base_url().'internetnovine/logout">LogOut</a></li>';
			  echo '<li><a href="'.base_url().'internetnovine/autorpanel">AutorPanel</a></li>';
			}elseif($this->session->userdata('userid') != false && $this->session->userdata('type') == 'admin' && $vju != 'IN Admin'&& $vju == 'IN Autor' ){ // ako je user ulogovan i ako je admin i ako nije u admin panel view-u dodaj i link koji vodi u adminpanel view
			  echo '<li><a href="'.base_url().'internetnovine/logout">LogOut</a></li>';
			  echo '<li><a href="'.base_url().'internetnovine/adminpanel">AdminPanel</a></li>';
			}elseif($this->session->userdata('userid') != false && $this->session->userdata('type') == 'admin' && $vju != 'IN Admin'&& $vju != 'IN Autor' ){ // ako je user ulogovan i ako je admin i ako nije ni u autor panel ni u admin panel view-u dodaj linkove za logout, adminpanel i autorpanel
			  echo '<li><a href="'.base_url().'internetnovine/logout">LogOut</a></li>';
			  echo '<li><a href="'.base_url().'internetnovine/adminpanel">AdminPanel</a></li>';
			  echo '<li><a href="'.base_url().'internetnovine/autorpanel">AutorPanel</a></li>';
			}elseif($this->session->userdata('userid') != false && $this->session->userdata('type') == 'admin' && $vju == 'IN Admin' ){ // ako je ulogovan i ako je admin i ako je u admin panel view-u onda samo link za logout
			  echo '<li><a href="'.base_url().'internetnovine/logout">LogOut</a></li>';
			  echo '<li><a href="'.base_url().'internetnovine/autorpanel">AutorPanel</a></li>';
			}
			?>
          </ul>
		</li>
		
      </ul>
    </div>
  </nav>
  
  
  

  

  

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	