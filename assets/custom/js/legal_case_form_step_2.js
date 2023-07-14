
var collection, boutonAjout, span,
	itemRefId;

$( window ).on("load", function() {
	//"use strict";
	
    //alert("window is loaded");
	collection = $("div#legalCaseDocuments");
   	span = $(".legalCaseSubmissionForm span#legalCaseDocumentSpanId");
   	//span.addClass("btn"); 
	//span.addClass("btn-primary");
	//span.attr("class", "btn btn-primary");
		
	
	// création du bouton d'ajout
   	boutonAjout = $("<strong>");
    boutonAjout.addClass("ajout-document-legal");
	boutonAjout.attr("class", "ajout-document-legal btn btn-primary");
    boutonAjout.text("Ajouter un document");

    span.append(boutonAjout);
	

	
	boutonAjout.on("click", function() {
  		addButton(collection);
	});
	
	//document.getElementById("salutation").innerHTML += "inside supprimer ce dirigeant<br>";
});

function addButton(collection){
	
    // on recupère le prototype
    var prototype = collection.data('prototype');
    // On recupère l'index qui indique le nombre de sous-formulaires déjà ajoutés
    var index = collection.data('index');
	// ce prototype est sous forme de chaine de caractères
    var newForm = prototype.replace(/__name__/g, index);
	var item = $("<div>");
	item.attr("id", "item_legalCaseDocument_" + index);
	item.html(newForm);
	
	collection.data("index", (index+1));
	
	//Before displaying, modify the label of the file type input
	//"<label for='"+fileInputId+"' class='btn btn-secondary' style='margin: 2px'>Choisissez le fichier</label>" 

	collection.prepend(item);
	
	//document.getElementById("salutation").textContent += item.html() + " ajout a la liste<br>";
	
	let boutonSuppression = $("<strong>");
    boutonSuppression.attr("class", "btn btn-danger btn-samall");
    boutonSuppression.attr("id", "_legalCaseDocument_" + index);
    boutonSuppression.text("Supprimer ce document");
	
	 // On créé le bouton d'ajouter du dirigeant dans la liste des dirigeants
    let boutonAjouterListe = $("<strong>");
    boutonAjouterListe.text("Ajouter ce document à la liste des documents");
	boutonAjouterListe.attr("class", "btn btn-primary btn-samall");
    boutonAjouterListe.attr("id", "_legalCaseDocument_" + index);

    $("span > strong").remove();
    span.append(boutonAjouterListe);
	span.append(boutonSuppression);
	
	//Locate the file type input in the current item object
	let fileInput = item.find("input[type=file]");
	let fileInputLabel = $("<label>");
	fileInputLabel.attr("for", fileInput.attr("id"));
	fileInputLabel.css("margin", "2px");
	fileInputLabel.text("Choisissez le fichier");
	fileInputLabel.attr("class", "btn btn-primary btn-samall");
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
		let documentPreferredNameInput = item.find('input[type=hidden]').eq(0);
		//Assign the prefferred name
		//documentPreferredNameInput.attr("value", fileInput[0].files[0].name);
		documentPreferredNameInput.attr("value", file);
		//document.getElementById("salutation").textContent += item.html() + " ajout a la liste<br>";
	});
	

	
	//document.getElementById("salutation").innerHTML += documentPreferredNameInput.attr("name") + " ajout a la liste<br>";
	//document.getElementById("salutation").textContent += documentPreferredNameInput.length + " ajout a la liste<br>";
	
	//document.getElementById("salutation").innerHTML += fileInput.attr("id") + " ajout a la liste<br>";
	//document.getElementById("salutation").textContent += item.html() + " ajout a la liste<br>";

	//document.getElementById("salutation").innerHTML += index + " ajout a la liste<br>";
	//document.getElementById("salutation").textContent += item.html() + " ajout a la liste<br>";
	
    // Quand on clique sur le bouton de suppression d'un dirigeant
	boutonSuppression.on("click", function(e) {
		e.preventDefault();

		$("#item_legalCaseDocument_" + index).remove();
		
		$("span > strong").remove();
        span.append(boutonAjout);
		collection.data("index", collection.find(':input').length);
		boutonAjout.on("click", function() {
  			addButton(collection);
		});
		
	});
	
	// on contruit les liens de modification du nouveau formulaire
    const modifier = $("<strong>");
    modifier.html("Modifier");
    modifier.attr("id", 'legalCaseDocument_'+index);
    modifier.attr("class", "btn btn-info");
	
     // on contruit les liens de suppression du nouveau formulaire
    const supprimer = $("<strong>");
    supprimer.html("Supprimer");
    supprimer.attr("id", 'legalCaseDocument_'+index);
    supprimer.attr("class", "btn btn-danger");
	

    const updateBouton2 = $('<strong>');
    updateBouton2.attr("id", 'legalCaseDocument_'+index);
    updateBouton2.text("Enregistrer les modifications");
	updateBouton2.attr("class", "btn btn-primary");
	
	// Quand on clique sur le bouton pour valider l'ajout d'un dirigeant
    boutonAjouterListe.on("click", function(e) {
       
        // On construit le td qui va contenir le lien Modifier et on y ajoute le lien
        const tdModifier = $("<td>");
        tdModifier.append(modifier);
        
        // On construit le td qui va contenir le lien Supprimer et on y ajoute le lien
        const tdSupprimer = $("<td>");
        tdSupprimer.append(supprimer);
		
		// On recupère les noms et prénoms. ils sont au premier input du formulaire
		//const nom = collection.find('input').first();
		let fileName = fileInput[0].files[0].name;
		
		//document.getElementById("salutation").innerHTML += fileName + " ajout a la liste<br>";
		
		// On construit le td qui va recevoir les noms et prénoms et on y ajoute les noms et prénoms
        const tdNom = $("<td>");
		tdNom.attr("id", index);
		tdNom.text(fileName);
		
		// On construire une ligne du tableau et on y ajoute les données du dirigeant ajouté
        const newLine = $("<tr>");
        newLine.attr("id", 'tr_legalCaseDocument_'+index);
        newLine.append(tdNom);
        newLine.append(tdModifier);
        newLine.append(tdSupprimer);
		
		// on ajoute la ligne au tbody du tableau de la liste
        const tbody = $("#documents_list>tbody");
        tbody.append(newLine);
		
		$("#item_legalCaseDocument_" + index).css("display", "none");
		
		$("span > strong").remove();
        span.append(boutonAjout);
		collection.data("index", collection.find(':input').length);
		boutonAjout.on("click", function() {
  			addButton(collection);
		});
		
		document.getElementById("salutation").innerHTML += index + " ajout<br>";
		
    });
	
	// Quand on clique sur le bouton Supprimer dans le tableau
    supprimer.on("click", function(e) {
		e.preventDefault();
		$("#item_"+$(this).attr("id")).remove(); 
        this.closest('tr').remove();
    });
	
	// Quand on clique sur le bouton Modifier dans le tableau
    modifier.on("click", function(e){
		e.preventDefault();
		itemRefId = "item_"+$(this).attr("id");
		const legalCaseDocument = $("#item_"+$(this).attr("id"));
		legalCaseDocument.css("display", "block");

		//collection.prepend(item);
		$("span > strong").remove();
		span.prepend(updateBouton2);
		
		updateBouton2.on("click", function(e){
			e.preventDefault();

			//const legalCaseDocument = $("#"+itemRefId);
			let fileInput = legalCaseDocument.find("input[type=file]");
			let fileInputLabel = $("<label>");
			fileInputLabel.attr("for", fileInput.attr("id"));
			fileInputLabel.css("margin", "2px");
			fileInputLabel.text("Choisissez le fichier");
			fileInputLabel.attr("class", "btn btn-primary btn-samall");
			fileInput.before(fileInputLabel);
			fileInput.css("visibility", "hidden");
			let fileName = fileInput[0].files[0].name;
			
			const legalCaseDocument_tr = $("#documents_list>tbody").find("tr#tr_"+$(this).attr("id"));
			const legalCaseDocument_name_td = legalCaseDocument_tr.find("td").first();
			legalCaseDocument_name_td.text(fileName);
			
			document.getElementById("salutation").innerHTML += legalCaseDocument_name_td.text() + " modifier<br>";
			
			
			legalCaseDocument.css("display", "none");
		
			$("span > strong").remove();
        	span.append(boutonAjout);
			collection.data("index", collection.find(':input').length);
			boutonAjout.on("click", function() {
  				addButton(collection);
			});
		
			//document.getElementById("salutation").innerHTML += itemRefId + " inside supprimer ce dirigeant<br>";
    	});

		
    });
	
	//document.getElementById("salutation").innerHTML += "inside ajouter ce dirigeant<br>";
    
    // On met à jour le design du nouveau formulaire
    //designNewForm(newForm.querySelector('#item_dirigeants_'+index));
	designNewForm($("#item_dirigeants_" + index)); 
	
	//document.getElementById("salutation").innerHTML += "inside supprimer ce dirigeant<br>";

}

	
	
	
// fonction qui crée un div.row et lui ajoute un élément
function putIntoDivRow(element, divClass)
{
    let div = $("<div>");
	//div.attr("class", divClass);
	div.addClass(divClass)
    element.before(div);
    div.append(element);
	//document.getElementById("salutation").innerHTML += " inside putIntoDivRow <br>"
}	

// Fait le design du sous formulaire inséré
function designNewForm(societe_dirigeants){
    
	
    // Début design ......les champs
    // on recupère tous ses divs enfants
    //const newFormChildren = societe_dirigeants.children();
	var divFormChildren = societe_dirigeants.children();
	divFormChildren = divFormChildren.eq(0).children();
	divFormChildren = divFormChildren.eq(0).children();
	//divFormChildren = divFormChildren.eq(0).children();
	//divFormChildren = divFormChildren[0].children();
	//const newFormChildren = divFormChildren[0].children;
	
	

    const firstName = divFormChildren.eq(0);
    const lastName = divFormChildren.eq(1);
	const adresseDiv = divFormChildren.eq(2);
	const adresseDivChildren = adresseDiv.children();
	const adresseLabel = adresseDivChildren.eq(0);
	var addressDetails = adresseDivChildren.eq(1);
	addressDetails = addressDetails.children(); 
	const town = addressDetails.eq(0);
	const neighborhood = addressDetails.eq(1);
	const street = addressDetails.eq(2);
	const postalCode = addressDetails.eq(3);
	const telephone = addressDetails.eq(4);
    const email = addressDetails.eq(5);
	
	//document.getElementById("salutation").innerHTML += email.text() + " inside design <br>";
	//document.getElementById("salutation").textContent += email.html() + " inside design <br>";
	
	// On donne la taille de 18px au label Adresse
    adresseLabel.css("font-size", "20px");
	adresseLabel.css("top-margin", "2");
	//adresseLabel.parent().css("fontSize", "18px");
	
	// On place les champs dans les div.row et dans les div.col-md-x
    putIntoDivRow(firstName, "col-md-3");
    putIntoDivRow(lastName, "col-md-3");
	putIntoDivRow(adresseLabel, "col-md-3");
    putIntoDivRow(town, "col-md-3");
    putIntoDivRow(neighborhood, "col-md-3");
    putIntoDivRow(street, "col-md-3");
    putIntoDivRow(postalCode, "col-md-3");
    putIntoDivRow(telephone, "col-md-3");
    putIntoDivRow(email, "col-md-3");
	
	// On attribue les classes 'form-control', et 'ie7-margin' conformément au thème du template
	firstName.find('input').addClass('form-control ie7-margin');
    lastName.find('input').addClass('form-control ie7-margin');
    town.find('input').addClass('form-control ie7-margin');
    neighborhood.find('input').addClass('form-control ie7-margin');
    street.find('input').addClass('form-control ie7-margin');
    postalCode.find('input').addClass('form-control ie7-margin');
    telephone.find('input').addClass('form-control ie7-margin');
    email.find('input').addClass('form-control ie7-margin');
	
	//document.getElementById("salutation").textContent += adresseLabel.parent().html() + " inside design <br>"
	
}
	
	


/*


// Construit le span qui contient la consigne
function consigneConstructor(consigne){
    let span = document.createElement('span');
    span.innerHTML = consigne;
    span.classList.add("defaultHidden", "consigne");

    return span;
}

// Construit le span qui contient la contrainte
function constraintConstructor(constraint){
    let span = document.createElement('span');
    span.innerHTML = constraint;
    span.classList.add("defaultHidden", "contrainte");

    return span;
}

// construit le span qui contient l'étoile rouge
function redStarConstructor(){
    let span = document.createElement('span');
    span.innerHTML = '*';
    span.classList.add("required-field");

    return span;
}


// fonction qui affiche et cache les consigne des champs obligatoires
function displayConsigne (element, consigne){
    if(isEmpty(element)){
        consigne.style.display = 'inline';  
    }else
    {
        consigne.style.display = 'none';
    }
}

// fonction qui vérifie si un champ est vide
function isEmpty (element){
    let content = element.value;
    content = content.replace(/\s+/g, '');

    return content.length == 0 ? true : false;
}

// fonction qui crée un div.row et lui ajoute un élément
function putIntoDivRow(element, divClass){
    let div = document.createElement('div');
    div.classList.add(divClass);
    element.before(div);
    div.append(element);
}


// Ajoute la consigne à un champ
function addConsigne(element, consigne){
    let elementConsigne = consigneConstructor(consigne);
    element.querySelector('label').append(elementConsigne);

    return elementConsigne;
}

// Ajoute la consigne à un champ
function addConstraint(element, constraint){
    let elementConstraint= constraintConstructor(constraint);
    element.querySelector('label').append(elementConstraint);

    return elementConstraint;
}

// ajoute l'étoile rouge à un champ
function addRedStar(element){
    
    element.querySelector('label').append(redStarConstructor());
}

//  Calcule l'âge à partir d'une date de naissance
function getAge(dateInput){
    return Math.round(((new Date()).getTime() - (new Date(dateInput.value)).getTime())/(1000*3600*24*365) );

}

 // fonction qui vérifie si un champ contient un e-mail valide
 function  isEmailValid(element){

    return element.value.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g);
};
*/
