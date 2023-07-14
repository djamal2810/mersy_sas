if($(".accountManagementFormUserCategoryClass :selected").text()=="ROLE_ADMIN")
{
	newCheckBox =
    	'<div id="accountManagementCheckBox">' +
		'<label>Editeur</label>' +
   		'<input type="checkbox" name="editor" value="editor" id="editorCheckBoxId" /></div>';

 	$('#checkbox_position').append(newCheckBox );
	//var checked = $("#accountManagementContainerId").find('input[name="_editor_check_box_checked"]').val();
	var checked = $(".accountManagementFormCheckValueClass").val();
					
	if(checked==true)
	{
		$("#editorCheckBoxId").prop("checked", true);
	}
	//$('#salutation')[0].innerHTML = checked;	
}else
{
	$("#accountManagementCheckBox").remove(); 
}
