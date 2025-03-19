<?php
include("../plantillas/header.php")
?>
<style>
    #map {
        height: 400px;
        width: 50%;
    }
</style>
<div id="map"></div> 

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAgJ8H6AThOR2nfw167BIDBhK66CZAiUo&callback=initMap" async defer></script>

<script>
    function initMap() {
        var myLocation = {lat: 37.1924105, lng: -3.7381217};

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 11,  
            center: myLocation 
        });
        var marker = new google.maps.Marker({
            position: myLocation,
            map: map
        });
    }
</script>
<?php
include("../plantillas/footer.php")
?>