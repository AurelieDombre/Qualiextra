/**
 * To mark all the establishment address on the map 
 */
const coordinate = {
    // in this table, we will put the list of markers
    markerList : [],

    init: function () {
        // we put a listener on the checkboxes filters
        const filtersCheckboxList = document.querySelectorAll('.new-filters');
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

            // if the establishment doesn't have the d-none class we display it
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

                // we add classes to markers (when we click on the merker that display the detail of the restaurant on left side)
                L.DomUtil.addClass(marker._icon, "markerIcon");
                L.DomUtil.addClass(marker._icon, establishmentNumber);

                // We add the marker in the array markerList : [], if the marker exist in array we display it but if the marker 
                //is not in array so the marker doesn't appeared
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