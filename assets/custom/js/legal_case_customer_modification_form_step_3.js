
var collection, addDocumentButton, documentListTable, span,
	itemRefId;


$( window ).on("load", function() 
{
	//"use strict";
	//Do not display the upload file in any case of pre-uploaded file documents
	//$('input[type=file]').hide();
	
	//alert("window is loaded");
	//Determine the current dataset and the buttons container
	collection = $(".legalCaseModificationForm table#documents_list tbody#legalCaseDocuments");
	span = $(".legalCaseModificationForm span#legalCaseDocumentSpanId");
	populateTableWithExistingDocuments(collection);
	
	// création du bouton d'ajout
   	addDocumentButton = $("<strong>");
    addDocumentButton.addClass("ajout-document-legal");
	addDocumentButton.attr("class", "ajout-document-legal btn btn-primary");
    addDocumentButton.text("Ajouter un document");

    span.append(addDocumentButton);
	
	addDocumentButton.on("click", function() {
  		addDocumentButtonAction(collection);
	});
	
	//document.getElementById("salutation").innerHTML += "inside load page<br>";
});
						

function populateTableWithExistingDocuments(collection)
{
	collection.find("tr").each(function() 
	{
		//document.getElementById("salutation").innerHTML += $( this ).find("td").eq(0).text()+"<br>";
		//Handle the display of all the update buttons
		addActionToModifyButton($(this));
		
		//document.getElementById("salutation").innerHTML += $( this ).find("td").eq(0).text()+"<br>";
		//Add action listener to all the delete buttons
		addActionToDeleteButton($(this));
	});
}
						
function addActionToDeleteButton(row_item)
{
	row_item.find("td>strong.btn-danger").eq(0).on( "click", function(e) 
	{
		//e.preventDefault();
		//document.getElementById("salutation").innerHTML += "Delete<br>";
		$(this).closest("tr").remove();
		//document.getElementById("salutation").innerHTML += collection.find("tr td>strong.btn-danger").eq(0).text()+"<br>";
	} );
}

function addActionToModifyButton(row_item)
{
	let fileInput = row_item.find('input[type=file]').eq(0);
	let fileInputLabel = $("<label>");
	//Make the delete button invisible in the span container
	//fileInput.hide(); 
	//fileInput.show();
	fileInputLabel.attr("for", fileInput.attr("id"));
	fileInputLabel.css("margin", "2px");
	fileInputLabel.text("Choisissez le fichier");
	fileInputLabel.attr("class", "btn btn-warning btn-small");
	fileInput.before(fileInputLabel);
	fileInput.css("visibility", "hidden");
	
	fileInput.on("change", function(e) {
		e.preventDefault();
		var i = $(this).prev('label').clone();
		var file = $(this)[0].files[0].name;
		$(this).prev('label').text(file);
		if ($(this).val() != "") 
		{
            $(this).prev('label').css('color', 'white');
			$(this).prev('label').css('backgroundColor', 'green');
			$(this).prev('label').css('border', 'none');
        }else
		{
            $(this).prev('label').css('color', 'transparent');
        }
		
		//Find the hidden field for preffered name
		let documentPreferredNameInput = row_item.find('input[type=hidden]').eq(0);
		//Assign the prefferred name
		//documentPreferredNameInput.attr("value", fileInput[0].files[0].name);
		documentPreferredNameInput.attr("value", file);
		//Update the table data for the preferred name
		row_item.find('td').eq(0).text(file);
		
	});
}

function addDocumentButtonAction(collection)
{
    // on recupère le prototype
    var prototype = collection.data('prototype');
    // On recupère l'index qui indique le nombre de sous-formulaires déjà ajoutés
    var index = collection.data('index');
	
	// ce prototype est sous forme de chaine de caractères
    var newForm = prototype.replace(/__name__/g, index);
	
	
	//document.getElementById("salutation").textContent += collection.html() + "<br>";
	//document.getElementById("salutation").textContent += prototype + '<br>';
	//document.getElementById("salutation").textContent += newForm + '<br>';
	
	
	collection.append(newForm);
	collection.data("index", (index+1));

	//Locate the file type input in the current item object
	//let fileInput = newForm.find('input[type=file]').eq(0);
	//var fileInput = newForm.find("td:nth-child(2)");
	var fileInput = collection.find("tr").last().find('input[type=file]').eq(0);
	let fileInputLabel = $("<label>");
	//Make the delete button invisible in the span container
	//fileDelButton.hide(); 
	
	//fileInput.show();
	fileInputLabel.attr("for", fileInput.attr("id"));
	fileInputLabel.css("margin", "2px");
	fileInputLabel.text("Choisissez le fichier");
	fileInputLabel.attr("class", "btn btn-primary btn-samall");
	fileInput.before(fileInputLabel);
	fileInput.css("visibility", "hidden"); 
	

	//span.append(fileInput);
	
	fileInput.on("change", function(e) {
		e.preventDefault();
		var i = $(this).prev('label').clone();
		var file = $(this)[0].files[0].name;
		$(this).prev('label').text(file);
		if ($(this).val() != "") 
		{
            $(this).prev('label').css('color', 'white');
			$(this).prev('label').css('backgroundColor', 'green');
			$(this).prev('label').css('border', 'none');
        }else
		{
            $(this).prev('label').css('color', 'transparent');
        }
		
		//Find the hidden field for preffered name
		let documentPreferredNameInput = collection.find("tr").last().find('input[type=hidden]').eq(0);
		//Assign the prefferred name
		//documentPreferredNameInput.attr("value", fileInput[0].files[0].name);
		documentPreferredNameInput.attr("value", file);
		//Update the table data for the preferred name
		collection.find("tr").last().find('td').eq(0).text(file);
		//document.getElementById("salutation").textContent += item.html() + " ajout a la liste<br>";
	});
	
	
	//document.getElementById("salutation").innerHTML += "Inside addButton<br>";
	//document.getElementById("salutation").innerHTML += item.html()+"<br>";
	
}