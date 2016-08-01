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
	  $data['vju'] = 'internetnovine';
	  $this->load->view('novineheader', $data);
      $this->load->view('novineview');
	  $this->load->view('novinefooter');
	}
	
	//-------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------
	
	//metod za proveru da li je user ulogovan vraca true ili false, metod je private i mogu ga zvat samo drugi metodi ove klase
	private function _obavezanlogin(){
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
		    $this->session->set_userdata($userdata); // ubaci array sa podatcima user-a u sesiju
			//$data['userid'] = $this->session->userdata('userid');
			//$data['usermail'] = $this->session->userdata('usermail');
		    $data['rezultat'] = $rezultat;
			$data['vju'] = 'IN Admin';
			$data['cestitka'] = 1; // ako iz ovog metoda ulazi u adminpanel view da prikaze poruku useru da se uspesno ulogovao
			$this->load->view('novineheader', $data); // ucitaj vju-ove
			$this->load->view('adminpanel', $data);
			$this->load->view('novinefooter');
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
	
	// ako user tj. admin hoce u adminpanel view ali ne iz login metoda poziva se ovaj metod 
	public function adminpanel(){
	  $provera = $this->_obavezanlogin(); // pozovi metod _obavezanlogin() da proveri da li je user ulogovan
	  if($provera){ // ako jeste pusti ga da udje u admin panel
	  $data['vju'] = 'IN Admin';
	  $this->load->view('novineheader', $data);
	  $this->load->view('adminpanel', $data);
	  $this->load->view('novinefooter');
	  }else{ // ako nije pozovi drugi view i posalji poruku da mora biti ulogovan
	    $data['loginerror'] = 'Morate biti ulogovani.';
		$this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	} // KRAJ funkcije adminpanel()
	
	//-------------------------------------------------------------------------------------------------
	
	// metod za logout
	public function logout(){
	  $this->session->sess_destroy();
	  redirect('/');
	}
	
	//-------------------------------------------------------------------------------------------------
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
	    $data['loginerror'] = 'Morate biti ulogovani.';
		$this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	}
	
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
	    $data['loginerror'] = 'Morate biti ulogovani.';
		$this->load->view('novineheader', $data);
	    $this->load->view('novineview2', $data);
	    $this->load->view('novinefooter');
	  }
	}
	
	//-------------------------------------------------------------------------------------------------
	
	//meod za prepravljanje podataka jednog autora u admin panelu 
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
		  $id = $this->input->post('idprepravka');// uzmi unos u formu korisnika
		  $email = $this->input->post('mailprepravka');
		  $ime = $this->input->post('imeprepravka');
	      $pass = $this->input->post('passprepravka');
		  if($pass != ''){ // ako je stigao passwor sifriraj ga ako nije salji '' u model
		    $password = SHA1($pass); // sifriraj password
		  }else{
		    $password = '';
		  }
		  $this->load->model('internetnovinemodel'); // ucitaj model
		  $rezultat = $this->internetnovinemodel->prepraviautora($id, $email, $ime, $password);// zovi metod za update autora u modelu
		  if($rezultat != 0){ // ako je upisano nesto u bazu
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
			}            			
              $data['vju'] = 'IN Admin';
			  $data['uspeh'] = 'Uspešno ste izmenili podatke autora:'.$ime;
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
		    $file_name = './images/autori/'.$id.'.jpg';
			unlink($file_name); // obrisi staru sliku
			// konfiguracija za upload library
			$config['upload_path'] = './images/autori/'; // folder za slike autora
            $config['allowed_types'] = 'gif|jpg|jpeg|png'; // dozvoljeni tipovi slika
		    $config['max_size'] = 10000; // velicina, sirina itd...
            $config['max_width'] = 1024;
            $config['max_height'] = 768;
		    $config['file_name'] = $id; // ime nove slike je id autora (stigao iz hiden input-a forme)
		    $this->load->library('upload', $config); //ucitaj upload library
	        $this->upload->do_upload('slika2'); // upload-uj novu sliku
			$data['vju'] = 'IN Admin';
			$data['uspeh'] = 'Uspešno ste izmenili sliku autora:'.$ime;
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
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function index2(){
	  $str = 'pikolo';
	  $shastr = sha1($str);
	  $data['sha'] = $shastr;
      $this->load->view('novineview2', $data);	
	}
  }

?>





















