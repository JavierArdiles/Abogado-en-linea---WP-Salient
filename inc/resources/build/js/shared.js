( function( $ ) {
    
	const ClazzyStudioShared = function() {

        this.selectors = {
			'demo': '.class',
		};
	};

	ClazzyStudioShared.prototype = {

		init: function(){
			
			this.initEvents();
		},

		initEvents: function(){
			/* Events */
		},
	};

	$(function() {

		window.ClazzyStudioShared = new ClazzyStudioShared();
		window.ClazzyStudioShared.init();
	});
	
}( jQuery ) );
