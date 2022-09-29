/**
 * system of booking page of pro space
 */
const reservations_list = {
    init: function () {
        const AllreservationsList = document.querySelectorAll('.package-line');
        for(const oneReservation of AllreservationsList){
            oneReservation.classList.add('d-none')
            // apparence = for block establishment
            oneReservation.classList.remove('apparence')
            // packager = for block package
            oneReservation.classList.remove('packager')
        }

        //we browse the reservation to check
        reservations_list.browseToCheck();
        //we browse the reservations validated
        reservations_list.browseValidated();


        //we browse the reservation cancelled
        reservations_list.browseCancelled();
        //we hide the package who doesn't have any reservation
        reservations_list.hidePackage();
        //we hide the etablishment who doesn't have any reservation
        reservations_list.hideEstablishment();
    },

    browseToCheck: function() {
        //we retrieve the element to browse the reservations to check
        const elementsToCheck= document.querySelectorAll('.toCheck'); 

        for(const table of elementsToCheck){
            //we retrieve all the reservations line
            const reservationsList = table.querySelectorAll('.package-line');

            //we get the status of each reservation and we show these reservation
            for (const reservation of reservationsList){
                let statusReservation = reservation.dataset.status
                
                //we remove the d-none class when the status is to check
                if (statusReservation == 0){
                    reservation.classList.remove('d-none')
                    reservation.classList.add('apparence')
                    reservation.classList.add('packager')
                }
            }        
        }
    },

    browseCancelled: function(){
        //we retrieve the element to browse the reservations cancelled
        const elementsToCheck= document.querySelectorAll('.cancelled');
        for(const table of elementsToCheck){
            //we retrieve all the reservations line
            const reservationsList = table.querySelectorAll('.package-line');
                
            //we get the status of each reservation and we show these reservation
            for (const reservation of reservationsList){
                let statusReservation = reservation.dataset.status
                
                //we remove the d-none class when the status is cancelled
                if (statusReservation == 2){
                    reservation.classList.remove('d-none')
                    reservation.classList.add('apparence')
                    reservation.classList.add('packager')
                }
            }        
        }
    },

    browseValidated: function(){
        //we retrieve the element to browse the reservations valitated
        const elementsToCheck= document.querySelectorAll('.validated');
        
        for(const table of elementsToCheck){
            //we retrieve all the reservations line
            const reservationsList = table.querySelectorAll('.package-line');
                
            //we get the status of each reservation and we show these reservation
            for (const reservation of reservationsList){
                let statusReservation = reservation.dataset.status
            
                //we remove the d-none class when the status is validated
                if (statusReservation == 1){
                    reservation.classList.remove('d-none')
                    reservation.classList.add('apparence')
                    reservation.classList.add('packager')
                }
            }        
        }
    },

    hidePackage: function(){
        const packagesList= document.querySelectorAll('.bloc-establishment');

        //for each etablishment block, we test if there is at least one book to browse
        for (const packageBlock of packagesList){
            const line = packageBlock.querySelectorAll('.packager');
            
            if(line.length==0){
                packageBlock.classList.add('d-none')
            }
        }
    },

    hideEstablishment: function(){
        const establishmentsList= document.querySelectorAll('.bloc-package-name');

        //for each etablishment block, we test if there is at least one book to browse
        for (const establishmentBlock of establishmentsList){
            const line = establishmentBlock.querySelectorAll('.apparence');
            
            if(line.length==0){
                establishmentBlock.classList.add('d-none')
            }
        }
    }
}
document.addEventListener('DOMContentLoaded', reservations_list.init);