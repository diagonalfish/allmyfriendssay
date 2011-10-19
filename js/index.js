var boxclicked = 0;

$(document).ready(function() {
	$("#index-authbutton").click(function() {
		window.location = $(this).attr('url');
	});

	$("#index-searchinput").focus(function() {
		if (boxclicked == 0) {
			boxclicked = 1;
			$("#index-searchinput").val("");
		}

	})

	$("#index-searchbutton").click(function() {
		if ($("#index-searchinput").val() != "" && boxclicked == 1) {
			search_1();
		}
		else {
			$("#index-searchinput").val("");
			$("#index-searchinput").focus();
		}
	})
});

function search_1() {
	$("#search-status").show();
	$("#index-searchbutton").hide();
	$("#search-error").hide();
	$("#index-searchinput").attr("disabled", "disabled");
	$("#search-status-message").html("Loading data from Twitter...");

	$.ajax({
		url: "ajax_twitter.php",
		success: function(data) {
			if (data == "0") {
				$("#search-status").hide();
				$("#search-error").show();
				$("#index-searchinput").removeAttr("disabled");
				$("#index-searchbutton").show();
				
				$("#search-error").html("Sorry! We're out of API hits for the moment. Try back soon!");
			}
			else {
				//Next stage
				search_2();
			}
		},
		error: function() {
			$("#search-status").hide();
			$("#search-error").show();
			$("#index-searchinput").removeAttr("disabled");
			$("#index-searchbutton").show();
			
			$("#search-error").html("An error occurred while loading data from Twitter. Sorry!");
		}
	});
}

function search_2() {
	var query = $("#index-searchinput").val();
	$("#search-status-message").html("Performing search...");
	
	$.ajax({
		url: "ajax_search.php",
		data: {query: query},
		success: function(data) {
			if (data == "0") {
				$("#search-status").hide();
				$("#search-error").show();
				$("#index-searchinput").removeAttr("disabled");
				$("#index-searchbutton").show();
				
				$("#search-error").html("No results! Try a different query.");
			}
			else {
				//Next stage
				$("#search-status-message").html("Done!");
				setTimeout("window.location = 'search.php'", 1000);
			}
		},
		error: function() {
			$("#search-status").hide();
			$("#search-error").show();
			$("#index-searchinput").removeAttr("disabled");
			$("#index-searchbutton").show();
				
			$("#search-error").html("An error occurred while searching. Sorry!");
		}
	});
	
}
