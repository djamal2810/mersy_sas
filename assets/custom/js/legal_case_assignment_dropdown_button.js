$( document ).ready(function() 
{
 	var forms = $(".legalCaseAssignmentForm");
	//Make all the submit buttons invisible
		
	forms.each(function() 
	{
		$(this).find(":submit").prop('value', 'Validez');
		$(this).find(":submit").hide();
		$(this).find('select.legal_case_agent').eq(0).change(function(e)
		{
			e.preventDefault();
			
			var currForm = $(this).closest('form');
			var submitButton = currForm.find(':submit').eq(0);
			
			//if($(this).children("option").filter(":selected").text()=='Consultant')
			if($(this).children("option").filter(":selected").val()=='Consultant')
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
