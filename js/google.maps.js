
  function initialize() {
    var mapOptions = {
      zoom: 12,
      center: new google.maps.LatLng(51.46598,46.11820),
	  disableDefaultUI: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	geocoder = new google.maps.Geocoder();
	gmarkers = [];
	/*var signImage = new google.maps.MarkerImage('/images/logo_map1.png', new google.maps.Size(100, 37), new google.maps.Point(0,0), new google.maps.Point(5, 37));
    var signImage_hover = new google.maps.MarkerImage('/images/logo_map2.png', new google.maps.Size(100, 37), new google.maps.Point(0,0), new google.maps.Point(5, 37));
	var signLatLng = new google.maps.LatLng(55.7097,37.6769);
    var beachMarker = new google.maps.Marker({
        position: signLatLng,
        map: map,
        icon: signImage
	});
	var infowindow = new google.maps.InfoWindow({
		content: "<b>Мы находимся</b><br/>7-ая Кожуховская д.18, офис 306<br/> (499) 340-94-71"
	});
	infowindow.open(map,beachMarker);
	infowindow.open(map,beachMarker);
	
	google.maps.event.addListener(beachMarker, 'mouseover', function() {
		beachMarker = new google.maps.Marker({
			position: signLatLng,
			map: map,
			icon: signImage_hover
		});
		
	google.maps.event.addListener(beachMarker, 'click', function() {
			infowindow.open(map,beachMarker);
		});
	  });
	  google.maps.event.addListener(beachMarker, 'click', function() {
			infowindow.open(map,beachMarker);
		});

	var avtozavodskaya_marwrut = [
		new google.maps.LatLng(55.7084, 37.6580),
		new google.maps.LatLng(55.7085, 37.6583),
		new google.maps.LatLng(55.7077, 37.6595),
		new google.maps.LatLng(55.7105, 37.6653),
		new google.maps.LatLng(55.7103, 37.6660),
		new google.maps.LatLng(55.7104, 37.6667),
		new google.maps.LatLng(55.7093, 37.6671),
		new google.maps.LatLng(55.7093, 37.6677),
		new google.maps.LatLng(55.7090, 37.6683),
		new google.maps.LatLng(55.7092, 37.6697),
		new google.maps.LatLng(55.7091, 37.6738),
		new google.maps.LatLng(55.7100, 37.6771),
		new google.maps.LatLng(55.7098, 37.6773)
	];
	var kajuxovskaya_marwrut = [
		new google.maps.LatLng(55.7061, 37.6856),
		new google.maps.LatLng(55.7060, 37.6853),
		new google.maps.LatLng(55.7069, 37.6846),
		new google.maps.LatLng(55.7064, 37.6828),
		new google.maps.LatLng(55.7106, 37.6790),
		new google.maps.LatLng(55.7100, 37.6772),
		new google.maps.LatLng(55.7098, 37.6773)
	];
	var dubrovka_marwrut = [
		new google.maps.LatLng(55.7178, 37.6767),
		new google.maps.LatLng(55.7113, 37.6816),
		new google.maps.LatLng(55.7106, 37.6790)
	];
	var walkingLine1 = new google.maps.Polyline({path: avtozavodskaya_marwrut,strokeColor: "#0a6f20",strokeOpacity: 0.9,	strokeWeight: 2});
	walkingLine1.setMap(map);
	var walkingLine2 = new google.maps.Polyline({path: kajuxovskaya_marwrut,strokeColor: "#8cce3a",strokeOpacity: 0.9,	strokeWeight: 2});
	walkingLine2.setMap(map);
	var walkingLine3 = new google.maps.Polyline({path: dubrovka_marwrut,strokeColor: "#8cce3a",strokeOpacity: 0.9,	strokeWeight: 2});
	walkingLine3.setMap(map);
	*/

	
	function ZoomControl(controlDiv, map) {
		// отмена увеличений справа
		return;
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
		content: "<b><span style='from-size:14px'>" +item['name']+ "</span></b> <a href='" +item['redir']+ "'>подробнее</a><br/><div style=\"float:left;\">" +item['addres']+ "</div><div style='float:right;padding-left: 10px;'><img width=\"50\" src='" +item['image']+ "' /></div>"
	});
	gmarkers.push(beachMarker);
	google.maps.event.addListener(beachMarker, 'click', function() {
		infowindow.open(map,beachMarker);
	});
	//infowindow.open(map,beachMarker);
}

function showPlaces(id_cat,array){
	var items = array[id_cat];
	for (i in gmarkers) gmarkers[i].setMap(null);
	for(var i in items){
		addPlace(items[i]);
	}
}

function showPlacesAll(array){
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
	var query = document.getElementById("query_google_map").value;
	for (i in gmarkers) gmarkers[i].setMap(null);
	if(query!=''){
		for(var i in array){
			for(var j in array[i]){
				if((query.indexOf(array[i][j]['name'])+1) or (query.indexOf(array[i][j]['addres'])+1)){
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
