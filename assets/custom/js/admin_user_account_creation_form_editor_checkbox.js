$("#adminUserAccountCreationFormId select#user_role_choice").change(function()
{
    var selectedRole = $(this).children("option:selected").val();
	if(selectedRole=="ROLE_ADMIN")
	{
		newCheckBox =
            '<div id="accountManagementCheckBox">' +
			'<label>Editeur</label>' +
            '<input type="checkbox" name="editor" value="editor" /></div>';
            $('#adminUserAccountCreationFormId #checkbox_position').append(newCheckBox );
	}else
	{
		$("#adminUserAccountCreationFormId #accountManagementCheckBox").remove(); 
	}

});
