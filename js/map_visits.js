(function(A) {
	if (!Array.prototype.forEach)
		A.forEach = A.forEach || function(action, that) {
			for (var i = 0, l = this.length; i < l; i++)
				if (i in this)
					action.call(that, this[i], i, this);
			};

		})(Array.prototype);
		
		var
			mapObject,
			markers = [],
			schoolmark = []
			markersData = [];
			
			$.ajax({
			  type: "POST",
			  url: "get_school_location.php",
			  datatype: "html",
			  data: "",
			  success: function(data) {
					//alert(data['school'][0]['id']);
					markersData = data;
				},
			  async: false
			}); 
		
		
		function initialize () {
			/* if (window.XMLHttpRequest) {
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
			*/
			
			
			//markersData = {'School': "asd"};
			//alert(markersData);
			var mapOptions = {
				zoom: 10,
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
						position: new google.maps.LatLng(item.lat, item.lon),
						map: mapObject,
						icon: 'img/icon/placeholder.png',
					});

					if ('undefined' === typeof markers[key])
						markers[key] = [];
					markers[key].push(marker);
					//alert(markers['school'][0]['lat']);
					google.maps.event.addListener(marker, 'click', (function () {
					  closeInfoBox();
					  getInfoBox(item).open(mapObject, this);
					  //mapObject.setCenter(new google.maps.LatLng(item.lat, item.lon));
					 }));

									
								});
			}
			/* };
			xmlhttp.open("GET","get_school_location.php",true);
			xmlhttp.send(); 
		};*/

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
				'<div class="marker_info none" id="marker_info">' +
				'<div class="info" id="info">'+
				'<h2>'+ item.name +'<span></span></h2>' +
				'<span>'+ item.information +'</span>' +
				'<a href="02.html" class="green_btn">More info</a>' +
				'<span class="arrow"></span>' +
				'</div>' +
				'</div>',
				disableAutoPan: true,
				maxWidth: 0,
				pixelOffset: new google.maps.Size(40, -210),
				closeBoxMargin: '50px 200px',
				closeBoxURL: '',
				isHidden: false,
				pane: 'floatPane',
				enableEventPropagation: true
			});


		};
		
		function change_location(key="school",index=0) {
			closeInfoBox();
			//alert (markers[key][0]['index']);
			mapObject.setCenter(new google.maps.LatLng(markers[key][index].getPosition().lat(),markers[key][index].getPosition().lng()));
				

			
			var InfoBox = getInfoBox(markersData[key][index]);
			InfoBox.position_ = markers[key][index].getPosition();
			InfoBox.setMap(mapObject);
			
				


		};

	


