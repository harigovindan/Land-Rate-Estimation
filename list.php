<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=&sensor=false&libraries=places"></script>
<style type="text/css">
            #map {
                height: 400px;
                width: 100%;
                border: 1px solid #333;
                margin-top: 0.6em;
            }
.rate{
  border: 0px solid #999;
}
        </style>
<script>
var temp = new Array();
var temp1=new Array();
function print1()
{
var final_rate= <?php echo json_encode($_COOKIE['final_rate']); ?>;
var storedNames = JSON.parse(sessionStorage.getItem("places"));
temp = storedNames.split("@");
temp1=final_rate.split("@");
for(i = 1; i < temp.length; i++)
  {
    var table = document.getElementById("myTable");
    var row = table.insertRow(i-1);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    cell1.innerHTML = temp[i];
	if(temp1[i-1].includes("HTTP/1.1 200 OK"))
	   {
	    	cell2.innerHTML = "Not Specified";
	   }
	else
	   {
	    	cell2.innerHTML = temp1[i-1];
           }
    //document.write("<b>Address "+ i + "</b>: " + temp[i] + "<br/>");
//alert('done');
//$('#address').val(temp[i]);
var opt = document.createElement("option");  
        // Assign text and value to Option object
        opt.text = temp[i];
        opt.value = temp[i];
 document.getElementById('address').options.add(opt);
	//alert('done');
    showMap();
  }
}
//localStorage.removeItem("places");
/*google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('address'));
            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();
            });
        });*/
		
            $(function(){
                $('.chkbox').click(function(){
                    $(':checkbox').attr('checked',false);
                    $('#'+$(this).attr('id')).attr('checked',true);
                    search_types(map.getCenter(),1);
                });
                
            });
            
	    var near = [];
            var map;
            var infowindow;
            var markersArray = [];
            var pyrmont = new google.maps.LatLng(12.9498453, 80.19727020000005);
            var marker;
            var geocoder = new google.maps.Geocoder();
            var infowindow = new google.maps.InfoWindow();
            // var waypoints = [];                  
            function initialize() {
                map = new google.maps.Map(document.getElementById('map'), {
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: pyrmont,
                    zoom: 14
                });
                infowindow = new google.maps.InfoWindow();
                //document.getElementById('directionsPanel').innerHTML='';
                search_types_init();
               }

            function createMarker(place,icon) {
                var placeLoc = place.geometry.location;
                var marker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location,
                    icon: icon,
                    visible:true  
                    
                });
                
                markersArray.push(marker);
		if(near.length<=4)
		{
			near.push("<b>" + place.name + "</b>" + " - "+place.vicinity);
		}
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.setContent("<b>Name:</b>"+place.name+"<br><b>Address:</b>"+place.vicinity+"<br><b>Rating:</b>"+place.rating+"<br>");
                    infowindow.open(map,this);
                });
               
            }
            var source="";
            var dest='';
            
	    function search_types_init(latLng){
                clearOverlays(); 
              	document.getElementById('details').innerHTML = "";
                if(!latLng){
                    var latLng = pyrmont;
                }
                var type = $('.chkbox:checked').val();
                var icon = "images/"+type+".png";
                
	 
                var request = {
                    location: latLng,
                    radius: 3000,
                    types: [type] //e.g. school, restaurant,bank,bar,city_hall,gym,night_club,park,zoo
                };
               
                var service = new google.maps.places.PlacesService(map);
                service.search(request, function(results, status) {
                    map.setZoom(14);
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        for (var i = 0; i < results.length; i++) {
                            results[i].html_attributions='';
                            createMarker(results[i],icon);
                        }
                    }
                });
                
             }

            function search_types(latLng,flag){
                clearOverlays(); 
              	document.getElementById('details').innerHTML = "";
                if(!latLng){
                    var latLng = pyrmont;
                }
                var type = $('.chkbox:checked').val();
                var icon = "images/"+type+".png";
                
	 
                var request = {
                    location: latLng,
                    radius: 3000,
                    types: [type] //e.g. school, restaurant,bank,bar,city_hall,gym,night_club,park,zoo
                };
               
                var service = new google.maps.places.PlacesService(map);
                service.search(request, function(results, status) {
                    map.setZoom(14);
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        for (var i = 0; i < results.length; i++) {
                            results[i].html_attributions='';
                            createMarker(results[i],icon);
                        }
					if(flag!=0)
					print();
                    }
                });
                
             }
            
	    function print(){
		if(near){
			for(i in near){
			document.getElementById('details').innerHTML = document.getElementById('details').innerHTML + "<br/>" + near[i];
				     }
			near.length = 0;
		}
	}
            
            // Deletes all markers in the array by removing references to them
            function clearOverlays() {
                if (markersArray) {
                    for (i in markersArray) {
                        markersArray[i].setVisible(false)
                    }
                    //markersArray.length = 0;
                }
            }
            google.maps.event.addDomListener(window, 'load', initialize);
            
            function clearMarkers(){
                $('#show_btn').show();
                $('#hide_btn').hide();
                clearOverlays()
            }
            function showMarkers(){
                $('#show_btn').hide();
                $('#hide_btn').show();
                if (markersArray) {
                    for (i in markersArray) {
                        markersArray[i].setVisible(true)
                    }
                     
                }
            }
            
            function showMap(){
                var imageUrl = 'http://chart.apis.google.com/chart?cht=mm&chs=24x32&chco=FFFFFF,008CFF,000000&ext=.png';
                var markerImage = new google.maps.MarkerImage(imageUrl,new google.maps.Size(24, 32));
                var input_addr=$('#address').val();
                geocoder.geocode({address: input_addr}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var latitude = results[0].geometry.location.lat();
                        var longitude = results[0].geometry.location.lng();
                        var latlng = new google.maps.LatLng(latitude, longitude);
                        if (results[0]) {
                            map.setZoom(14);
                            map.setCenter(latlng);
                            marker = new google.maps.Marker({
                                position: latlng, 
                                map: map,
                                icon: markerImage,
                                draggable: true 
                                
                            }); 
                            //$('#btn').hide();
                            $('#latitude,#longitude').show();
                            $('#address').val(results[0].formatted_address);
                            $('#latitude').val(marker.getPosition().lat());
                            $('#longitude').val(marker.getPosition().lng());
                            infowindow.setContent(results[0].formatted_address);
                            infowindow.open(map, marker);
                            search_types(marker.getPosition(),0);
                            google.maps.event.addListener(marker, 'click', function() {
                                infowindow.open(map,marker);
                                
                            });
                        
                        
                            google.maps.event.addListener(marker, 'dragend', function() {
                              
                                geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        if (results[0]) {
                                            $('#btn').hide();
                                            $('#latitude,#longitude').show();
                                            $('#address').val(results[0].formatted_address);
                                            $('#latitude').val(marker.getPosition().lat());
                                            $('#longitude').val(marker.getPosition().lng());
                                        }
                                        
                                        infowindow.setContent(results[0].formatted_address);
                                        var centralLatLng = marker.getPosition();
                                        search_types(centralLatLng,1);
                                        infowindow.open(map, marker);
                                    }
                                });
                            });
                            
                        
                        } else {
                            alert("No results found");
                        }
                    } else {
                        alert("Geocoder failed due to: " + status);
                    }
                });
            }   
</script>
<html>
<head>
</head>
<body onload="print1()" bgcolor="#ECFDDF">
<center>
<b>Estimated Land Rates:</b><br/>
<table id="myTable" border="1" class="rate">
</table>
</center>
<br/><br/>
<div>            <center><table border="0" cellspacing="0" cellpadding="3">
                <tr>
                <td> <input type="checkbox" name="mytype" class="chkbox" id="school"  value="school" /><label for="school">School</label><br/></td>
                <td><input type="checkbox" name="mytype" class="chkbox" id="restaurant"  value="restaurant"/><label for="restaurant" 				>Restaurant</label></td>
		<td> <input type="checkbox" name="mytype" class="chkbox"  id="hospital"  value="hospital"/><label for="hospital" >Hospital</label></td>
                <td><input type="checkbox" name="mytype"  class="chkbox" id="bus_station"  value="bus_station"/><label for="bus_station" >Bus 		Stopedge</label></td>
		<td><input type="checkbox" name="mytype"  class="chkbox" id="park"  value="park"/><label for="park" >Park</label></td>
                <td><input type="checkbox" name="mytype"  class="chkbox" id="bank"  value="bank"/><label for="bank" >Bank</label></td>
<td><input type="checkbox" name="mytype"  class="chkbox" id="bar"  value="bar"/><label for="bar" >Bar</label></td>
                    <td><input type="checkbox" name="mytype"  class="chkbox" id="movie_theater"  value="movie_theater"/><label for="movie_theater" >Movie Theater</label></td>
<td><input type="checkbox" name="mytype"  class="chkbox" id="night_club"  value="night_club"/><label for="night_club" >Night Club</label></td>
                    <td><input type="checkbox" name="mytype"  class="chkbox" id="zoo"  value="zoo"/><label for="zoo" >Zoo</label><br/></td>
<td><input type="checkbox" name="mytype"  class="chkbox" id="gym"  value="gym"/><label for="gym" >Gym</label></td>
                    <td><input type="checkbox" name="mytype"  class="chkbox" id="atm"  value="atm"/><label for="atm" >ATM</label></td>
<td><input type="checkbox" name="mytype"  class="chkbox" id="spa"  value="spa"/><label for="spa" >Spa</label></td>
       		</tr>
		<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>
            </table>
		</center>
        </div>
        <center><label><b>Address:</b> </label>
	<select name="address" id="address">
	</select>
	<!--<input id="address" name="address" type="hidden" style="width:400px;" value="Sunnambu Kolathur, Chennai" />-->
        <input type="button" value="Search" id="btn" onClick="showMap();"/></center>
        <br/>
        <div id="map"></div>
       <!-- <input type="text" id="latitude" style="display:none;" placeholder="Latitude"/>-->
       <!-- <input type="text" id="longitude" style="display:none;" placeholder="Longitude"/>-->
       <!-- <input type="button"  id="hide_btn" value="hide markers" onClick="clearMarkers();" />-->
        <input type="button" id="show_btn" value="show  markers" onClick="showMarkers();" style="display:none;" />
	<p id="final_rate" name="final_rate"></p>
        <div id="test">
		<p id="details"></p>
	</div>
</body>
</html>
