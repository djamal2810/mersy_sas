		$(document).ready(function()
		{
    		var selectedRole = $("#adminUserAccountCreationFormId select#user_role_choice").children("option:selected").val();
			var this_js_script = $('script[src*=admin_user_account_modification_editor_checkbox]'); 
			var is_editor = this_js_script.attr('data-isEditor'); 
			//alert(selectedRole);
			if(selectedRole=="ROLE_ADMIN")
			{
				var newCheckBox = $("<input>");
				newCheckBox.attr("type", "checkbox");
				newCheckBox.attr("name", "editor");
				newCheckBox.attr("value", "editor");
				var newLabel = $("<label>");
				newLabel.text("Editeur");
				var checkBoxDiv = $("<div>");
				checkBoxDiv.attr("id", "accountManagementCheckBox");
				checkBoxDiv.append(newLabel);
				checkBoxDiv.append(newCheckBox);
				
            	$('#adminUserAccountCreationFormId #checkbox_position').append(checkBoxDiv );
				
				if(is_editor==1)
				{
					//alert('hi '+is_editor);
					//newCheckBox.prop('checked');
					//$('#checkMeOut').attr('checked'); 
					newCheckBox.prop( "checked", true );
				}
				
			}else
			{
				$("#adminUserAccountCreationFormId #accountManagementCheckBox").remove(); 
			}
			
			
			$("#adminUserAccountCreationFormId select#user_role_choice").change(function()
			{
    			selectedRole = $(this).children("option:selected").val();
				if(selectedRole=="ROLE_ADMIN")
				{
					var newCheckBox = $("<input>");
					newCheckBox.attr("type", "checkbox");
					newCheckBox.attr("name", "editor");
					newCheckBox.attr("value", "editor");
					var newLabel = $("<label>");
					newLabel.text("Editeur");
					var checkBoxDiv = $("<div>");
					checkBoxDiv.attr("id", "accountManagementCheckBox");
					checkBoxDiv.append(newLabel);
					checkBoxDiv.append(newCheckBox);
				
            		$('#adminUserAccountCreationFormId #checkbox_position').append(checkBoxDiv );
					if(is_editor==1)
					{
						newCheckBox.prop( "checked", true );
					}
				}else
				{
					$("#adminUserAccountCreationFormId #accountManagementCheckBox").remove(); 
				}

			});
		});
