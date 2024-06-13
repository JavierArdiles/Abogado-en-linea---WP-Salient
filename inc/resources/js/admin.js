( function( $ ) {
    
	const ClazzyStudioAdmin = function() {

        this.selectors = {
			'demo': '.class',
		};
	};

	ClazzyStudioAdmin.prototype = {
        
		init: function(){
			
			this.initEvents();
		},

		initEvents: function(){
            /* Events */
        },
	};

	$(function() {

		window.ClazzyStudioAdmin = new ClazzyStudioAdmin();
		window.ClazzyStudioAdmin.init();
	});
	
}( jQuery ) );
