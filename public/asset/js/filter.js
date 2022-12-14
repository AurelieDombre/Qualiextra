/**
 * To add filter 
 */
const filter = {
    init: function () {
        const buttonFilterElmt = document.querySelector('#filter');
        buttonFilterElmt.addEventListener('click', filter.handleClickFilter);

        const iconCloseElmt = document.querySelector('#filter-close');
        iconCloseElmt.addEventListener('click', filter.handleClickClose);


        // we put a listener on the checkboxes filters
        const filtersCheckboxList = document.querySelectorAll('.new-filters');
        for (const filterCheckbox of filtersCheckboxList) {
            filterCheckbox.addEventListener('change', filter.handleChangefilter);
        }

    },

    /**
     * when you click on the "filter" button, a div appears
     * we close the "details" div if it is open
     */
    handleClickFilter: function (evt) {
        evt.preventDefault();
        document.querySelector('.filter-case').classList.toggle('d-none');
        for (const detailEstablishment of document.querySelectorAll('.details')) {
            detailEstablishment.classList.add('d-none');
        }
        document.querySelector('.establishmentList').classList.remove('d-none');
    },

    /**
     * if you click on the cross, you close the div
     */
    handleClickClose: function (evt) {
        document.querySelector('.filter-case').classList.add('d-none');

    },

    /**
     * when you select a checkbox, you filter the list of restaurants
     */
    handleChangefilter: function () {

        // we get the list of filters
        const filtersCheckboxList = document.querySelectorAll('.checkbox-filters');
        let listFiltersChecked = [];
        // We test if the checkbox is checked 
        for (const filterCheckbox of filtersCheckboxList) {
            if (filterCheckbox.checked) {
                // if a filter is checked, it is added in a table
                listFiltersChecked.push(filterCheckbox.id);
            }

        }


        // we get the list of restaurants
        let restaurantList = document.querySelectorAll('.establishment');

        for (const restaurant of restaurantList) {
            // For each restaurant, we retrieve the filters associated with it
            let filtersOfRestaurant = restaurant.dataset.name;

            // and put them in a table
            restaurantFilterArray = filtersOfRestaurant.split(" ");


            let counter = 0;
            for (let i = 0; i < restaurantFilterArray.length; i++) {
                // if the restaurant filter is one of the checked filters, the counter is incremented
                if (listFiltersChecked.includes(restaurantFilterArray[i])) {
                    counter++;
                }
            }

            // we retrieve the  array lenght of filter
            let comparator = listFiltersChecked.length

            // We take the list of price filter
            const filtersPriceList = document.querySelectorAll('.price-filter');
            for (const filterPrice of filtersPriceList) {
                if (filterPrice.selected) {

                    comparator++; 

                    // if a price filter is selected, we compare by the average price of the establishment
                    let priceOfRestaurant = restaurant.dataset.price;
                    
                    if (filterPrice.value==1 && priceOfRestaurant<15) {
                        counter++;
                    }
                    if (filterPrice.value==2 &&  priceOfRestaurant<25 &&  priceOfRestaurant>=15) {
                        counter++;
                    }
                    if (filterPrice.value==3 &&  priceOfRestaurant<40 &&  priceOfRestaurant>=25) {
                        counter++;
                    }
                    if (filterPrice.value==4 &&  40<=priceOfRestaurant) {
                        counter++;
                    }
                }

            }
            
            // if the counter is equal to the number of checked filters, the restaurant is displayed
            if (counter == comparator) {
                restaurant.classList.remove("d-none");
            } else {
                restaurant.classList.add("d-none");
            }
        }
    }
};