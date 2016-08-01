


var Gm = function(){



  //-------------------------------------------------------------------------------------------------------------------------
  // FUNKCIJA ZA PRIKAZ GOOGLE MAP-e
  //-------------------------------------------------------------------------------------------------------------------------
  var x = 44.818611; // koordinate beograda
  var y = 20.468056;
  var center = new google.maps.LatLng(x, y);
  var map;
	  
  function initialize(){
	var mapOptions = {
	      zoom: 14,
		  center: center,
		  //mapTypeId: google.maps.MapTypeId.TERRAIN 
		  //mapTypeId: google.maps.MapTypeId.SATELLITE
		  mapTypeId: google.maps.MapTypeId.HYBRID // HYBRID je mesavina satelitskog snimka i mape tako da ima ucrtane granice imena gradova i slicno...
	};
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	
	// hendler za klik na googlemap-u	
	google.maps.event.addListener(map, 'click', function(event){ 
	  $('#latitudaprepravka').val(event.latLng.lat()); // popuni input za latitiudu
	  $('#longitudaprepravka').val(event.latLng.lng()); // popuni input za longitudu
	  $('#zoomprepravka').val(map.getZoom()); // popuni input za zoom level
    });
  }


};






