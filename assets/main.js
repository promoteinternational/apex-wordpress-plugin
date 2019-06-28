(function() {
    // your page initialization code here
    // the DOM will be available here

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

})();

