<?php

  class internetnovinemodel extends CI_Model{
    
	public function __construct(){
	  parent::__construct();
	}
	
	//-------------------------------------------------------------------------------------------------------------------
	
	// poziva ga metod index iz internetnovine.php kontrolera da izvuce glavni tekst i po 2 najnovija teksta svake rubrike(odobrena)
	public function naslovna(){
	  //glavni tekst
	  $this->db->where('glavni', 1);
	  $this->db->select('id_teksta, id_autora, rubrika, naslov, podnaslov, slike, yt, googlemap, DATE_FORMAT(datum, "%d/%m/%Y %H:%i") AS datum');
	  $q = $this->db->get('tekst');
	  $rez[0][1] = $q->result_array();	  
	  // array sa rubrikama
	  $rubrike = array('Politika', 'Svet', 'Hronika', 'Zabava', 'Sport');
	  // iteriraj kroz array sa rubrikama i za svaku izvuci dva najnovija teksta koja su odobrena i nisu oznaceni kao glavni tekst
	  for($i = 0; $i < count($rubrike); $i++){
	    $this->db->where('rubrika', $rubrike[$i]);
	    $this->db->where('odobren', 1);
		$this->db->where('glavni !=', 1);
	    $this->db->select('id_teksta, id_autora, odobren, rubrika, naslov, podnaslov, slike, yt, googlemap, DATE_FORMAT(datum, "%d/%m/%Y %H:%i") AS datum');
	    $this->db->limit(2);
	    $this->db->order_by('datum', 'DESC');
	    $q = $this->db->get('tekst');
	    $rez[1][$i] = $q->result_array();
	  }
	  return $rez;
	} // KRAJ funkcije naslovna()
	
	//-------------------------------------------------------------------------------------------------
	
	// metod koji vadi sve odobrene tekstove za view svevesti.php, dobija samo limit i offset zbog paginacije
	public function svevesti($limit, $offset){
	  $this->db->where('odobren', 1);
	  $this->db->select('id_teksta, id_autora, odobren, rubrika, naslov, podnaslov, slike, yt, googlemap, datum');
	  $this->db->limit($limit, $offset); // dodaj za paginaciju
	  $this->db->order_by('datum', 'DESC');
	  $q = $this->db->get('tekst');  
	  if($q->num_rows() > 0){ // ako nadje nesto
	    $rez['tekstovi'] = $q->result_array(); // u ovaj subarray idu izvuceni podatci tekstova koji ce biti prikazani na stranici svevesti.php
	    $this->db->where('odobren', 1);
		$q1 = $this->db->select('COUNT(*) as count', FALSE)->from('tekst');//izvuci ukupan broj rezultata za paginaciju
	    $tmp = $q1->get()->result();
		$rez['num_rows'] = $tmp[0]->count; // stavi ukupan br rezultata bez limt i offset u ovaj subarray
	  }else{ // ako ne nadje nista vrati ovu poruku
	    $rez = 'Nema Nadjenih Tekstova!';
	  }
	  return $rez;
	} // KRAJ funkcije svevesti()
	
    //-------------------------------------------------------------------------------------------------
	
	// metod koji vadi jedan tekst iz baze koji se prikazuje korisniku u tekst.php view-u
	public function tekst($id, $googlemap, $id_autora){
	  // ako tekst nema googlemap vadi podatke teksta i ime autora iz users tabele 
	  if($googlemap != 1){
		$q = $this->db->query('SELECT u.username, t.id_teksta, t.id_autora, t.rubrika, t.naslov, t.podnaslov, t.tekst, t.slike,
                    	  t.yt, t.googlemap, t.komentar, DATE_FORMAT(t.datum, "%d/%m/%Y %H:%i") AS datum
						  FROM tekst t  LEFT OUTER JOIN users u ON t.id_autora = u.userid WHERE t.odobren = 1 AND t.id_teksta = '.$id);
		if($q->num_rows() > 0){				
		$rez[0] = $q->result_array();
		}else{
		  $rez = 'Nema Nadjenih Tekstova!';
		}
	  }else{// ako tekst ima googlemap vadi podatke teksta, lat long in zoom iz googlemap tabele i ime autora iz users tabele 
	    $q = $this->db->query('SELECT u.username, g.lat, g.long, g.zoom, t.id_teksta, t.id_autora, t.rubrika, t.naslov, t.podnaslov, t.tekst, t.slike,
                    	  t.yt, t.googlemap, t.komentar, DATE_FORMAT(t.datum, "%d/%m/%Y %H:%i") AS datum
						  FROM tekst t 
						  LEFT OUTER JOIN googlemap g ON t.id_teksta = g.tekst_id 
						  LEFT OUTER JOIN users u ON t.id_autora = u.userid WHERE t.odobren = 1 AND t.id_teksta = '.$id);
		if($q->num_rows() > 0){				
		$rez[0] = $q->result_array();
		}else{
		  $rez = 'Nema Nadjenih Tekstova!';
		}				  
	  }   
	  return $rez;
	}  // KRAJ funkcije tekst()
	
	//-------------------------------------------------------------------------------------------------
	
	// VAZNO!!!! ZAKOMENTARISI
	public function dodajkomentar($id_teksta, $ime_komentatora, $email_komentatora, $komentar){
	  $datum_komentara = date('Y-m-d H:i:s', time());
	  $data = array( // array sa podatcima za unos u bazu
		  'id_teksta' => $id_teksta,
		  'datum_komentara' => $datum_komentara,
          'ime_komentatora' => $ime_komentatora,
          'email_komentatora' => $email_komentatora,
		  'komentar' => $komentar
		);  
	  $insert = $this->db->insert('komentari', $data); // unesi
	  if($this->db->affected_rows() > 0){ // ako nesto upise
	    $this->db->set('komentar', 'komentar+1', FALSE);
        $this->db->where('id_teksta', $id_teksta);
        $this->db->update('tekst');
		$rez = 1; // vrati jedan -
		//$rez[1] = $this->db->insert_id(); // - i vrati id teksta koji ce u kontroleru biti dodat kao ime folderu za slike	
	  }else{ // ako ne upise iz nekog razloga vrati dva
		  $rez = 2;
	  }
	  return $rez;
	} // KRAJ funkcije dodajkomentar()
	
	//-------------------------------------------------------------------------------------------------
	
	// metod izvlaci 3 najnovija komentara za tekst koji trenutno prikazuje tekst.php, stize ajax u istoimeni metod u kontroleru
	public function prvatrikomentara($id_teksta){
	  $q = $this->db->select('id_komentara, id_teksta, datum_komentara, ime_komentatora, email_komentatora, komentar')//izvuci podatke iz baze
	               ->from('komentari')->where('id_teksta', $id_teksta)->limit(3, 0)->order_by('datum_komentara', 'DESC');
	  $rez[0] = $q->get()->result();
	  $rez[2] = $this->db->last_query();
	  return $rez;
	} // KRAJ funkcije prvatrikomentara()
	
	//-------------------------------------------------------------------------------------------------
	
	// ako tekst ima vise od 3 komentara kad se klikne dugme 'Prikazi Sve Komentare' u tekst.php metod izvlaci preostale komentare iz baze (osim prva 3 koje je izvukao metod prvatrikomentara()) i vraca ih kontroleru da ih vrati u in4.js da ih ispise u tekst.php
	public function svikomentari($id_teksta){
	  $q = $this->db->select('id_komentara, id_teksta, datum_komentara, ime_komentatora, email_komentatora, komentar')//izvuci podatke iz baze, posto mora da se specifikuje i limit i offset ukucao sam 1000000 da bi izvukao sve komentare sigurno
	               ->from('komentari')->where('id_teksta', $id_teksta)->limit(1000000, 3)->order_by('datum_komentara', 'DESC');
	  $rez[0] = $q->get()->result();
	  $rez[2] = $this->db->last_query();
	  return $rez;
	} // KRAJ funkcije svikomentari()
	
	//-------------------------------------------------------------------------------------------------
	
	// metod izvlaci sve tekstove iz jedne rubrike ili od jednog autora
	// poziva ga metod rubrikaautor() iz kontrolera i salje mu ime rubrike ili id autora da bi znao koji WHERE uslov da doda i limit i ofset za paginaciju
	public function rubrikaautor($rubrikaautor, $provera, $limit, $offset){
	  if($provera == "rubrika"){ // ako je rubrika
	    $this->db->where('rubrika', $rubrikaautor); // radi WHERE po koloni rubrika
	  }elseif($provera == "autor"){ // ako je autor
	    $this->db->where('id_autora', $rubrikaautor); // radi WHERE po koloni id_autora 
	  }
	  $this->db->where('odobren', 1); // tekst mora biti odobren
	  $this->db->select('id_teksta, id_autora, odobren, rubrika, naslov, podnaslov, slike, googlemap, datum');
	  $this->db->limit($limit, $offset); // dodaj za paginaciju
	  $this->db->order_by('datum', 'DESC'); // po datumu silazno
	  $q = $this->db->get('tekst');  
	  if($q->num_rows() > 0){ // ako nadje nesto
	    $rez['tekstovi'] = $q->result_array(); // u ovaj subarray idu izvuceni podatci tekstova koji ce biti prikazani na stranici rubrika.php ili autor.php	
		if($provera == "rubrika"){ // ako je rubrika
		  $this->db->where('rubrika', $rubrikaautor); // novi WHERE za drugi query koji vadi ukupan broj tekstova bez limit i offset posto paginaciji treba taj broj
		}elseif($provera == "autor"){ // ako je autor
	      $this->db->where('id_autora', $rubrikaautor); // novi WHERE za drugi query koji vadi ukupan broj tekstova bez limit i offset posto paginaciji treba taj broj
	    }	
	    $this->db->where('odobren', 1);
		$q1 = $this->db->select('COUNT(*) as count', FALSE)->from('tekst');//izvuci ukupan broj rezultata za paginaciju
	    $tmp = $q1->get()->result();
		$rez['num_rows'] = $tmp[0]->count; // stavi ukupan br rezultata bez limt i offset u ovaj subarray 
	  }else{ // ako ne nadje nista vrati ovu poruku
	    $rez = 'Nema Nadjenih Tekstova!';
	  }
	  
	  return $rez;
	} // KRAJ funkcije rubrikaautor()
	
	//-------------------------------------------------------------------------------------------------
	
	// VAZNO VAZNO ZAKOMENTARISI
	public function pretraga($unoszapretragu, $limit, $offset){
	  $unoszapretragu = $this->db->escape_str($unoszapretragu);
	  $where = "odobren = 1 AND (naslov LIKE '%$unoszapretragu%' OR podnaslov LIKE '%$unoszapretragu%')";
	  $this->db->select('id_teksta, id_autora, odobren, rubrika, naslov, podnaslov, slike, googlemap, datum');
	  $this->db->where($where);
	  $this->db->limit($limit, $offset); // dodaj za paginaciju
	  $this->db->order_by('datum', 'DESC'); // po datumu silazno
	  $q = $this->db->get('tekst');
	  if($q->num_rows() > 0){ // ako nadje nesto
	    $rez['tekstovi'] = $q->result_array(); // u ovaj subarray idu izvuceni podatci tekstova koji ce biti prikazani u pretaga.php
		//$rez['query'] = $this->db->last_query();
		$where = "odobren = 1 AND (naslov LIKE '%$unoszapretragu%' OR podnaslov LIKE '%$unoszapretragu%')";
		$q1 = $this->db->select('COUNT(*) as count', FALSE)->from('tekst');//izvuci ukupan broj rezultata za paginaciju
		$this->db->where($where);
	    $tmp = $q1->get()->result();
		$rez['num_rows'] = $tmp[0]->count; // stavi ukupan br rezultata bez limt i offset u ovaj subarray 
	  }else{ // ako ne nadje nista vrati ovu poruku
	    $rez = 'Nema Nadjenih Tekstova!';
	  }
	  
	  return $rez;
	} // KRAJ funkcije pretraga()
	
	//-------------------------------------------------------------------------------------------------
	
	//// metod vadi 5 tekstova sa najvise komentara i vraca u in5.js da se prikaze u div-u #desno ili u divu koji se pravi posle klika na dugme 'Najvise Komentara' ako je mali ekran
	public function najvisekomentara(){
	  $this->db->where('odobren', 1);
	  $this->db->select('id_teksta, id_autora, odobren, rubrika, naslov, podnaslov, slike, googlemap, datum, komentar');
	  $this->db->limit(5, 0); // izvadi samo 5 tekstova (offset = 0)
	  $this->db->order_by('komentar', 'DESC'); // po 
	  $q = $this->db->get('tekst');
	  $rez['tekstovi'] = $q->result_array(); // u ovaj subarray idu izvuceni podatci tekstova koji ce biti prikazani
	  return $rez;
	}
	
	//-------------------------------------------------------------------------------------------------
	
    // metod za login, prima $email i $password
	public function login($email = null, $password = null){
	  $this->db->where('usermail', $email);
      $this->db->where('password', $password);
	  $q = $this->db->get('users');
	  return $q->result_array();
	} // KRAJ funkcije login()
	
	//-------------------------------------------------------------------------------------------------
	
	// metod za dodavanje autora u bazu
	public function praviautora($email, $ime, $password){
	  // prvo proveri da li postoji autor sa istim imenom ili emailom
	  $ime = $this->db->escape_str($ime);
	  $where = "username = '$ime' OR usermail = '$email'";
	  $this->db->select('username, usermail');
	  $this->db->from('users');
	  $this->db->where($where);
	  $query = $this->db->get();
	  if($query->num_rows() > 0){ // ako vrati neki red tj vec postoji user sa tim imenom i mail-om
	    $rez[0] = 0; //vrati nulu
	  }else{ // ako nema sa istim imeno ili mailom, radi unos
	    $data = array( // array sa podatcima za unos u bazu
		  'usermail' => $email,
          'username' => $ime,
          'password' => $password,
		  'type' => 'autor'
		);
	    $insert = $this->db->insert('users', $data); // unesi
		if($this->db->affected_rows() > 0){ // ako nesto upise
		  $rez[0] = 1; // vrati jedan -
		  $rez[1] = $this->db->insert_id(); // - i vrati id koji ce u kontroleru biti dodat uploadovanoj slici kao ime
		}else{ // ako ne upise iz nekog razloga vrati dva
		  $rez[0] = 2;
		}
	  }
	  return $rez;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	//metod za brisanje autora
	public function obrisiautora($id){
	  $q = $this->db->delete('users', array('userid' => $id)); 
	  if($this->db->affected_rows() > 0){
	    return 1;
	  }else{
	    return 0;
	  }
	}
	
	//-------------------------------------------------------------------------------------------------
	
	// metod za promenu podataka tj update autora
	public function prepraviautora($id, $email, $ime, $password){
	  $this->db->where('userid', $id); // update-uj podatke u bazi po id-ju
	  if($password != ''){ // ako je korisnik tj admin popunio password polje u formi za prepravke   
        $this->db->update('users', array(
	                       'username' => $ime, 
						   'usermail' => $email, 
						   'password' => $password
						   )); 
	    return $this->db->affected_rows();//koliko redova je promenjeno
	  }else{ // ako nije popunio password polje u formi za prepravke
	    $this->db->update('users', array(
	                       'username' => $ime, 
						   'usermail' => $email
						   )); 
		return $this->db->affected_rows();//koliko redova je promenjeno
	  }
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function prikaziautore(){
	  $rez = $this->db->get_where('users', array('type' => 'autor'))->result();
	  return $rez;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	//metod za unos novog teksta u bazu
	public function novitekst($idautora, $rubrika, $naslov, $podnaslov, $tekst, $slika, $yt = 0, $latituda = null, $longituda = null, $zoom = null){
	  
	  $naslov = $this->db->escape_str($naslov);
	  $podnaslov = $this->db->escape_str($podnaslov);
	  $tekst = $this->db->escape_str($tekst);
	  
	  date_default_timezone_set('Europe/Berlin'); // za upis datuma
	  $datum = date('Y-m-d H:i:s', time());
	  if($latituda != null){ // ako je popunjena u formi latituda tj autor je dodao googlemap
	    $googlemap = 1; // podesi kontrolnu varijablu na 1 i to ce biti upisano u tekst tabeli u koloni googlemap
	  }else{
	    $googlemap = 0; // ako autor nije kliknuo na mapu u formi googlemap je 0 i ne upisuje se nista u googlemap tabelu
	  }
	  if($yt == null){
	    $yt = 0;
	  }
	  $data = array( // array sa podatcima za unos u bazu
		  'id_autora' => $idautora,
		  'rubrika' => $rubrika,
          'naslov' => $naslov,
          'podnaslov' => $podnaslov,
		  'tekst' => $tekst,
		  'datum' => $datum,
		  'slike' => $slika,
		  'yt' => $yt,
		  'googlemap' => $googlemap
		);  
	  $insert = $this->db->insert('tekst', $data); // unesi
      if($this->db->affected_rows() > 0){ // ako nesto upise
		$rez[0] = 1; // vrati jedan -
		$rez[1] = $this->db->insert_id(); // - i vrati id teksta koji ce u kontroleru biti dodat kao ime folderu za slike
		if($googlemap == 1){ // ako je autor kliknuo na googlemap 
		  $datagm = array( // napravi array sa podatcima za googlemapu idteksta, lat, long i zoom
		    'tekst_id' => $rez[1],
			'lat' => $latituda,
			'long' => $longituda,
			'zoom' => $zoom
		  );
		  $insert = $this->db->insert('googlemap', $datagm); // unesi u googlemap tabelu podatke iz forme
		}
	  }else{ // ako ne upise iz nekog razloga vrati dva
		  $rez[0] = 2;
	  }
	  return $rez;
	}
	
    //-------------------------------------------------------------------------------------------------
    
	public function prikazitekstovejednogautora($idautora, $limit, $offset){
	  $q = $this->db->query("SELECT id_teksta, odobren, rubrika, naslov, podnaslov, tekst, DATE_FORMAT(datum, '%m/%d/%Y %h:%i') AS datum,
	                         slike, yt, googlemap FROM tekst WHERE id_autora = $idautora 
							 ORDER BY id_teksta LIMIT $limit OFFSET $offset");
	  $rez[0] = $q->result();
	  $q1 = $this->db->select('COUNT(*) as count', FALSE)->from('tekst')->where('id_autora', $idautora);
	  $tmp = $q1->get()->result();
	  $rez[1] = $tmp[0]->count;
	  return $rez;
	}
	
    //-------------------------------------------------------------------------------------------------
    
	// metod za prepravljanje jednog teksta u bazi u autor panelu
	public function prepravitekst($idautora, $idtekstaprepravka, $rubrikaprepravka, $naslovprepravka, $podnaslovprepravka, $tekstprepravka, $slikaprepravka, $ytprepravka, $latitudaprepravka = null, $longitudaprepravka = null, $zoomprepravka = null, $stareslike, $starigm){                    
	   // ako je popunjena u formi latituda tj autor je dodao googlemap ili je vec bio dodat googlemap uz tekst
	  if($latitudaprepravka != null || $starigm == 1){
	    $googlemap = 1; // podesi kontrolnu varijablu na 1 i to ce biti upisano u tekst tabeli u koloni googlemap
	  }else{
	    $googlemap = 0; // ako autor nije kliknuo na mapu u formi googlemap je 0 i ne upisuje se nista u googlemap tabelu
	  }
	  $slikeprovera = $slikaprepravka; // ovde uzmi da li je dodao slike posto i ako nije a bilo je slika tj kolona slike je jedan $slikeprepravka se menja u jedan i ne vraca error ako nista nije promenjeno
	  if($stareslike == 1){ // ako je bilo slika tj kolona je 1 
	    $slikaprepravka = 1;
	  }
	  if($ytprepravka == null){ // ako nista nije uneto u yt link polje u formi nemoj updateovati tu kolonu
	    $data = array( // array sa podatcima za unos u bazu
		  'rubrika' => $rubrikaprepravka,
          'naslov' => $naslovprepravka,
          'podnaslov' => $podnaslovprepravka,
		  'tekst' => $tekstprepravka,
		  'slike' => $slikaprepravka,
		  'googlemap' => $googlemap
		);
	  }else{ // ako je nesto uneto u yt linkpolje u formi updateuj i tu kolonu
	    $data = array( // array sa podatcima za unos u bazu
		  'rubrika' => $rubrikaprepravka,
          'naslov' => $naslovprepravka,
          'podnaslov' => $podnaslovprepravka,
		  'tekst' => $tekstprepravka,
		  'slike' => $slikaprepravka,
		  'yt' => $ytprepravka,
		  'googlemap' => $googlemap
		); 
      }		
	  // radi update po id_teksta koloni
	  $this->db->where('id_teksta', $idtekstaprepravka);
	  $this->db->update('tekst', $data);
	  $prepravkatekst = $this->db->affected_rows(); // provera da li je nesto upisano ako je forma submitovana bez ikakvih promena onda nista ne upisuje i vraca 0
	  if($prepravkatekst > 0){ // ako je nesto promenjeno u bazi
		$rez[0] = 1; // vrati jedan -
	  }else{// ako ne upise iz nekog razloga ili je forma submitovana bez unosa ikakvih promena - 
	    if($slikaprepravka == 1){ // ako je menjao slike a vec je tekst imao slike pa je kolona slike ostala 1 tj ni ona nije menjana
		  $rez[0] = 1; // vrati jedan
		}
		if($slikeprovera != 1){ // ako nije ni uneo slike u formi ni nista drugo nije menjao vrati 2 tj error 
		  $rez[0] = 2;
		}
	  }
	  // ako je stigla $latitudaprepravka znaci da je uneta mapa pa se prepravlja tabela googlemap, na vrhu je resen unos googlemap kolone u tekst tabelu
	  if($latitudaprepravka != null ){ // ako je autor kliknuo na googlemap 
		  $this->db->where('tekst_id', $idtekstaprepravka); // radi query u googlemap trabeli da proveri postoji li red sa kolonom tekst_id kao $idtekstaprepravka
		  $query = $this->db->get('googlemap');
		   //ako ima taj red radi se update ako nema radi se insert, ovo je moglo i preko $starigm varijable ali -
		   // - reko' ako je bio neki error pa nije uneo u googlemap tabelu prethodni put da ipak proverim
		  if($query->num_rows() > 0){
		    $datagm = array( // napravi array sa podatcima za googlemapu idteksta, lat, long i zoom
			  'lat' => $latitudaprepravka,
			  'long' => $longitudaprepravka,
			  'zoom' => $zoomprepravka
		    );
			$this->db->where('tekst_id', $idtekstaprepravka);
			$this->db->update('googlemap', $datagm);
			$rez[0] = 1; // vrati 1 ako je menjana googlemap tabela
		  }else{ // ako nije bio red sa tom tekst_id kolonom radi inseert u googlemap tabeli
		    $datagm = array( // napravi array sa podatcima za googlemapu idteksta, lat, long i zoom
		      'tekst_id' => $idtekstaprepravka,
			  'lat' => $latitudaprepravka,
			  'long' => $longitudaprepravka,
			  'zoom' => $zoomprepravka
		    );
		    $insert = $this->db->insert('googlemap', $datagm); // unesi u googlemap tabelu podatke iz forme
			$rez[0] = 1; // vrati 1 ako je upisano u googlemap tabelu
		  }
		}
	  return $rez;
	}
	
    //-------------------------------------------------------------------------------------------------
    
	//metod za izvlacenje neodobrenih tekstova iz baze, izvlaci sve osim samog teksta koji vadi metod prikazineodobrentekst($id) 
	public function prikazineodobrenetekstove($limit, $offset){
	  // DATE_FORMAT da bi useru bio citljiviji datum, radi se LEFT OUTER JOIN da bi se uzelo ime autora iz tabele users
	  $q = $this->db->query("SELECT u.username, t.id_teksta, t.id_autora, t.odobren, t.rubrika, t.naslov, t.podnaslov, t.slike,
	                         DATE_FORMAT(t.datum, '%m/%d/%Y %h:%i') AS datum, t.yt, t.googlemap 
							 FROM tekst t LEFT OUTER JOIN users u ON t.id_autora = u.userid 
							 WHERE t.odobren = 0 
							 ORDER BY id_teksta 
							 LIMIT $limit OFFSET $offset");
	  $rez[0] = $q->result();
	  $q1 = $this->db->select('COUNT(*) as count', FALSE)->from('tekst')->where('odobren', 0);//izvuci ukupan broj rezultata bez limit offset-a zbog paginacije
	  $tmp = $q1->get()->result();
	  $rez[1] = $tmp[0]->count;
	  return $rez;
	}
	
	// metod izvlaci jedan tekst iz baze(samo tekst posto je ostalo izvuceno u metodu prikazineodobrenetekstove)
	public function prikazineodobrentekst($id){
	   $q = $this->db->query("SELECT tekst FROM tekst WHERE id_teksta = $id");
	   $rez[0] = $q->result();
	   return $rez;
	}
	
	//odobritekst, menja kolonu odobren iz 0 u 1
	public function odobritekst($id){
	  $q = $this->db->where('id_teksta', $id)->update("tekst", array('odobren' => '1'));
	  return $q; // vraca true ako izvrsi UPDATE kolone odobren
	}
	
	//metod za brisanje teksta u bazi
	public function obrisitekst($id, $mapa){
	  $this->db->where('id_teksta', $id)->delete('tekst'); // brisi tekst u tekst tabeli
	  if($this->db->affected_rows() > 0){ // ako obrise
	    if($mapa == 1){ // proveri da li je tekst imao mapu
		  $this->db->where('tekst_id', $id)->delete('googlemap'); // ako je imao mapu brisi u googlemap tabeli red sa tim id-em teksta
		  return 1; // i vrati 1 u kontroler
		}else{ // ako nije imao mapu samo vrati 1 u kontroler
		  return 1;
		} 
	  }else{ // ako nije nista obrisao doslo je do greske... vrati 0
		return 0;
	  }
	}	
	//----------------------------------------------------------------------------------------------------------------
	
	//metod za izvlacenje odobrenih tekstova iz baze, izvlaci sve osim samog teksta koji vadi metod prikaziodobrentekst($id) 
	public function prikaziodobrenetekstove($limit, $offset){
	  // DATE_FORMAT da bi useru bio citljiviji datum, radi se LEFT OUTER JOIN da bi se uzelo ime autora iz tabele users
	  $q = $this->db->query("SELECT u.username, t.id_teksta, t.id_autora, t.odobren, t.glavni, t.rubrika, t.naslov, t.podnaslov, t.slike,
	                         DATE_FORMAT(t.datum, '%m/%d/%Y %h:%i') AS datum, t.yt, t.googlemap, t.komentar 
							 FROM tekst t LEFT OUTER JOIN users u ON t.id_autora = u.userid 
							 WHERE t.odobren = 1 
							 ORDER BY id_teksta 
							 LIMIT $limit OFFSET $offset");
	  $rez[0] = $q->result();
	  $q1 = $this->db->select('COUNT(*) as count', FALSE)->from('tekst')->where('odobren', 1);//izvuci ukupan broj rezultata bez limit offset-a zbog paginacije
	  $tmp = $q1->get()->result();
	  $rez[1] = $tmp[0]->count;
	  return $rez;
	}
	
	//odobritekst, menja kolonu odobren iz 0 u 1
	public function zabranitekst($id){
	  $q = $this->db->where('id_teksta', $id)->update("tekst", array('odobren' => '0'));
	  return $q; // vraca true ako izvrsi UPDATE kolone odobren
	}
	
	//metod koji udate-uje tabelu tekst i menja kolonu glavni iz 0 u 1 tekstu koji je upravo proglasen za glavni - 
	// - a tekstu koji je do sad bio glavni iz 1 u 0
	public function proglasiglavni($id){
	  $data = array('glavni' => 0); // prvo tekstu koji je bio glavni prepravi kolonu glavni u nulu posto to vise nije
	  $this->db->where('glavni', 1);
	  $this->db->update('tekst', $data);
	  //zatim tekstu koji je sada proglasen za glavni prepravi kolonu glavni u 1
	  $q = $this->db->where('id_teksta', $id)->update('tekst', array('glavni' => '1'));
	  return $q; // vraca true ako izvrsi UPDATE kolone glavni
	}
	
	// metod vraca sve komentare jednog teksta u admin panel kad se klikne dugme 'komentari' ispod odobrenog teksta da bi admin mogao da obrise nepristojne komentare
	public function svikomentariadminpanel($id_teksta){
	  $q = $this->db->select('id_komentara, id_teksta, datum_komentara, ime_komentatora, komentar')
	               ->from('komentari')->where('id_teksta', $id_teksta)->order_by('datum_komentara', 'DESC');
	  $rez[0] = $q->get()->result();
	  $rez[2] = $this->db->last_query();
	  return $rez;
	}
	
	// kad se u admin panelu klikne link 'obrisi komentar', iz tabele 'komentari' brise red u kom je komentar u tabeli 'tekst' smanjuje kolonu 'komentar' za 1 
	public function obrisikomentar($id_komentara, $id_teksta){
	  $this->db->where('id_komentara', $id_komentara)->delete('komentari'); // brisi komentar u tabeli 'komentari' po id_komentara koloni
	  if($this->db->affected_rows() > 0){ // ako obrise red u 'komentar' tabeli smanji u 'tekst' tabeli kolonu komentar za 1
		$this->db->set('komentar', 'komentar-1', FALSE);
        $this->db->where('id_teksta', $id_teksta);
        $this->db->update('tekst');
	    return 1;
	  }else{
	    return 0;
	  }
	}
  
  }
  
?>




























