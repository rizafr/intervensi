/****************************************************************
(C) 2008 Kishore Nallan for DesignShack
http://www.kishorelive.com
kishore.nc@gmail.com
*****************************************************************/

$(document).ready(function(){
    
	var shifton = false;
	
	// toggles the keyboard to show or hide when link is clicked
	$("#showkeyboard2").click(function(e) {
		var height = $('#keyboard2').height();
		var width = $('#keyboard2').width();
		leftVal=e.pageX-40+"px";
		topVal=e.pageY+20+"px";
		$('#keyboard2').css({left:leftVal,top:topVal}).toggle();
	});
	

	
	// function thats called when any of the keys on the keyboard are pressed
	$("#keyboard2 input").bind("click", function(e) {
									   
		if( $(this).val() == 'Backspace' ) {
			$('#user_login').replaceSelection("", true);
		}
		
		else if( $(this).val() == "Shift" ) {
			if(shifton == false) {
				onShift(1);	
				shifton = true;
			}
			
			else {
				onShift(0);
				shifton = false;
			} 
		}
		
		else {
		
			$('#user_login').replaceSelection($(this).val(), true);
			
			if(shifton == true) {
				onShift(0);
				shifton = false;
			}
		}
	});
	
});


