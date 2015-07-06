var snippetsDir = "assets/snippets/";

$("#loginLink").click(function(){
	var currentContent = $(".quickform").html();
	if(currentContent==""){
		$(".quickform").load(snippetsDir+"loginForm.html");
	}
	else{
		$(".quickform").html("");
	}
});

$("#registerLink").click(function(){
	var currentContent = $(".quickform").html();
	if(currentContent==""){
		$(".quickform").load(snippetsDir+"registerForm.html");	
	}
	else{
		$(".quickform").html("");
	}
});

$("#uploadLink").click(function(){
	var currentContent = $(".quickform").html();
	if(currentContent==""){
		$(".quickform").load(snippetsDir+"uploadForm.html");	
	}
	else{
		$(".quickform").html("");
	}
});

$("#faqLink").click(function(){
	$(".div-title").text("FAQ");
	$(".center-div-content").load(snippetsDir+"faq.html");
	showContentDiv();
});

$("#plansLink").click(function(){
	$(".div-title").text("Plans");
	$(".center-div-content").load(snippetsDir+"plans.html");
	showContentDiv();
});

$("#contactLink").click(function(){
	$(".div-title").text("Contact Us");
	$(".center-div-content").load(snippetsDir+"contact.html");
	showContentDiv();
});

$("#aboutLink").click(function(){
	$(".div-title").text("About Us");
	$(".center-div-content").load(snippetsDir+"about.html");
	showContentDiv();
});

$(".center-div-close").click(function(){
	$(".center-div").hide("slow");
});

function showContentDiv(){
	$(".center-div").show("slow");
}