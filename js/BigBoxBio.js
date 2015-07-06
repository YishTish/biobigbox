var snippetsDir = "html/";
/*
$("#loginLink").click(function(){
	var currentContent = $(".quickform").html();
	if(currentContent==""){
		$(".quickform").load(snippetsDir+"loginForm.html");
	}
	else{
		$(".quickform").html("");
	}
});
*/


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
};

$('body').click(function(e){
    if ($(e.target).hasClass('bg-link') || 
        $(e.target).hasClass('site-wrapper-inner') || 
        $(e.target).hasClass('masthead')) {
            $('a#background-link').on('click', function(e){
                e.preventDefault();
                if($('body').hasClass('uploadBG'))
                    window.open('/home/backgroundimages/link?image=webimage2', '_blank');
                else
                    window.open('/home/backgroundimages/link?image=webimage1', '_blank');
            });
            $('a#background-link').click();
    }
});
