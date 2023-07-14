window.changeTabOnClick = function changeTabOnClick(navLinksId, navTabsId, anchorId, tabContentId) {
  		//alert("Hello");
  		//document.getElementById("salutation").innerHTML = "Hello";
		//document.getElementById("salutation").innerHTML = "Hello<br>";

		let anchorElements = document.getElementById(navLinksId).querySelectorAll('a');
		for (let i = 0; i < anchorElements.length; i++)
		{
			anchorElements[i].classList.remove('active');
		}
		
		let tabElements = document.getElementById(navTabsId).querySelectorAll('div.tab-pane');
		for (let i = 0; i < tabElements.length; i++)
		{
			tabElements[i].classList.remove('active');
			tabElements[i].classList.add('fade');
		}
	
		var anchorElement = document.getElementById(anchorId);
		anchorElement.classList.add('active');
		var tabElement = document.getElementById(tabContentId);
		tabElement.classList.remove('fade');
		tabElement.classList.add('active');
     	//anchorElement.click();
	
	/*
		for (let i = 0; i < tabElements.length; i++)
		{
			document.getElementById("salutation").innerHTML += anchorElements[i].className;
			document.getElementById("salutation").innerHTML += "<br>";
			document.getElementById("salutation").innerHTML += tabElements[i].className;
			document.getElementById("salutation").innerHTML += "<br>";
		}
	*/
}