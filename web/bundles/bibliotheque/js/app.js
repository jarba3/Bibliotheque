$(document).ready(function() {

	$( "#form_nom" ).keyup(function() {

		var nom = $( this ).val();

		$('#form_prenom').keyup(function() {

		    var prenom = $( this ).val();

		    var nomsplit = nom.substring(0, 3);

		    var prenomsplit = prenom.substring(0 , 3);

		    var rand = Math.floor((Math.random() * 1000) + 1);
		    
		    $( "#form_username" ).val( nomsplit + prenomsplit + rand );
		    
		    $( "#form_password_first" ).val( nomsplit + prenomsplit + rand );

		    $( "#form_password_second" ).val( nomsplit + prenomsplit + rand );
			
		}).keyup();

  	}).keyup();

});