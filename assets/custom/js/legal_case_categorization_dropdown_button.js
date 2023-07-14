$( document ).ready(function() 
{
 	var forms = $(".legalCaseCategoryForm");
	//Make all the submit buttons invisible
		
	
	forms.each(function() 
	{
			$(this).find(":submit").prop('value', 'Validez');
		$(this).find(":submit").hide();
		$(this).find('select.legal_case_category').eq(0).change(function(e)
		{
			e.preventDefault();
			
			var currForm = $(this).closest('form');
			var submitButton = currForm.find(':submit').eq(0);
			
			//if($(this).children("option").filter(":selected").text()=='Choisissez')
			if($(this).children("option").filter(":selected").val()=='Catégorie')
			{
				//Hide the submit button
				submitButton.hide();
			}else
			{
					//Make the submit button visible
				submitButton.show();
			}
		});
	});
});