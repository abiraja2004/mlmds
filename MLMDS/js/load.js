$(document).ready(function(){	
	//$('#big_wrapper').fadeIn(2000);
	$('#big_wrapper').slideToggle("slow");
});

function validate()
{
	var x=document.forms["sear"]["search"].value;
	if(x == null || x=="")
	{
		alert("Enter some word");
		return false;
	}
}
function validateForm()
{
	var y=document.forms["up"]["file"].value;
	if(y == null || y=="")
	{
		alert("Select some file");
		return false;
	}
}
