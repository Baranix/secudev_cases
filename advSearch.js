$("#advSearchField").hide();

var query = "";

function toggleSearch( adv )
{

	if(adv)
	{
		$("#basicSearchField").hide();
		$("#advSearchField").show();
	}
	else
	{
		$("#basicSearchField").show();
		$("#advSearchField").hide();
	}
}

/*function updateQuery(field)
{
	var value = $("#" + field).parent().find('input').val();
	query += value;
	$("#query").html(query);
}*/

function toggleField( searchFilter )
{
	if( $(searchFilter).find(".searchBy").val() == 1 )
	{
		$(searchFilter).find(".usernameSearch").show();
		$(searchFilter).find(".postSearch").hide();
		$(searchFilter).find(".dateSearch").hide();
	}
	/*else if ($(searchFilter).find(".searchBy").val() == 2)
	{
		$(searchFilter).find(".usernameSearch").hide();
		$(searchFilter).find(".dateSearch").hide();
		$(searchFilter).find(".postSearch").show();
	}*/
	else
		if( $(searchFilter).find(".searchBy").val() == 2 )
		{
			$(searchFilter).find(".usernameSearch").hide();
			$(searchFilter).find(".postSearch").hide();
			$(searchFilter).find(".dateSearch").show();
		}
}

function toggleDates( dateSearch )
{
	if( $(dateSearch).find(".dateCondition").val() == 4 )
	{
		$(dateSearch).find(".date2").show();
	}
	else
	{
		$(dateSearch).find(".date2").hide();
	}
}

function addSearchParams( addButton )
{
	var searchParams = "<br/><br/><span>" +
				"<select class=\"operator\" name=\"operator[]\"><option value=\"1\">And</option><option value=\"2\">Or</option></select> " +
				"<select class=\"searchBy\" name=\"searchBy[]\" onchange=\"toggleField(this.parentNode)\"><option value=\"1\">Username</option><option value=\"2\">Date</option></select> " +
				"<span class=\"usernameSearch\">" +
					"<input type=\"text\" placeholder=\"Username\" name=\"username[]\"> " +
				"</span> " +
				/*"<span class=\"postSearch\" style=\"display:none\">" +
					"<input type=\"text\" placeholder=\"Post\" name=\"post[]\"> " +
				"</span> " +*/
				"<span class=\"dateSearch\" style=\"display:none\">" +
					"<select class=\"dateCondition\" name=\"dateCondition[]\" onchange=\"toggleDates(this.parentNode)\"><option value=\"1\">&gt;=</option><option value=\"2\">&lt;=</option><option value=\"3\">=</option><option value=\"4\">Between</option></select> " +
					"<input type=\"date\" class=\"date1\" name=\"date1[]\"> " +
					"<input type=\"date\" class=\"date2\" name=\"date2[]\" style=\"display:none\"> " +
				"</span> " +
			"</span> ";
	$(addButton).before(searchParams);
}