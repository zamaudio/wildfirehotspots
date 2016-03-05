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
  var yellow1 = new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
    opacity: 0.75,
    src: 'fire_yellow.png',
    scale : 0.2
  }));
  var orange1 = new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
    opacity: 0.75,
    src: 'fire_orange.png',
    scale : 0.2
  }));
  var green1 = new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
    opacity: 0.75,
    src: 'fire_green.png',
    scale : 0.2
  }));
      var styles = {
        'PointGreen': new ol.style.Style({
          image: green1
        }),
        'PointYellow': new ol.style.Style({
          image: yellow1
        }),
        'PointRed': new ol.style.Style({
          image: orange1
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
          new ol.layer.Tile({
             source: new ol.source.TileWMS(/** @type {olx.source.TileWMSOptions} */ ({
               url: 'http://data.auscover.org.au/geoserver/wms',
               params: {'LAYERS': 'clw:FractCover.V2_2.PV', 'TILED': true},
               serverType: 'geoserver'
               })
             )
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
