<?php
  
  
  class internetnovine extends CI_Controller{
  
    // konstruktor
    public function __construct(){
      parent::__construct();
	  $this->load->helper('url');
	  //$this->output->enable_profiler(TRUE);
    }
	
	//-------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------
	
	public function index(){
	  $this->load->model('internetnovinemodel'); // ucitaj model
	  $rezultat = $this->internetnovinemodel->naslovna();
	  $data['rezultat'] = $rezultat;
	  $data['vju'] = 'internetnovine';
	  $this->load->view('novineheader', $data);
      $this->load->view('novineview');
	  $this->load->view('novinefooter');
	}
	
	//-------------------------------------------------------------------------------------------------
	
	//metod prikazuje jedan tekst kad citalac klikne na naslov teksta
	public function tekst($id, $naslov, $googlemap, $id_autora){
	  $data['id_teksta'] = $id;
	  $this->load->model('internetnovinemodel'); // ucitaj model
	  $data['rezultat'] = $this->internetnovinemodel->tekst($id, $googlemap, $id_autora);
	  if($data['rezultat'] == "Nema Nadjenih Tekstova!"){ //ako ne postoji tekst sa id-em koji je u URL ucitaj novineview2 sa porukom nemateksta... 
	    $data['nemateksta'] = $data['rezultat'];
	    $data['vju'] = 'IN Tekst '.urldecode($naslov);
	    $this->load->view('novineheader', $data);
        $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }else{ // ako nadje tekst u bazi ucitaj view tekst.php i posalji mu podatke teksta iz baze
	    $data['vju'] = 'IN Tekst '.urldecode($naslov);
	    $this->load->view('novineheader', $data);
        $this->load->view('tekst', $data);
	    $this->load->view('novinefooter');
	  }
	}
	
	//-------------------------------------------------------------------------------------------------
	
	/* public function rubrika($rubrika){
	  $limit = 3; // tekstova po stranici 
	  $offset = $this->uri->segment(4); // offset stize iz URI posto je ucitana pagination klasa
	  $data['vju'] = 'IN rubrika '.$rubrika;
	  $data['rubrika'] = $rubrika;
	  $this->load->model('internetnovinemodel'); // ucitaj model
	  $data['rezultat'] = $this->internetnovinemodel->rubrika($rubrika, $limit, $offset); // salji ime rubrike i limt i offset posto se radi paginacija
	  $data['num_rows'] = $data['rezultat']['num_rows']; // ukupan broj redova koji zadovoljava where uslov bez limit i offset-a
	  $data['rezultat'] = $data['rezultat']['tekstovi']; // izvuceni podatci za prikazivanje po stranici(sa limitom i offsetom)
	  //$this->load->library('uri');
	  $this->load->library('pagination'); // ucitaj klasu za paginaciju
	  $config = array(); // array za konfiguraciju paginacije, obavezan
	  $config['base_url'] = site_url("internetnovine/rubrika/$rubrika");
	  $config['total_rows'] = $data['num_rows']; // kolko je nadjeno ukupno redova bez limita i offseta
	  $config['per_page'] = $limit; // definisano na vrhu metoda koliko rezultata da prikaze po stranici
	  $config['uri_segment'] = 4; // gde se u URI-u nalazi offset koji generise CI
	  $config['num_links'] = 7; // broj linkova
	  $config['full_tag_open'] = '<div class="light-theme">'; // cela paginacija ide u ovaj div css je u fajlu internetnovine\css\simplePagination.css
      $config['full_tag_close'] = '</div>';  // kraj diva koji prikazuje paginaciju
	  $config['cur_tag_open'] = '<a class="paginacijacurrentlink"><b class="current">'; // trenutni link
      $config['cur_tag_close'] = '</b></a>';
	  $this->pagination->initialize($config);
	  $data['pagination'] = $this->pagination->create_links(); // napravi linkove koji se salju u view rubrika.php
	  $this->load->view('novineheader', $data);
      $this->load->view('rubrika', $data);
	  $this->load->view('novinefooter');
	} */
	
	// metod izvlaci sve tekstove iz jedne rubrike ili od jednog autora i salje ih u view rubrika.php ili autor.php
	// u varijabli $provera je vrednost koja odlucuje da li se trazi po autoru ili rubrici, $imeautora = null ako se trazi po rubrici zato sto linkovi koji pozivaju ovaj metod nemaju taj parametar 
	public function rubrikaautor($rubrikaautor, $provera, $imeautora = null){
	
	  $limit = 3; // tekstova po stranici 
	  if($provera == "rubrika"){ // ako je rubrika
	    $offset = $this->uri->segment(5); // offset je 5 posto ne stize i ime autora
	  }elseif($provera == "autor"){ // ako je autor
	    $offset = $this->uri->segment(6); // offset je 6 posto je u URI i ime autora kao argument
	  }
	  //$offset = $this->uri->segment(5); // offset stize iz URI posto je ucitana pagination klasa
	  if($provera == "rubrika"){ // varijable za kontrolu vju-a
	    $data['vju'] = 'IN rubrika '.$rubrikaautor;
	    $data['rubrika'] = $rubrikaautor;
	  }elseif($provera == "autor"){
	    $data['vju'] = 'IN autor '.urldecode($imeautora);
	    $data['autor'] = urldecode($imeautora);
	  }
	  $this->load->model('internetnovinemodel'); // ucitaj model
	  $data['rezultat'] = $this->internetnovinemodel->rubrikaautor($rubrikaautor, $provera, $limit, $offset); // salji ime rubrike ili id_autora, $proveru(da bi model znao po kojoj koloni da doda WHERE) i limt i offset posto se radi paginacija
	  //$data['rezultat1'] = $data['rezultat'];
	  
	  if($data['rezultat'] != 'Nema Nadjenih Tekstova!'){ // ako model nadje nesto tj ne vrati ovu poruku posalji podatke nadjenih tekstova u odgovarajuci view
	    
		$data['num_rows'] = $data['rezultat']['num_rows']; // ukupan broj redova koji zadovoljava where uslov bez limit i offset-a
	    $data['rezultat'] = $data['rezultat']['tekstovi']; // izvuceni podatci za prikazivanje po stranici(sa limitom i offsetom)
	    $this->load->library('pagination'); // ucitaj klasu za paginaciju
	    $config = array(); // array za konfiguraciju paginacije, obavezan
	    if($provera == "rubrika"){// ako je rubrika
	      $config['base_url'] = site_url("internetnovine/rubrikaautor/$rubrikaautor/$provera");
	    }elseif($provera == "autor"){ // ako je autor
	      $config['base_url'] = site_url("internetnovine/rubrikaautor/$rubrikaautor/$provera/$imeautora");
	    }
	    $config['total_rows'] = $data['num_rows']; // kolko je nadjeno ukupno redova bez limita i offseta
	    $config['per_page'] = $limit; // definisano na vrhu metoda koliko rezultata da prikaze po stranici
	    if($provera == "rubrika"){ // ako je rubrika
	      $config['uri_segment'] = 5; // gde se u URI-u nalazi offset koji generise CI
	    }elseif($provera == "autor"){ // ako je autor
	      $config['uri_segment'] = 6; // gde se u URI-u nalazi offset koji generise CI
	    }
	  //$config['uri_segment'] = 5; // gde se u URI-u nalazi offset koji generise CI
	    $config['num_links'] = 7; // broj linkova
	    $config['full_tag_open'] = '<div class="light-theme">'; // cela paginacija ide u ovaj div css je u fajlu internetnovine\css\simplePagination.css
        $config['full_tag_close'] = '</div>';  // kraj diva koji prikazuje paginaciju
	    $config['cur_tag_open'] = '<a class="paginacijacurrentlink"><b class="current">'; // trenutni link na koji se ne moze kliknuti
        $config['cur_tag_close'] = '</b></a>';
	    $this->pagination->initialize($config);
	    $data['pagination'] = $this->pagination->create_links(); // napravi linkove koji se salju u view rubrika.php
		//ucitaj header odgovarajuci view(rubrika.php ili autor.php) i footer
	    if($provera == "rubrika"){ // ako je rubrika
	      $this->load->view('novineheader', $data);
          $this->load->view('rubrika', $data);
	      $this->load->view('novinefooter');
	    }elseif($provera == "autor"){ // ako je autor
	      $this->load->view('novineheader', $data);
          $this->load->view('autor', $data);
	      $this->load->view('novinefooter');
	    }
	  }else{ // ako nista nije nadjeno u bazi tj model je vratio poruku  Nema Nadjenih Tekstova! posalji to u odgovarajuci view  
	    $data['nematekstova'] = "Nema pronadjenih Tekstova!";
		//ucitaj header odgovarajuci view(rubrika.php ili autor.php) i footer
		if($provera == "rubrika"){ // ako je rubrika
	      $this->load->view('novineheader', $data);
          $this->load->view('rubrika', $data);
	      $this->load->view('novinefooter');
	    }elseif($provera == "autor"){ // ako je autor
	      $this->load->view('novineheader', $data);
          $this->load->view('autor', $data);
	      $this->load->view('novinefooter');
	    }	
	  }
	  
	} // KRAJ funkcije rubrikaautor()
	
	//-------------------------------------------------------------------------------------------------
	
	/* public function autor($autorid, $autor){
	  $data['vju'] = 'IN Autor '.urldecode($autor);
	  $data['autor'] = urldecode($autor);
	  $data['autorid'] = $autorid;
	  $this->load->model('internetnovinemodel'); // ucitaj model
	  //$data['rezultat'] = $this->internetnovinemodel->autor($autor);
	  $this->load->view('novineheader', $data);
      $this->load->view('autor', $data);
	  $this->load->view('novinefooter');
	} */
	//-------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------
	
	//metod za proveru da li je user ulogovan kao admin vraca true ili false, metod je private i mogu ga zvat samo drugi metodi ove klase
	private function _obavezanlogin(){
	  if($this->session->userdata('userid') == false || $this->session->userdata('type') != "admin"){ // ako nije podesen userid u sesiji
		$loginerror = null;	//vrati null
	  }else{ // ako jeste tj. user je ulogovan -
	    $loginerror = 1; // vrati true tj. 1
	  }
	  return $loginerror;
	}
	
	private function _obavezanloginautora(){
	  if($this->session->userdata('userid') == false){ // ako nije podesen userid u sesiji
		$loginerror = null;	//vrati null
	  }else{ // ako jeste tj. user je ulogovan -
	    $loginerror = 1; // vrati true tj. 1
	  }
	  return $loginerror;
	}
	
	//-------------------------------------------------------------------------------------------------
	// metodi za login logout i slicno
	//-------------------------------------------------------------------------------------------------
	
	// metod samo prikazuje login formu tj loginview.php
	public function loginforma(){  
	  $data['vju'] = 'IN LogiIn';
	  $this->load->view('novineheader', $data);
      $this->load->view('loginview');
	  $this->load->view('novinefooter');
	} // KRAJ funkcije login()
	
	// metod za validaciju forme za logovanje
	public function login(){  
	  $data['vju'] = 'IN LogiIn';
	  if($this->input->server('REQUEST_METHOD') == 'POST'){ // ako je stigao $POST iz loginview-a
	    $this->load->library('form_validation'); // radi validaciju
	    $this->form_validation->set_rules('email', 'email', 'required|valid_email',
          array('required' => 'Polje "Email" je obavezno.', 'valid_email' => 'Pogrešan Email, pokušajte ponovo.')
        );
	    $this->form_validation->set_rules('password', 'password', 'required|min_length[6]',
          array('required' => 'Polje "Password" je obavezno.', 'min_length' => 'Password mora imati najmanje 6 karaktera.')
        );
	    if($this->form_validation->run() == FALSE){ // ako ne prodje validacija
	      $data['validacijaerrors'] =  validation_errors(); // posalji u loginview koji su errori da korisnik opet popuni formu
	      $data['email'] = $this->input->post('email'); // posalji user-ov unos u email polje da view popuni polje za novi unos
		  $this->load->view('novineheader', $data);
          $this->load->view('loginview', $data);
	      $this->load->view('novinefooter');
	    }else{ // ako uspe validacija
		  $email = $this->input->post('email');
	      $pass = $this->input->post('password');
          $password = SHA1($pass); // sifriraj password
		  $this->load->model('internetnovinemodel'); // ucitaj model
		  $rezultat = $this->internetnovinemodel->login($email, $password); // pozovi login() metod modela da uporedi unos sa bazom
		  if($rezultat){ // ako se podudara sa bazom - 
		    $userdata = array( // napravi array koji se ubacuje u sesiju u kom su podatci ulogovanog usera
			  'userid' => $rezultat[0]['userid'],
			  'usermail' => $rezultat[0]['usermail'],
			  'username' => $rezultat[0]['username'],
			  'type' => $rezultat[0]['type']			  
			);
			if($rezultat[0]['type']	== 'admin'){
		      $this->session->set_userdata($userdata); // ubaci array sa podatcima user-a u sesiju
			  //$data['userid'] = $this->session->userdata('userid');
			  //$data['usermail'] = $this->session->userdata('usermail');
		      $data['rezultat'] = $rezultat;
			  $data['vju'] = 'IN Admin';
			  $data['cestitka'] = 1; // ako iz ovog metoda ulazi u adminpanel view da prikaze poruku useru da se uspesno ulogovao
			  $this->load->view('novineheader', $data); // ucitaj vju-ove
			  $this->load->view('adminpanel', $data);
			  $this->load->view('novinefooter');
			}elseif($rezultat[0]['type']	== 'autor'){
			  $this->session->set_userdata($userdata); // ubaci array sa podatcima user-a u sesiju
			  //$data['userid'] = $this->session->userdata('userid');
			  //$data['usermail'] = $this->session->userdata('usermail');
		      $data['rezultat'] = $rezultat;
			  $data['vju'] = 'IN Autor';
			  $data['cestitka'] = 1; // ako iz ovog metoda ulazi u adminpanel view da prikaze poruku useru da se uspesno ulogovao
			  $this->load->view('novineheader', $data); // ucitaj vju-ove
			  $this->load->view('autorpanel', $data);
			  $this->load->view('novinefooter');
			}
		  }else{ // ako se ne podudara sa bazom
		    $data['loginerror'] = 'Uneli ste pogrešne podatke,  pokušajte ponovo.';
			$this->load->view('novineheader', $data);
            $this->load->view('loginview', $data);
	        $this->load->view('novinefooter');
		  }
		}
	  }  
	} // KRAJ funkcije login()
	
	//-------------------------------------------------------------------------------------------------
	
	// metod za logout
	public function logout(){
	  $this->session->sess_destroy();
	  redirect('/');
	}
	
	//-------------------------------------------------------------------------------------------------
	// METODI ZA ADMIN PANEL
	//-------------------------------------------------------------------------------------------------
	
	// ako user tj. admin hoce u adminpanel view ali ne iz login metoda poziva se ovaj metod 
	public function adminpanel(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ // ako jeste pusti ga da udje u admin panel
	  $data['vju'] = 'IN Admin';
	  $this->load->view('novineheader', $data);
	  $this->load->view('adminpanel', $data);
	  $this->load->view('novinefooter');
	  }else{ // ako nije pozovi drugi view i posalji poruku da mora biti ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
		$this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ funkcije adminpanel()
	
	//-------------------------------------------------------------------------------------------------
	
	// ako user tj. admin hoce u adminpanel view ali ne iz login metoda poziva se ovaj metod 
	public function autorpanel(){
	  $provera = $this->_obavezanloginautora(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ // ako jeste pusti ga da udje u admin panel
	  $data['vju'] = 'IN Autor';
	  $this->load->view('novineheader', $data);
	  $this->load->view('autorpanel', $data);
	  $this->load->view('novinefooter');
	  }else{ // ako nije pozovi drugi view i posalji poruku da mora biti ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
		$this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ funkcije autorpanel()
	
	//-------------------------------------------------------------------------------------------------
	
	// kad se popuni pop-up forma za kreiranje autora u adminpanel view-u
	public function praviautora(){
	$provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ // ako jeste pusti ga da udje u admin panel
	    $this->load->library('form_validation'); // iako jquery radi validaciju pre slanja forme iz adminpanela uradi jos jednom za svaki slucaj
	    $this->form_validation->set_rules('email', 'email', 'required|valid_email',
            array('required' => 'Polje "Email" je obavezno.', 'valid_email' => 'Pogrešan Email, pokušajte ponovo.')
          );
	    $this->form_validation->set_rules('ime', 'ime', 'required',
            array('required' => 'Polje "Ime" je obavezno.')
          );
	    /* $this->form_validation->set_rules('email', 'email', 'required|valid_email',
            array('required' => 'Polje "Email" je obavezno.', 'valid_email' => 'Pogrešan Email, pokušajte ponovo.')
          ); */
	    $this->form_validation->set_rules('password', 'password', 'required|min_length[6]',
            array('required' => 'Polje "Password" je obavezno.', 'min_length' => 'Password mora imati najmanje 6 karaktera.')
          );
	    if($this->form_validation->run() == FALSE){ // ako ne prodje validacija
	      $data['validacijaerrors'] =  validation_errors(); // posalji u adminpanel koji su errori
		  $this->load->view('novineheader', $data);
          $this->load->view('adminpanel', $data);
	      $this->load->view('novinefooter');
	    }else{ // ako prodje validacija, uzmi sta je uneto u formu
		  $email = $this->input->post('email');
		  $ime = $this->input->post('ime');
	      $pass = $this->input->post('password');
		  $password = SHA1($pass); // sifriraj password
		  $data['email'] = $email;
		  $data['ime'] = $ime;
		  $data['pass'] = $pass;
		  $this->load->model('internetnovinemodel'); // ucitaj model
		  $rezultat = $this->internetnovinemodel->praviautora($email, $ime, $password);// zovi metod za unos autora u modelu
		  if($rezultat[0] == 0){ //autor je vec unet
			$data['uspeh'] = '';
			$data['vju'] = 'IN Admin';
			$data['validacijaerrors'] = 'Autor sa imenom ili emailom koji ste uneli je vec unet u bazu.';
            $this->load->view('novineheader', $data);
            $this->load->view('adminpanel', $data);
	        $this->load->view('novinefooter');
		  }elseif($rezultat[0] == 1){ // ako je uspesno uneto tj model vratio 1
		      $data['uspeh'] = 'Sve je OK';
		      // konfiguracija za upload library
		      $config['upload_path'] = './images/autori/'; // folder za slike autora
              $config['allowed_types'] = 'gif|jpg|jpeg|png'; // dozvoljeni tipovi slika
		      $config['max_size'] = 10000; // velicina, sirina itd...
              $config['max_width'] = 1024;
              $config['max_height'] = 768;
		      $config['file_name'] = $rezultat[1]; // model je u $rez[1] vratio id unetog reda i to ce biti ime slike unetog autora
		      $this->load->library('upload', $config); // ucitaj upload library
		      if(!$this->upload->do_upload('slika')){ // radi upload u C:\xampp\htdocs\internetnovine\images\autori
                //$error = array('error' => $this->upload->display_errors());
				// ako je bilo errora pri upload-u slike obrisi unos u bazu
                $this->internetnovinemodel->obrisiautora($rezultat[1]);
				$data['vju'] = 'IN Admin';
			    $data['uspeh'] = '';
			    $data['validacijaerrors'] = 'Niste uneli sliku ili je došlo do greške pri ucitavanju slike.';
                $this->load->view('novineheader', $data);
                $this->load->view('adminpanel', $data);
	            $this->load->view('novinefooter');
              }else{ // ako nema gresaka pri uploadu slike ucitaj vju admiin panel sa porukom uspeh itd...
		         $data['vju'] = 'IN Admin';
				 $data['uspeh'] = 'Uspešno ste kreirali novog autora:';
                 $data['upload_data'] = $this->upload->data();   
		         $this->load->view('novineheader', $data);
                 $this->load->view('adminpanel', $data);
	             $this->load->view('novinefooter');
		      }
			}elseif($rezultat[0] == 2){ // doslo je do neke greske pri upisa u bazu
			  $data['uspeh'] = '';
			  $data['validacijaerrors'] = 'Došlo je do greške u bazi.';
              $this->load->view('novineheader', $data);
              $this->load->view('adminpanel', $data);
	          $this->load->view('novinefooter');
			}
		}
	  }else{ // ako nije pozovi drugi view i posalji poruku da mora biti ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
		$this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ funkcije praviautora()
	
	//-------------------------------------------------------------------------------------------------
	
	public function prikaziautore(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera && $this->input->post('provera') == 1){
	    $this->load->model('internetnovinemodel'); // ucitaj model
		$rezultat = $this->internetnovinemodel->prikaziautore(); // metod modela koji cita sve autore i njihove podatke iz baze
		$this->output->set_output(json_encode($rezultat)); // vrati json
	  }else{ // ako nije ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
		$this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ funkcije prikaziautore()
	
	//-------------------------------------------------------------------------------------------------
	
	//metod poziva klik na button brisi autora u admin panelu ciji hendler salje ajax sa id-em autora
	public function obrisiautora(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ // ako je ulogovan
	    $id = $this->input->post('id');// uzmi id
		$this->load->model('internetnovinemodel');
		$rezultat = $this->internetnovinemodel->obrisiautora($id); // zovi model
		$this->output->set_output(json_encode($rezultat)); // vrati json hendleru koji je poslao ajax
	  }else{ // ako nije ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
		$this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	}  // KRAJ funkcije obrisiautora()
	
	//-------------------------------------------------------------------------------------------------
	
	//metod za prepravljanje podataka jednog autora u admin panelu 
	public function prepraviautora(){
	  //$this->output->enable_profiler(TRUE);
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ // ako je ulogovan
	    $this->load->library('form_validation'); // iako jquery radi validaciju pre slanja forme iz adminpanela uradi jos jednom za svaki slucaj
	    $this->form_validation->set_rules('mailprepravka', 'mailprepravka', 'required|valid_email',
            array('required' => 'Polje "Email" je obavezno.', 'valid_email' => 'Pogrešan Email, pokušajte ponovo.')
          );
	    $this->form_validation->set_rules('imeprepravka', 'imeprepravka', 'required',
            array('required' => 'Polje "Ime" je obavezno.')
          );
	    if($this->form_validation->run() == FALSE){ // ako ne prodje validacija
		  $data['vju'] = 'IN Admin';
	      $data['validacijaerrors'] =  validation_errors(); // posalji u adminpanel koji su errori
		  $this->load->view('novineheader', $data);
          $this->load->view('adminpanel', $data);
	      $this->load->view('novinefooter');
	    }else{ // ako prodje validacija
		  $data['uspeh'] = '';
		  $id = $this->input->post('idprepravka');// uzmi unos u formu korisnika
		  $email = $this->input->post('mailprepravka');
		  $ime = $this->input->post('imeprepravka');
	      $pass = $this->input->post('passprepravka');
		  if($pass != ''){ // ako je stigao passwor sifriraj ga ako nije salji '' u model
		    $password = SHA1($pass); // sifriraj password
		  }else{
		    $password = '';
		  }
		  if(isset($_FILES['slika2']['name']) && !empty($_FILES['slika2']['name'])){ // ako je uneta nova slika
			  $file_name = './images/autori/'.$id.'.jpg';
			  unlink($file_name);  // obrisi staru sliku
			  // konfiguracija za upload library
			  $config['upload_path'] = './images/autori/'; // folder za slike autora
              $config['allowed_types'] = 'gif|jpg|jpeg|png'; // dozvoljeni tipovi slika
		      $config['max_size'] = 10000; // velicina, sirina itd...
              $config['max_width'] = 1024;
              $config['max_height'] = 768;
		      $config['file_name'] = $id;  // ime nove slike je id autora (stigao iz hiden input-a forme)
		      $this->load->library('upload', $config); //ucitaj upload library
			  $this->upload->do_upload('slika2'); // upload-uj novu sliku
			  $data['uspeh'] = 'Uspešno ste izmenili sliku autora: '.$ime;
			}	  
		  $this->load->model('internetnovinemodel'); // ucitaj model
		  $rezultat = $this->internetnovinemodel->prepraviautora($id, $email, $ime, $password);// zovi metod za update autora u modelu
		  if($rezultat != 0){ // ako je upisano nesto u bazu           			
              $data['vju'] = 'IN Admin';
			  if($data['uspeh'] == ''){
			  $data['uspeh'] = 'Uspešno ste izmenili podatke autora:'.$ime;
			  }else{
			    $data['uspeh'] = 'Uspešno ste izmenili podatke i sliku autora: '.$ime;
			  }
              $data['ime'] = $ime;   
			  $data['email'] = $email;
			  $data['pass'] = $pass;
		      $this->load->view('novineheader', $data);
              $this->load->view('adminpanel', $data);
	          $this->load->view('novinefooter');        
		  }elseif($rezultat == 0 && empty($_FILES['slika2']['name'])){ // ako nije nista upisano u bazu jer korisnik nije menjao podatke i nije uneo novu skliku
		    $data['vju'] = 'IN Admin';
			$data['uspeh'] = '';
			$data['validacijaerrors'] = 'Niste uneli nijednu prepravku.';
            $this->load->view('novineheader', $data);
            $this->load->view('adminpanel', $data);
	        $this->load->view('novinefooter');
		  }elseif($rezultat == 0 && !empty($_FILES['slika2']['name'])){// ako nije nista upisano u bazu jer korisnik nije menjao podatke ali je uneo sliku
			$data['vju'] = 'IN Admin';
			//$data['uspeh'] = 'Uspešno ste izmenili sliku autora:'.$ime;
			$data['ime'] = $ime;   
			$data['email'] = $email;
			$data['pass'] = $pass;
		    $this->load->view('novineheader', $data);
            $this->load->view('adminpanel', $data);
	        $this->load->view('novinefooter');
		  }
		}
	  }else{ // ako nije ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
		$this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ funkcije prepraviautora()
	
	//-------------------------------------------------------------------------------------------------
	
	// metod za slanje adminovog maila autorima iz admin panela, napravljen u config folderu fajl email.php sa podesavanjima za slanje sa kantarion35@gmail.com adrese
	public function saljimail(){
	$provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ // ako je ulogovan
	    $emailovi = $this->input->post('usermail'); // napravi array sa adresama onih autora koji su cekirani u formi u adminpanel-u
	    $naslov = $this->input->post('naslovmaila');
	    $sadrzaj = $this->input->post('sadrzajmaila');
	    $this->load->library('email');//ucitaj library
	    $this->email->set_newline("\r\n");
	    $this->email->from($this->session->userdata('usermail'), $this->session->userdata('username'));//ovde su adminov email(kantarion35@gmail.com) i username(Admin)
	    $this->email->to($emailovi);//kome se salje
	    $this->email->subject($naslov);//naslov
	    $this->email->message($sadrzaj);//sadrzaj mail-a
		if(isset($_FILES['fajl']['name']) && !empty($_FILES['fajl']['name'])){ // ako je dodat fajl kao attach emailu, prvo uploaduj fajl u images/attach folder pa ga odatle kaci na mail
		  $file_name = $_FILES['fajl']['name'];
          $starifajl = 	'./images/attach/'.$_FILES['fajl']['name'];	
		  if(file_exists($starifajl)){
            unlink($starifajl);  // obrisi staru sliku
          }
		  // konfiguracija za upload library
		  $config['upload_path'] = './images/attach/'; // folder za attach-mente
          $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf'; // dozvoljeni tipovi fajlova
		  $config['max_size'] = 10240; // velicina, sirina itd...
          $config['max_width'] = 5000;
          $config['max_height'] = 3000;
		  $config['file_name'] = $file_name;  // ime je origanolno ime koje je fajl imao prilikom uploada
		  $this->load->library('upload', $config); //ucitaj upload library
	      $this->upload->do_upload('fajl'); // upload-uj fajl
		  $file = './images/attach/'.$file_name; // putanja za attach
		  // $data['fajl'] = $file;
		  // $this->load->view('novineview2', $data);
	      $this->email->attach($file);//attach-uj fajl na mail
		}
	    if($this->email->send()){//salji mail, i ako uspe - 
		  $data['vju'] = 'IN Admin';	  
		  $adrese = implode(", ", $emailovi);
		  $data['poslatoautorima'] = $adrese;
	      $data['uspehemail'] = 'Uspešno ste poslali mailove';
		  $this->load->view('novineheader', $data);
          $this->load->view('adminpanel', $data);
	      $this->load->view('novinefooter'); 
	    }else{ // ako ne uspe slanje maila
		  $data['vju'] = 'IN Admin';
	      $data['validacijaerrors'] = 'Slanje Emaila je bilo neuspešno, pokušajte ponovo.';
		  $this->load->view('novineheader', $data);
          $this->load->view('adminpanel', $data);
	      $this->load->view('novinefooter'); 
	    }
	  }else{// ako nije ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }	
	} // KRAJ funkcije saljimail()
	
	
	//-------------------------------------------------------------------------------------------------
	
	//metod prikazuje neodobrene tekstove(klik na dugme #neodobrenitekstovidugme u admin panel-u)
	public function prikazineodobrenetekstove(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){
	    $limit = $this->input->post('limit'); // samo salje u model limit i offset koji su stigli ajax-om
	    $offset = $this->input->post('offset'); 
	    $this->load->model('internetnovinemodel');
	    $rezultat = $this->internetnovinemodel->prikazineodobrenetekstove($limit, $offset);
	    $this->output->set_output(json_encode($rezultat)); // vrati json
	  }else{ // ako nije ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ METODA prikazineodobrenetekstove()
	
	//metod koji vadi samo tekst teksta iz baze poziva se kad se u meniju neodobreni tekstovi u adminpanel-u -
	// - klikne dugme 'prikazi tekst' nekog teksta koji ceka da bude odobren
	public function prikazineodobrentekst(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ 
	    $id = $this->input->post('id');
		$this->load->model('internetnovinemodel');
	    $rezultat = $this->internetnovinemodel->prikazineodobrentekst($id);
	    $this->output->set_output(json_encode($rezultat)); // vrati json
	  }else{ // ako nije ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ METODA prikazineodobrentekst()
	
	//metod odobritekst, poziva ga klik na dugme odobri tekst u admin panelu i on samo u model salje id teksta i menja kolonu odobren iz 0 u 1
	public function odobritekst(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ 
	    $id = $this->input->post('id');
		$this->load->model('internetnovinemodel');
	    $rezultat = $this->internetnovinemodel->odobritekst($id); //pozovi metod iz modela i posalji id
	    $this->output->set_output(json_encode($rezultat)); // vrati json
	  }else{ // ako nije ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ METODA odobritekst()
	
	//brisanje teksta u bazi tj klik na dugme u formi za odobravnje ili brisanje tekstova u adminpanel-u
	// stize AJAX
	public function obrisitekst(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ // ako je user ulogovan kao admin
	    $id = $this->input->post('id'); // uzmi id teksta i proveru za mapu i slike
		$slike = $this->input->post('slike');
		$mapa = $this->input->post('mapa');
		$this->load->model('internetnovinemodel');
		$rezultat = $this->internetnovinemodel->obrisitekst($id, $mapa); //pozovi metod iz modela i posalji id i mapu posto ako je mapa == 1 treba model da brise i u googlemap tabeli red
		if($rezultat){ // ako obrise
		  $brisanje['rezultat'] = 1; // vrati ovo jquery fajlu
		  $this->output->set_output(json_encode($brisanje));
		  if($slike == 1){ // ako je bilo slika brisi slike i folder u kom su bile
		    $this->load->helper('file'); // ucitaj helper'file'
		    delete_files('./images/tekstovi/'.$id, TRUE); // brisi slike
			rmdir('./images/tekstovi/'.$id); // obrisi folder u kom su bile slike	    
		  } 
		}else{ // ako je doslo do neke greske prilikom brisanja i model je vratio 0
		  $brisanje['rezultat'] = 0; // vrati ovo jquery fajlu
		  $this->output->set_output(json_encode($brisanje));
		}
	  }else{ // ako nije ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ METODA obrisitekst()
	
	//-------------------------------------------------------------------------------------------------
	
	//metod prikazuje odobrene tekstove(klik na dugme #odobrenitekstovidugme u admin panel-u)
	public function prikaziodobrenetekstove(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){
	    $limit = $this->input->post('limit'); // samo salje u model limit i offset koji su stigli ajax-om
	    $offset = $this->input->post('offset'); 
	    $this->load->model('internetnovinemodel');
	    $rezultat = $this->internetnovinemodel->prikaziodobrenetekstove($limit, $offset);
	    $this->output->set_output(json_encode($rezultat)); // vrati json
	  }else{ // ako nije ulogovan
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ METODA prikaziodobrenetekstove()
	
	// funkcija za zabranu teksta koji je odobren tj kolona odobren je == 1
	// stize AJAX
	public function zabranitekst(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){
	    $id = $this->input->post('id');// uzmi id teksta
		$this->load->model('internetnovinemodel');
	    $rezultat = $this->internetnovinemodel->zabranitekst($id); //pozovi metod iz modela i posalji id
	    $this->output->set_output(json_encode($rezultat)); // vrati json
	  }else{
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ METODA zabranitekst()
	
	//funkcija za proglasavanje teksta glavnim tekstom
	// stize AJAX
	public function proglasiglavni(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){
	    $id = $this->input->post('id');// uzmi id teksta
		$this->load->model('internetnovinemodel');
		$rezultat = $this->internetnovinemodel->proglasiglavni($id); //pozovi metod iz modela i posalji id
	    $this->output->set_output(json_encode($rezultat)); // vrati json
	  }else{
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ funkcije proglasiglavni()
	
	//-------------------------------------------------------------------------------------------------
	// METODI ZA RAD SA TEKSTOVIMA	
	//-------------------------------------------------------------------------------------------------
	
	// metod koji upisuje novi tekst u tabelu 'tekst' kad se popuni forma novi tekst u autor panelu
	public function novitekst(){
	  $provera = $this->_obavezanloginautora(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ 
	    $this->load->library('form_validation'); // iako jquery radi validaciju pre slanja forme iz adminpanela uradi jos jednom za svaki slucaj
	    $this->form_validation->set_rules('naslov', 'naslov', 'required',
            array('required' => 'Morate uneti Naslov teksta.')
          );
		$this->form_validation->set_rules('podnaslov', 'podnaslov', 'required',
            array('required' => 'Morate uneti Podnaslov teksta.')
          );
		$this->form_validation->set_rules('tekst', 'tekst', 'required',
            array('required' => 'Morate uneti Tekst.')
          );
		// $this->form_validation->set_rules('YTlink', 'YTlink', 'valid_url',
            // array('valid_url' => 'Morate uneti YouTube URL u polje za link ka YouTube-u.')
          // );
		if($this->form_validation->run() == FALSE){ // ako ne prodje validacija
		  $data['naslov'] =  $this->input->post('naslov');
		  $data['podnaslov'] =  $this->input->post('podnaslov');
		  $data['tekst'] =  $this->input->post('tekst');
		  $data['vju'] = 'IN Autor';
	      $data['validacijaerrors'] =  validation_errors(); // posalji u autorpanel koji su errori
		  $this->load->view('novineheader', $data);
          $this->load->view('autorpanel', $data);
	      $this->load->view('novinefooter');
	    }else{
		 //ako prodje validacijauzmi autorov unos
		  $idautora = $this->input->post('idautora');
		  $rubrika = $this->input->post('rubrika');
		  $naslov = $this->input->post('naslov');
		  $podnaslov = $this->input->post('podnaslov');
		  $tekst = $this->input->post('tekst');
		  $yt = $this->input->post('YTlink');
		  $latituda = $this->input->post('latituda');
		  $longituda = $this->input->post('longituda');
		  $zoom = $this->input->post('zoom');
		  $this->load->model('internetnovinemodel'); // ucitaj model
		  if(isset($_FILES['tekstslika1']['name']) && !empty($_FILES['tekstslika1']['name'])){ // ako ima uploadovanih slika
		    $slika = 1;
		  }else{ // ako nema slika
		    $slika = 0;
		  }
		  $rezultat = $this->internetnovinemodel->novitekst($idautora, $rubrika, $naslov, $podnaslov, $tekst, $slika, $yt, $latituda, $longituda, $zoom);// zovi metod za unos novog teksta u modelu
		  if($rezultat[0] == 2){ // ako ne uspe unos
		    $data['vju'] = 'IN Autor';
	        $data['validacijaerrors'] =  'Došlo je do greške u bazi, pokušajte ponovo.'; // posalji u autorpanel koji su errori
		    $this->load->view('novineheader', $data);
            $this->load->view('autorpanel', $data);
	        $this->load->view('novinefooter');
		  }elseif($rezultat[0] == 1){ // ako uspe unos
		    if($slika == 1){ // ako ima uneta slika
			  mkdir('./images/tekstovi/'.$rezultat[1]); // napravi folder u image/tekstovi kom je ime id_teksta iz baze
			  $config['upload_path'] = './images/tekstovi/'.$rezultat[1]; // folder za slike uz tekstove
              $config['allowed_types'] = 'jpg|jpeg|png'; // dozvoljeni tipovi fajlova
		      $config['max_size'] = 10240; // velicina, sirina itd...
              $config['max_width'] = 5000;
              $config['max_height'] = 3000;
		      $this->load->library('upload', $config); //ucitaj upload library
	          $this->upload->do_upload('tekstslika1'); // uploaduj prvu sliku
			  $this->upload->do_upload('tekstslika2'); // uploaduj drugu sliku
			  $this->load->helper('file'); // file helper za menjanje mena slika posto ce se zvati 1.jpg prva slika ii 2.jpg druga slika
			  $slike = get_filenames('./images/tekstovi/'.$rezultat[1]); // uzmi u array imena unetih slika
			  rename('./images/tekstovi/'.$rezultat[1].'/'.$slike[0], './images/tekstovi/'.$rezultat[1].'/1.jpg'); // renameuj prvu sliku
			  rename('./images/tekstovi/'.$rezultat[1].'/'.$slike[1], './images/tekstovi/'.$rezultat[1].'/2.jpg'); // renameuj drugu sliku
			}
			// posalji u view poruku da je uspesno unet novi tekst i naslov teksta
		    $data['vju'] = 'IN Autor';
	        $data['rez'] = $rezultat;
			$data['uspeh'] = 'Uneli ste novi tekst, Naslov:'.$naslov;
            $this->load->view('novineheader', $data);
	        $this->load->view('autorpanel', $data);
	        $this->load->view('novinefooter');
		  }		  
		}    
	  }else{ // ako user nije ulogovan tj _obavezanloginautora() je vratio  null
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ funkcije novitekst()
	
	//-------------------------------------------------------------------------------------------------
	
	//poziva se kad autor klikne dugme Prepravi Tekst u autorpanel.php da bi bili prikazani tekstovi - 
	// tog autora koje on moze da prepravi 
	public function prikazitekstovejednogautora(){
	  $provera = $this->_obavezanloginautora(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ 
	    $idautora = $this->input->post('id');
		$limit = $this->input->post('limit'); // salji u model limit i offset koji su stigli ajax-om
	    $offset = $this->input->post('offset'); 
	    $this->load->model('internetnovinemodel'); // ucitaj model
		$rezultat = $this->internetnovinemodel->prikazitekstovejednogautora($idautora, $limit, $offset);
		$this->output->set_output(json_encode($rezultat)); // vrati json
	  }else{ // ako user nije ulogovan tj _obavezanloginautora() je vratio  null
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter'); 
	  }
	} // KRAJ funkcije prikazitekstovejednogautora()
	
	//-------------------------------------------------------------------------------------------------
	
	//metod se poziva iz autor panela kad se popuni forma za prepravku jednog teksta   
	public function prepravitekst(){
	  $provera = $this->_obavezanloginautora(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ 
	    $this->load->library('form_validation'); // iako jquery radi validaciju pre slanja forme iz adminpanela uradi jos jednom za svaki slucaj
	    $this->form_validation->set_rules('naslovprepravka', 'naslovprepravka', 'required',
            array('required' => 'Morate uneti Naslov teksta.')
          );
		$this->form_validation->set_rules('podnaslovprepravka', 'podnaslovprepravka', 'required',
            array('required' => 'Morate uneti Podnaslov teksta.')
          );
		$this->form_validation->set_rules('tekstprepravka', 'tekstprepravka', 'required',
            array('required' => 'Morate uneti Tekst.')
          );
		if($this->form_validation->run() == FALSE){ // ako ne prodje validacija
		  // $data['naslov'] =  $this->input->post('naslovprepravka');
		  // $data['podnaslov'] =  $this->input->post('podnaslovprepravka');
		  // $data['tekst'] =  $this->input->post('tekstprepravka');
		  $data['vju'] = 'IN Autor';
	      $data['validacijaerrors'] =  validation_errors(); // posalji u autorpanel koji su errori
		  $this->load->view('novineheader', $data);
          $this->load->view('autorpanel', $data);
	      $this->load->view('novinefooter');
		}else{ //ako prodje validacijauzmi autorov unos
		  $idautora = $this->input->post('idautora');
		  $idtekstaprepravka = $this->input->post('idtekstaprepravka'); //prepravljen tekst
		  $stareslike = $this->input->post('stareslike'); // provera da li je bilo slika pre posto ako jeste treba da ostane 1 u koloni slike
		  $starigm = $this->input->post('starigm'); // provera da li je bilo mape pre posto ako jeste treba da ostane 1 u koloni googlemap
		  $rubrikaprepravka = $this->input->post('rubrikaprepravka'); //prepravljena rubrika
		  $naslovprepravka = $this->input->post('naslovprepravka'); //prepravljen naslov
		  $podnaslovprepravka = $this->input->post('podnaslovprepravka'); //prepravljen podnaslov
		  $tekstprepravka = $this->input->post('tekstprepravka'); //prepravljen tekst
		  $ytprepravka = $this->input->post('YTlinkprepravka'); //prepravljen YT link
		  $latitudaprepravka = $this->input->post('latitudaprepravka'); //prepravljena LAT
		  $longitudaprepravka = $this->input->post('longitudaprepravka'); //prepravljena LONG
		  $zoomprepravka = $this->input->post('zoomprepravka'); //prepravljen zoom
		  if(isset($_FILES['tekstslika1prepravka']['name']) && !empty($_FILES['tekstslika1prepravka']['name'])){ // ako ima uploadovanih slika
		    $slikaprepravka = 1;
		  }else{ // ako nema slika
		    $slikaprepravka = 0;
		  }
		  $this->load->model('internetnovinemodel'); // ucitaj model
		  $rezultat = $this->internetnovinemodel->prepravitekst($idautora, $idtekstaprepravka, $rubrikaprepravka, $naslovprepravka, $podnaslovprepravka, $tekstprepravka, $slikaprepravka, $ytprepravka, $latitudaprepravka, $longitudaprepravka, $zoomprepravka, $stareslike, $starigm);// zovi metod za prepravljanje teksta u modelu i salji mu varijable popunjene iz unosa u formu
		  if($rezultat[0] == 2){ // ako ne uspe unos
		    $data['vju'] = 'IN Autor';
	        $data['validacijaerrors'] =  'Došlo je do greške u bazi, pokušajte ponovo.'; // posalji u autorpanel koji su errori
		    $this->load->view('novineheader', $data);
            $this->load->view('autorpanel', $data);
	        $this->load->view('novinefooter');
		  }elseif($rezultat[0] == 1){  // ako uspe unos	    
			if($slikaprepravka == 1){ // ako ima uneta slika
			  if($stareslike != 1){ // ako pre tekst nije imao slike napravi folder za slike u images/tekstovi/idteksta
			    mkdir('./images/tekstovi/'.$idtekstaprepravka); // napravi folder u image/tekstovi kom je ime id teksta iz forme za prepravku		    
			  }elseif($stareslike == 1){ // ako je imao i pre slike a nove su dodate obrisi stare
			    $this->load->helper('file'); // ucitaj helper'file'
		        delete_files('./images/tekstovi/'.$idtekstaprepravka, TRUE); // brisi slike
			  }
			  //radi upload slika, u folder images/tekstovi/$idtekstaprepravka da bi folder imao ime kao id teksta u bazi
			  $config['upload_path'] = './images/tekstovi/'.$idtekstaprepravka; // folder za slike uz tekstove
              $config['allowed_types'] = 'jpg|jpeg|png'; // dozvoljeni tipovi fajlova
		      $config['max_size'] = 10240; // velicina, sirina itd...
              $config['max_width'] = 5000;
              $config['max_height'] = 3000;
		      $this->load->library('upload', $config); //ucitaj upload library
	          $this->upload->do_upload('tekstslika1prepravka'); // uploaduj prvu sliku
			  $this->upload->do_upload('tekstslika2prepravka'); // uploaduj drugu sliku
			  $this->load->helper('file'); // file helper za menjanje mena slika posto ce se zvati 1.jpg prva slika ii 2.jpg druga slika
			  $slike = get_filenames('./images/tekstovi/'.$idtekstaprepravka); // uzmi u array imena unetih slika
			  rename('./images/tekstovi/'.$idtekstaprepravka.'/'.$slike[0], './images/tekstovi/'.$idtekstaprepravka.'/1.jpg'); // renameuj prvu sliku
			  rename('./images/tekstovi/'.$idtekstaprepravka.'/'.$slike[1], './images/tekstovi/'.$idtekstaprepravka.'/2.jpg'); // renameuj drugu sliku
			}
		    // posalji u view poruku da je tekst uspesno prepravljen
		    $data['vju'] = 'IN Autor';
	        $data['rez'] = $rezultat;
			$data['uspeh'] = 'Tekst:'.$naslovprepravka.' je uspešno prepravljen!';
            $this->load->view('novineheader', $data);
	        $this->load->view('autorpanel', $data);
	        $this->load->view('novinefooter');
		  }		
		}
	  }else{  // ako user nije ulogovan tj _obavezanloginautora() je vratio  null
	    $data['vju'] = 'ulogujse';
	    $data['loginerror'] = 'Morate biti ulogovani.';
        $this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter'); 
	  }
	} // KRAJ funkcije prepravitekst()

	
	//-------------------------------------------------------------------------------------------------
	public function index2(){
	  $str = 'pikolo';
	  $shastr = sha1($str);
	  $data['sha'] = $shastr;
      $this->load->view('novineview2', $data);	
	}
	
	
	//-------------------------------------------------------------------------------------------------
	// SAMO PROBA ZA GOOGLE MAP
	//-------------------------------------------------------------------------------------------------
	
	public function probaguglmap(){
	  $this->load->view('guglmapvju2');
	}
  } 

?>




















