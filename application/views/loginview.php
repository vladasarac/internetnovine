

<!--Forma za logovanje, poziva login() metod klase internetnovine -->
<form action="<?=base_url()?>internetnovine/login" class="form-horizontal" method="post">
   <div class="control-group"> 
    <div class="form-group">
     <h1 id="loginh1" class="text-info">Log In</h1>
     <p id="loginp" class="lead text-info">Popunite polja i kliknite LogIn dugme</p>
    </div>	
    <div class="form-group">
	  <label for="email" class="col-sm-2 control-label">Email :</label>
	  <div class="col-sm-6">
	   <input name="email" type="text" class="form-control" id="email" <?php if(isset($email)){echo 'value="'.$email.'"';} // prikazi prethodni unos koji je vracen iz kontrolera ako je validacija neuspela?>><br><br>
	  </div>
	</div>
    <div class="form-group">
	  <label for="password" class="col-sm-2 control-label">Password :</label>
	  <div class="col-sm-6">
	   <input name="password" type="password" class="form-control" id="password"><br><br>
	  </div>
	</div> 
    <div class="form-group">	
	  <input type="submit" id="logindugme" class="btn btn-primary" value="LogIn!">
	</div> 
	<div class="form-group">
	<?php
      // ako validacija ne prodje i vrati errore prikazi ih
      if(isset($validacijaerrors)){
	    echo '<h3 id="loginerror" class="text-danger">'.$validacijaerrors.'</h3>';
      }
	  // ako prodje validaciju ali se ne podudara sa podatcima u bazi
	  if(isset($loginerror)){
	    echo '<h3 id="loginerror" class="text-danger">'.$loginerror.'</h3>';
      }
    ?>
    </div> 
   </div>
</form>





















