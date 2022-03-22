<?php

function validate_ip($ip) {
	if (filter_var($ip, FILTER_VALIDATE_IP,
						FILTER_FLAG_IPV4 |
						FILTER_FLAG_IPV6 |
						FILTER_FLAG_NO_PRIV_RANGE |
						FILTER_FLAG_NO_RES_RANGE) === false)
		return false;
	return true;
}

function get_ip_address() {
	// check for shared internet/ISP IP
	if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	}

	// check for IPs passing through proxies
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		// check if multiple ips exist in var
		if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
			$iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			foreach ($iplist as $ip) {
				if (validate_ip($ip))
					return $ip;
			}
		} else {
			if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
		return $_SERVER['HTTP_X_FORWARDED'];
	if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
		return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
	if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
		return $_SERVER['HTTP_FORWARDED_FOR'];
	if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
		return $_SERVER['HTTP_FORWARDED'];

	// return unreliable ip since all else failed
	return $_SERVER['REMOTE_ADDR'];
}
$ip = isset($_GET['ip']) ? $_GET['ip'] : get_ip_address();

$url = file_get_contents('http://ip-api.com/json/'.$ip);
$curlSession = curl_init();
curl_setopt($curlSession, CURLOPT_URL, $url);
curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
curl_close($curlSession);
$json = json_encode($query);

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Ver mi IP?</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="Ver mi IP, Cual es mi IP?, Encuentra tu IP de forma rapida! En 'Ver mi IP? nos dedicamos a brindar información acerca de tu IP, ya sea información geográfica o de tu proveedor de Internet">
    	<meta name="keywords" content="ver mi IP, cual es mi ip, cual, es, mi, ip, sacar mi IP, IP, ver mi ip publica, mi ip publica, buscar IP, bucsar Ip publica">
    	<meta name="author" content="Christian Cruz">
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	<body id="top">
		<!-- Header -->
			<header id="header">
				<div class="content">
					<h1><a href="http://www.vermiip.com.mx">¿Cual es mi ip?</a></h1>
					<p>Tu IP es: <b class="mi_ip">Espera un momento</b> <br /></p>
					<ul class="actions">
						<li><a href="#" data-clipboard-target=".mi_ip" class="button special icon fa-clipboard copy" >Copiar</a></li>
						<li><a href="#one" class="button icon fa-chevron-down scrolly">Conoce más</a></li>
					</ul>
					<ul class="actions">
						<li style="width:100%">
							<form class="form_search margin-bottom-10 ajaxPost" action="buscar_ip">
								 <div class="input-group static-w">
							      	<input type="text" name="ip" placeholder="Busca otra IP" class="form-control">
									<span class="input-group-btn">
							        	<button class="button special static-h btn btn-default" type="submit">Buscar</button>
							      	</span>
							    </div><!-- /input-group -->
							</form>
						</li>
					</ul>
					<ul class="actions">
						<li class="container_errors"></li>
					</ul>
				</div>
				<div class="image phone">
					<div class="inner mapa">
						<div id="map-canvas"></div>
					</div>
				</div>
			</header>

		<!-- One -->
			<section id="one" class="wrapper style2 special">
				<header class="major content">
					<h2>Información acerca de tu IP:</h2>
					<div class="content">
						<table class="alt" id="tablaInfo">
						</table>
						<!-- Adapatble horizontal -->
						<ins class="adsbygoogle"
						     style="display:block"
						     data-ad-client="ca-pub-3122338729474694"
						     data-ad-slot="4544777236"
						     data-ad-format="auto"></ins>
					</div>
				</header>
			</section>

		<!-- Two -->
			<section id="two" class="wrapper">
				<div class="inner alt">
					<section class="spotlight">
						<div class="image"><img src="images/www.png" alt="Que es IP" /></div>
						<div class="content">
							<h3>¿Que es IP?</h3>
							<p>IP o Protocolo de Internet, es un conjunto de reglas que se deben de seguir con el fin de comunicar dos equipos a través de la red</p>
						</div>
					</section>
					<section class="spotlight">
						<div class="image"><img src="images/maps.png" alt="Ubicacion de una IP" /></div>
						<div class="content">
							<h3>Ubicación</h3>
							<p>Tu IP siempre mostrará el lugar desde el cual accesas, por eso es importante proteger tu privacidad!.</p>
						</div>
					</section>
					<section class="spotlight">
						<div class="image"><img src="images/laptop.png" alt="Direciones fisicas de las computadoras" /></div>
						<div class="content">
							  <h3>Dirección IP</h3>
							  <p class="lead">Una dirección IP es un identificador unico para un equipo dentro de una red.</p>
							<ins class="adsbygoogle"
						     style="display:block"
						     data-ad-client="ca-pub-3122338729474694"
						     data-ad-slot="4544777236"
						     data-ad-format="auto"></ins>
						</div>
					</section>
				</div>
			</section>

		<!-- Three -->
			<section id="three" class="wrapper style2 special">
				<header class="major">
					<h2>Buscas privacidad en Internet</h2>
					<p>Recuerda que tu dirección IP puede ser rastreada, te recomendamos usar alguna herramienta para proteger tu ubicación.</p>
					<p>Tor Project es la herramienta indicada a la hora de proteger tu privacidad y anonimato a la hora de navegar en internet.</p>
					<ins class="adsbygoogle"
						     style="display:block"
						     data-ad-client="ca-pub-3122338729474694"
						     data-ad-slot="4544777236"
						     data-ad-format="auto"></ins>
				</header>
				<ul class="actions">
					<li><a href="https://www.torproject.org/download/download-easy.html.en" rel="nofollow" target="_blank" class="button special icon fa-download">Descargar</a></li>
					<li><a href="https://www.torproject.org/" rel="nofollow" target="_blank" class="button">Aprender Más</a></li>
				</ul>
			</section>
		<!-- Footer -->
			<footer id="footer">
				<ul class="icons">
					<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
					<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
				</ul>
				<p class="copyright">&copy; www.vermiip.com.mx Creditos: <a href="https://www.ccruz.me">CCRUZ</a></p>
			</footer>

		<!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/jquery.validate.min.js"></script>
		<script src="assets/js/jquery.scrolly.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
		<script src="assets/js/clipboard.min.js"></script>
		<script src="assets/js/notify.js"></script>
		<script type="text/javascript">
			jQuery.validator.addMethod('validIP', function(value) {
			    var split = value.split('.');
			    if (split.length != 4)
			        return false;

			    for (var i=0; i<split.length; i++) {
			        var s = split[i];
			        if (s.length==0 || isNaN(s) || s<0 || s>255)
			            return false;
			    }
			    return true;
			}, 'Ingresa una IP válida');

		  	jQuery(function($) {
				$(document).ready( function() {

					jQuery(".form_search").validate({
						errorLabelContainer: $(".container_errors"),
						rules: {
							ip: { validIP: true }
						}
					});

					var info = <?php echo $json; ?>;
					if(info != '') {
						if(info.hasOwnProperty('status') && info.status == 'success') {
							$('.mi_ip').html(info.query);
							var html = '';
							if(info.hasOwnProperty('country') && info.country != ''){
								html += '<tr><td>País</td><td>' + info.country;
								if(info.hasOwnProperty('countryCode') && info.countryCode != ''){
									html += ' (' + info.countryCode + ')';
								}
								html += '</td></tr>';
							}
							var ciudad = '';
							if(info.hasOwnProperty('regionName') && info.regionName != ''){
								html += '<tr><td>Region</td><td>' + info.regionName;
								if(info.hasOwnProperty('region') && info.region != ''){
									html += ' (' + info.region + ')';
								}
								html += '</td></tr>';
							}
							if(info.hasOwnProperty('city') && info.city != ''){
								html += '<tr><td>Ciudad</td><td>' + info.city + '</td></tr>';
							}

							if(info.hasOwnProperty('zip') && info.zip != ''){
								html += '<tr><td>Código postal</td><td>' + info.zip + '</td></tr>';
							}
							if(info.hasOwnProperty('timezone') && info.timezone != ''){
								html += '<tr><td>Zona horaria</td><td>' + info.timezone + '</td></tr>';
							}
							if(info.hasOwnProperty('lat') && info.lat != '' && info.hasOwnProperty('lon') && info.lon != ''){
								html += '<tr><td>Ubicación</td><td>' + info.lat + ', ' + info.lon;'</td></tr>';
								initialize(info.lat, info.lon);
							}else {
								$('#idUbicacion').hide();
							}

							if(info.hasOwnProperty('isp') && info.isp != ''){
								html += '<tr><td>ISP</td><td>' + info.isp + '</td></tr>';
							}
							if(info.hasOwnProperty('org') && info.org != ''){
								html += '<tr><td>Organización</td><td>' + info.org + '</td></tr>';
							}

							$('#tablaInfo').html(html);
						} else {
							intentoNuevo();
						}
					} else {
						intentoNuevo();
					}
				});

				function intentoNuevo() {
					$.getJSON("https://freegeoip.net/json/",
						function(info) {
							$('.mi_ip').html(info.ip);
							var html = '';
							if(info.hasOwnProperty('country_name') && info.country_name != ''){
								html += '<tr><td>País</td><td>' + info.country_name;
								if(info.hasOwnProperty('country_code') && info.country_code != ''){
									html += ' (' + info.country_code + ')';
								}
								html += '</td></tr>';
							}
							var ciudad = '';
							if(info.hasOwnProperty('city') && info.city != ''){
								html += '<tr><td>Ciudad</td><td>' + info.city;
								ciudad = info.city;
							}
							if(ciudad != '' && info.hasOwnProperty('region_name') && info.region_name != ''){
								html += ', ' + info.region_name;
							}else if(info.hasOwnProperty('region_name') && info.region_name != ''){
								 html += '<tr><td>Ciudad</td><td>' +info.region_name + '</td></tr>';
							}
							if(ciudad != ''){
								html += '</td></tr>'
							}
							if(info.hasOwnProperty('zip_code') && info.zip_code != ''){
								html += '<tr><td>Código postal</td><td>' + info.zip_code + '</td></tr>';
							}
							if(info.hasOwnProperty('isp') && info.isp != ''){
								html += '<tr><td>ISP</td><td>' + info.isp + '</td></tr>';
							}
							var timezone = '';
							if(info.hasOwnProperty('time_zone') && info.time_zone != ''){
								html += '<tr><td>Zona horaria</td><td>' + info.time_zone + '</td></tr>';
							}
							var ubicacion = '';
							if(info.hasOwnProperty('latitude') && info.latitude != '' && info.hasOwnProperty('longitude') && info.longitude != ''){
								html += '<tr><td>Ubicación</td><td>' + info.latitude + ',' + info.longitude;'</td></tr>';
								initialize(info.latitude, info.longitude);
							}else {
								$('#idUbicacion').hide();
							}
							$('#tablaInfo').html(html);
					});
				}
	 	 });
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;key=AIzaSyD_9emDBlkgjjuS-J2QnFBRQJpaGVV3svk"></script>
		<script>
			function initialize(longitud, latitud) {
			  var myLatlng = new google.maps.LatLng(longitud, latitud);
			  var mapOptions = {
			    zoom: 6,
			    center: myLatlng
			  }
			  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			  var marker = new google.maps.Marker({
			      position: myLatlng,
			      map: map,
			      title: 'Estas aquí!'
			  });
			}

			var clipboard = new Clipboard('.copy');
			clipboard.on('success', function(e) {
				$(".copy").notify("Tu IP se ha copiado al portapapeles", "info");
			});
  		</script>

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-57318924-4"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-57318924-4');
		</script>

		<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
		<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
		<script>
		window.addEventListener("load", function(){
		window.cookieconsent.initialise({
		  "palette": {
		    "popup": {
		      "background": "#000"
		    },
		    "button": {
		      "background": "#f1d600"
		    }
		  },
		  "type": "opt-out",
		  "content": {
		    "message": "Este sitio web usa Cookies para mejorar y optimizar la experiencia del usuario",
		    "dismiss": "Aceptar",
		    "deny": "Cancelar",
		    "link": "Leer más",
			"allow": "Permitir"
		  }
		})});
		</script>

		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script>
		     (adsbygoogle = window.adsbygoogle || []).push({
		          google_ad_client: "ca-pub-3122338729474694",
		          enable_page_level_ads: true
		     });
		     (adsbygoogle = window.adsbygoogle || []).push({
                          google_ad_client: "ca-pub-3122338729474694",

                     });
		     (adsbygoogle = window.adsbygoogle || []).push({
                          google_ad_client: "ca-pub-3122338729474694",

                     });
		</script>
	</body>
</html>
