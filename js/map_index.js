(function(A) {
	if (!Array.prototype.forEach)
		A.forEach = A.forEach || function(action, that) {
			for (var i = 0, l = this.length; i < l; i++)
				if (i in this)
					action.call(that, this[i], i, this);
		};

})(Array.prototype);


function initialize () {
	if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var data = this.responseText;
					var school = JSON.parse(data);
					//alert(school.school.length);
			
			var
			mapObject,
			markers = [],
			schoolmark = [],
			markersData = {};
			for(var i=0;i<2;i++){
				schoolmark.push( 
				{
					name: school['school'][i][1],
					location_latitude: school['school'][i][6],
					location_longitude: school['school'][i][7],
					map_image_url: 'img/img.png',
					name_point: school['school'][i][1],
					description_point: school['school'][i][5],
					url_point: 'school.php?idmap='+school['school'][i][0]
				});
			}
			markersData = {'School': schoolmark};
	
	var mapOptions = {
		zoom: 12,
		center: new google.maps.LatLng(13.7373,100.497),
		mapTypeId: google.maps.MapTypeId.ROADMAP,

		mapTypeControl: false,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			position: google.maps.ControlPosition.LEFT_CENTER
		},
		panControl: false,
		panControlOptions: {
			position: google.maps.ControlPosition.TOP_RIGHT
		},
		zoomControl: false,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.LARGE,
			position: google.maps.ControlPosition.TOP_RIGHT
		},
		scaleControl: false,
		scaleControlOptions: {
			position: google.maps.ControlPosition.TOP_LEFT
		},
		streetViewControl: false,
		streetViewControlOptions: {
			position: google.maps.ControlPosition.LEFT_TOP
		},
				styles: [
          {
            featureType: 'poi.business',
            stylers: [{visibility: 'off'}]
          },
          {
            featureType: 'transit',
            elementType: 'labels.icon',
            stylers: [{visibility: 'off'}]
          }
        ]
			};
	var
		marker;
	mapObject = new google.maps.Map(document.getElementById('map'), mapOptions);
	for (var key in markersData)
		markersData[key].forEach(function (item) {
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(item.location_latitude, item.location_longitude),
				map: mapObject,
				icon: 'img/icon/placeholder.png',
			});

			if ('undefined' === typeof markers[key])
				markers[key] = [];
			markers[key].push(marker);
			google.maps.event.addListener(marker, 'mouseover', (function () {
				closeInfoBox();
				getInfoBox(item).open(mapObject, this);
				
			}));

			
		});

		}
			};
			xmlhttp.open("GET","get_school_location.php",true);
			xmlhttp.send();
		};
		
		





function hideAllMarkers () {
	for (var key in markers)
		markers[key].forEach(function (marker) {
			marker.setMap(null);
		});
};

function toggleMarkers (category) {
	hideAllMarkers();
	closeInfoBox();

	if ('undefined' === typeof markers[category])
		return false;
	markers[category].forEach(function (marker) {
		marker.setMap(mapObject);
		marker.setAnimation(google.maps.Animation.DROP);

	});
};

function closeInfoBox() {
	$('div.infoBox').remove();
};

function getInfoBox(item) {
	return new InfoBox({
		content:
			'<div class="marker_visit" id="marker_info">' +
			'<div class="info" id="info">'+
			'<a href="'+ item.url_point + '" class="">'+ item.name_point +'</a>' +
			'<span></span>' +
			'</div>' +
			'</div>',
		disableAutoPan: true,
		maxWidth: 0,
		pixelOffset: new google.maps.Size(40, -50),
		closeBoxMargin: '50px 0px',
		closeBoxURL: '',
		isHidden: false,
		pane: 'floatPane',
		enableEventPropagation: true
	});


};




