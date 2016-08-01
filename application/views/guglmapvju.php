<!DOCTYPE html>
 <html lang="en">
 <head>
  <meta charset="UTF-8">

  <title>guglmap</title>
  <link rel="stylesheet" href="<?=base_url()?>css/style.css">
  <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
  <!--<script src="http://maps.googleapis.com/maps/api/js"></script>-->
  <!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCM8q3fvXDbopwqbaswTrTU-hlQyLrkf2w" async defer ></script>-->
  <script type="text/javascript">
	  function initialize() {
	    var mapProp = {
          center:new google.maps.LatLng(51.508742,-0.120850),
          zoom:5,
          mapTypeId:google.maps.MapTypeId.SATELLITE
        };
        var map = new google.maps.Map(document.getElementById("map"),mapProp);	
		
		 google.maps.event.addListener(map, 'click', function(event){
         alert("Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng()); 
       });
		
	   }
	   google.maps.event.addDomListener(window, 'load', initialize);

	  
	 	   
  </script>
 </head>
 <body>
  <h1>GUGL MAP VJU!!!</h1>
   <div id="map" style="width:500px;height:380px;"></div>
 
 </body>
</html>
  
  
  

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	