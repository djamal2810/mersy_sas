/* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
function myFunction() {
  var x = document.getElementById("navigation-menu");
  if (x.className === "navigation-menu") {
    x.className += " responsive";
	//alert('hi');
  } else {
    x.className = "navigation-menu";
  }
}	
