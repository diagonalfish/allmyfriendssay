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
	$("#index-searchinput").attr("disabled", "disabled");
	var query = $("#index-searchinput").val();
	$("#search-status-message").html("Loading data from Twitter...");

	$.ajax({
		url: "ajax_twitter.php",
		data: {query: query},
		success: function(data) {
			if (data == "0") {
				$("#search-status").hide();
				$("#search-error").show();
				$("#search-error").html("Sorry! We're out of API hits for the moment. Try back soon!");
			}
			else {
				//Next stage
				$("#search-status-message").html("Done... next stage now");
			}
		},
		error: function() {
			$("#search-status").hide();
			$("#search-error").show();
			$("#search-error").html("An error occurred while loading data from Twitter. Sorry!");
		}
	});
}
