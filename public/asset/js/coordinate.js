const coordinate = {
    // in this table, we will put the list of markers
    markerList : [],

    init: function () {
        // we put a listener on the checkboxes filters
        const filtersCheckboxList = document.querySelectorAll('.checkbox-filters');
        for (const filterCheckbox of filtersCheckboxList) {
            filterCheckbox.addEventListener('change', coordinate.handleChangeMarker);
        }

        coordinate.markerShow(); 
    },

    /**
     * allows to get the list of establishments and to place all markers on the map
     */
    markerShow: function () {

        const listEstablisment = document.querySelectorAll('.establishment');

        for (let establishment of listEstablisment) {

            if (!establishment.classList.contains('d-none')) {

                let latEstablishment = establishment.dataset.lat;
                let longEstablishment = establishment.dataset.long;
                let establishmentNumber = establishment.dataset.number;
                
                // for each establishment, we add a marker on the map
                let marker = L.marker([latEstablishment, longEstablishment]).addTo(map);
    
                // we add a popup when we click on the marker
                let establishmentName = establishment.querySelector('h3').textContent;
                let message = '<h5>'+establishmentName+'</h5>'
                marker.bindPopup(message);

                // we add classes to markers
                L.DomUtil.addClass(marker._icon, "markerIcon");
                L.DomUtil.addClass(marker._icon, establishmentNumber);

                coordinate.markerList.push(marker); 
            }
        }

        // we put eventListener on the markers
        const markersElmt = document.querySelectorAll('.markerIcon');
        for (const markerElmt of markersElmt) {
            markerElmt.addEventListener('click', establishmentDetails.handleClickMarker);
        }

    },

    /**
     * if we add a filter, we delete all markers before adding others
     */
    handleChangeMarker: function () {

        for (markerOne of coordinate.markerList) {
            map.removeLayer(markerOne);
        }

        coordinate.markerShow();   
    }
}