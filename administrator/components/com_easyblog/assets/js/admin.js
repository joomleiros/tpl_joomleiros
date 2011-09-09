(function($){
	$(document).ready( function() {
	
		// insert span tag into submenu item for more flexibility
		$('#submenu li a').each( function() {
			$(this).wrapInner('<span></span>');
		});
		
		
		// move system message
		if ( $('#system-message').length > 0 ) {
			var message = $('#system-message').html();
			
			$('#system-message').remove();
			
			$( '<dl id="system-message">' + message + '</dl>' ).insertAfter('#toolbar-box');
			
			setTimeout( function() {
				$('#system-message').slideUp();
			}, 5000);
		}
		
		$('body #settingsForm .admintable tr:odd').addClass('tr-odd');
		
		$('.admintable tr').hover( function() {
			$(this).addClass('tr-hover');
		},
		function() {
			$(this).removeClass('tr-hover');
		});
		
		// move version notice to header
		$('#versionTracker').appendTo('.icon-48-home').show();
		$('.icon-48-home').css({ position: 'relative' });
		
		
		$('.icon-item').click( function(event) {
	        window.location.href = $('a', this).attr('href');
	   	}); 
		   
		$('.icon-item').hover( function(event) {
	        $(this).addClass('hover');
	   	}, function() {
			$(this).removeClass('hover');
		}); 	
		
		admin.checkbox.init();
	});
	
	
	var admin = window.admin = {
		blog: {
			reject: function( blogId ) {
				ejax.load( 'Pending' , 'confirmRejectBlog' , blogId );
			    return;
			},
			approve: function(blogId) {
				if ( confirm( 'Are you sure you want to approve this blog?' ) ) {
			    	window.location = eblog_site + '/index.php?option=com_easyblog&c=blogs&task=approveBlog&draft_id=' + blogId;
			    }
			    return;			
			}
		},
		checkbox: {
			init: function(){
				// Transform checkboxes.
				$( '.option-enable' ).click( function(){
					var parent = $(this).parent();
					$( '.option-disable' , parent ).removeClass( 'selected' );
					$( this ).addClass( 'selected' );
					$( '.radiobox' , parent ).attr( 'value' , 1 );
				});
				
				$( '.option-disable' ).click( function(){
					var parent = $(this).parent();
					$( '.option-enable' , parent ).removeClass( 'selected' );
					$( this ).addClass( 'selected' );
					$( '.radiobox' , parent ).attr( 'value' , 0 );
				});
			}
		},
		teamblog: {
		    markAdmin : function(teamid, userid) {
	            window.location = eblog_site + '&c=teamblogs&task=markAdmin&teamid=' + teamid + '&userid=' + userid;
			},
		    removeAdmin : function(teamid, userid) {
				window.location = eblog_site + '&c=teamblogs&task=removeAdmin&teamid=' + teamid + '&userid=' + userid;
			}
		}
	}

})(sQuery);