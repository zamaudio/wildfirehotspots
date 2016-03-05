<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>WildFireHotspots</title>
    <link rel="stylesheet" href="http://openlayers.org/en/v3.14.1/css/ol.css" type="text/css">
    <script src="http://openlayers.org/en/v3.14.1/build/ol.js"></script>
  </head>
  <body>
    <div id="map" class="map"></div>
    <script>
      var red1 = new ol.style.Circle({
        radius: 5,
        fill: new ol.style.Stroke({color: 'red'}),
        stroke: new ol.style.Stroke({color: 'red', width: 1})
      });
      var yellow1 = new ol.style.Circle({
        radius: 5,
        fill: new ol.style.Stroke({color: 'yellow'}),
        stroke: new ol.style.Stroke({color: 'yellow', width: 1})
      });
      var green1 = new ol.style.Circle({
        radius: 5,
        fill: new ol.style.Stroke({color: 'green'}),
        stroke: new ol.style.Stroke({color: 'green', width: 1})
      });

      var styles = {
        'PointGreen': new ol.style.Style({
          image: green1
        }),
        'PointYellow': new ol.style.Style({
          image: yellow1
        }),
        'PointRed': new ol.style.Style({
          image: red1
        }),
      };

      var styleFunction = function(feature) {
        celsius = parseInt(feature.getProperties().temp_kelvin, 10) - 273.15;
        if (celsius <= 35) {
          return styles['PointGreen'];
        } else if (celsius <= 40) {
          return styles['PointYellow'];
        } else if (celsius > 40) {
          return styles['PointRed'];
        }
      };

      var geojsonObject = <?php 
		$json = file_get_contents("http://sentinel.ga.gov.au/geoserver/wfs?version=1.1.1&SERVICE=WFS&REQUEST=GetFeature&TYPENAME=sentinel:hotspot_current&SRSNAME=EPSG:3857&outputformat=json");
		echo $json;
      ?>;

      var vectorSource = new ol.source.Vector({
        features: (new ol.format.GeoJSON()).readFeatures(geojsonObject)
      });

      var vectorLayer = new ol.layer.Vector({
        source: vectorSource,
        style: styleFunction
      });

      var map = new ol.Map({
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          }),
          vectorLayer
        ],
        target: 'map',
        controls: ol.control.defaults({
          attributionOptions: /** @type {olx.control.AttributionOptions} */ ({
            collapsible: false
          })
        }),
        view: new ol.View({
          center: [14188175,-2031939],
          zoom: 4
        })
      });
    </script>
  </body>
</html>
