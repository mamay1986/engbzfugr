
  function initialize() {
    var mapOptions = {
      zoom: 12,
      center: new google.maps.LatLng(51.46598,46.11820),
	  disableDefaultUI: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
	  panControl: true,
	  zoomControl: true,

    }
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	geocoder = new google.maps.Geocoder();
	gmarkers = [];
	
	google.maps.event.addListener(map, 'click', function(event) {
		//alert(event.latLng);
		try{
			for (i in gmarkers) gmarkers[i].setMap(null);
			
			document.getElementById("addres_coord").value = event.latLng;
			var patern = /\((.*),(.*)\)/i;
			var str = document.getElementById("addres_coord").value;
			var array = str.match(patern)
			//var signImage = new google.maps.MarkerImage('/images/logo_map1.png', new google.maps.Size(100, 37), new google.maps.Point(0,0), new google.maps.Point(5, 37));
			var signLatLng = new google.maps.LatLng(array[1],array[2]);
			//alert('qq');
			var beachMarker = new google.maps.Marker({
				position: signLatLng,
				map: map,
				//icon: signImage
			});
			var infowindow = new google.maps.InfoWindow({
				//content: "<b><span style='from-size:14px'>" +item['name']+ "</span></b> <a href='#'>подробнее</a><br/><div style=\"float:left;\">" +item['addres']+ "</div><div style='float:right;padding-left: 10px;'><img width=\"50\" src='" +item['image']+ "' /></div>"
			});
			gmarkers.push(beachMarker);
			google.maps.event.addListener(beachMarker, 'click', function() {
				infowindow.open(map,beachMarker);
			});
		} catch(e) {
			return false;
		}
	});

	
	function ZoomControl(controlDiv, map) {
		// отмена увеличений справа
		//return;
		controlDiv.className = "zoomHandler";

		var zoomIN = document.createElement('DIV');
		zoomIN.className = "zoomInButton";
		zoomIN.style.cursor = 'pointer';
		zoomIN.title = '+';
		controlDiv.appendChild(zoomIN);

		google.maps.event.addDomListener(zoomIN, 'click', function() {
			map.setZoom(map.getZoom()+1);
		});
		
		var zoomOUT = document.createElement('DIV');
		zoomOUT.className = "zoomOutButton";
		zoomOUT.style.cursor = 'pointer';
		zoomOUT.title = '-';
		controlDiv.appendChild(zoomOUT);

		// Setup the click event listeners: simply set the map to Chicago
		google.maps.event.addDomListener(zoomOUT, 'click', function() {
			map.setZoom(map.getZoom()-1);
		});
	}

	var zoomControlDiv = document.createElement('DIV');
	var zoomControl = new ZoomControl(zoomControlDiv, map);

	zoomControlDiv.index = 1;
	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(zoomControlDiv);	
		
}

function addPlace(item){
	try{
		var patern = /\((.*),(.*)\)/i;
		var str = item['addres_coord'];
		var array = str.match(patern)
		//var signImage = new google.maps.MarkerImage('/images/logo_map1.png', new google.maps.Size(100, 37), new google.maps.Point(0,0), new google.maps.Point(5, 37));
		var signLatLng = new google.maps.LatLng(array[1],array[2]);
		var beachMarker = new google.maps.Marker({
			position: signLatLng,
			map: map,
			//icon: signImage
		});
		var infowindow = new google.maps.InfoWindow({
			//content: "<b><span style='from-size:14px'>" +item['name']+ "</span></b> <a href='#'>подробнее</a><br/><div style=\"float:left;\">" +item['addres']+ "</div><div style='float:right;padding-left: 10px;'><img width=\"50\" src='" +item['image']+ "' /></div>"
		});
		gmarkers.push(beachMarker);
		google.maps.event.addListener(beachMarker, 'click', function() {
			infowindow.open(map,beachMarker);
		});
	} catch(e) {
		return false;
	}
}

function showPlaces(id_cat,array){	
	document.getElementById("query_google_map").value = '';
	var items = array[id_cat];
	for (i in gmarkers) gmarkers[i].setMap(null);
	for(var i in items){
		addPlace(items[i]);
	}
}

function showPlacesAll(array){
	document.getElementById("query_google_map").value = '';
	initialize();
	for(var i in array){
		for(var j in array[i]){
			addPlace(array[i][j]);
		}
	}
}

function searchPlaces(array){
	//if(str.indexOf('yandex.ru') + 1)
	$('#preloader_google_maps').show();
	var query = (document.getElementById("query_google_map").value).toLowerCase();
	//alert(query);
	for (i in gmarkers) gmarkers[i].setMap(null);
	if(query!=''){
		for(var i in array){
			for(var j in array[i]){
				//alert(query);
				var name = array[i][j]['name'];
				var addres = array[i][j]['addres'];
				if(((name.toLowerCase()).indexOf(query)+1) || ((addres.toLowerCase()).indexOf(query)+1)){
					addPlace(array[i][j]);
				}
			}
		}
	}
	$('#preloader_google_maps').hide();
}

function codeAddress() {
	//var address = document.getElementById("address").value;
	var address = "Москва, Чертановская д.16 к.1";
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			//map.setCenter(results[0].geometry.location);
			alert(results[0].geometry.location);
			/*var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location
			});*/
		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});
}
