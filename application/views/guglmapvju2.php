<!DOCTYPE html>
 <html lang="en">
 <head>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="<?=base_url()?>js/in.js"></script>
  <meta charset="UTF-8">
  <title>guglmap</title>
  <style>
    html, body, #map-canvas{
	  height: 87%;
	  margin: 0px;
	  padding: 0px;
	}
  </style>
  <!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>-->
  <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
  <script>
    $(document).ready (function(){
	  var x = 44.818611;
	  var y = 20.468056;
	  var center = new google.maps.LatLng(x, y);
	  var map;
	  
	  function initialize(){
	    var mapOptions = {
	      zoom: 14,
		  center: center,
		  //mapTypeId: google.maps.MapTypeId.TERRAIN 
		  mapTypeId: google.maps.MapTypeId.SATELLITE
	    };
	   	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		
		google.maps.event.addListener(map, 'click', function(event){
          alert("Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng()); 
        });		
	   
	  }
	 
	 //google.maps.event.addDomListener(document.getElementById('zovimapu'), 'click', initialize);
	 $('#zovimapu').click(initialize);
	  
      	
	  //google.maps.event.addDomListener(window, 'load', initialize);	  	  
	
	/* function placeMarker(location){
	  var marker = new google.maps.Marker({
	    position: location,
		map: map
	  });
	} */
	
  });
  </script>
 </head>
 <body>

   <div id="map-canvas"></div>
   <button id="zovimapu">Mapa</button>
 </body>
 </html>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 