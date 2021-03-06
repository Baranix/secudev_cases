var selectYear = document.getElementById("year");
var selectMonth = document.getElementById("month");
var selectDay = document.getElementById("day");
var today = new Date();
var year = today.getFullYear() - 18; // Year now minus 18 years to get only applicants who are at least 18.
var month = today.getMonth() + 1; // 0-11 (offset 1, so 1-12)
var day = today.getDate(); // 1-31
var i;
var optionYear="";
for(i=0; i<85; i++)
{
	optionYear += "<option value=\"" + year + "\" >" + year + "</option>";
	year--;
}
selectYear.innerHTML = optionYear;
year = today.getFullYear() - 18;

function genderChange(gender)
{
	// Change the available Salutations depending on Gender

	if( gender == "1" )
	{
		document.getElementById("salutation").innerHTML = "<option value=\"1\" selected>Mr.</option><option value=\"2\">Sir</option><option value=\"3\">Se&ntilde;or</option><option value=\"4\">Count</option>"
	}
	else
	{
		document.getElementById("salutation").innerHTML = "<option value=\"5\" selected>Miss</option><option value=\"6\">Ms</option><option value=\"7\">Mrs</option><option value=\"8\">Madame</option><option value=\"9\">Majesty</option><option value=\"10\">Se&ntilde;ora</option>"
	}
}

function birthdateChange(month, day, year)
{
	// Change the Birthdate depending on the user

	document.getElementById("month").options[month-1].selected = true;
	document.getElementById("day").options[day-1].selected = true;

	var yearInput = document.getElementById("year");
	var numYears = yearInput.options.length;
	for(var i=0;i<numYears;i++)
	{
		if(yearInput.options[i].value == year)
		{
			yearInput.options[i].selected = true;
		}
	}
}

function setDays(monthSelected)
{
	// Change the number of days depending on the month

	var optionDay="";
	var maxDays;
	var yearSelected = parseInt( selectYear.options[selectYear.selectedIndex].text );
	leapYear = !(yearSelected % 4);

	if( parseInt( monthSelected.value ) % 2 )
	{
		maxDays = 31;
	}
	else
		if( monthSelected.value == "2" )
		{
			if( leapYear )
				maxDays = 29;
			else
				maxDays = 28;
		}
		else
		{
			maxDays = 30;
		}

	for( i=1; i<maxDays+1; i++ )
	{
		optionDay += "<option value=\"" + i + "\">" + i + "</option>";
	}

	selectDay.innerHTML = optionDay;
}

function calculateAge(selection, element)
{
	// Calculate if the selected birthdate is more than or equal to 18 years ago
	// Provide warning label if not over 18 years old

	switch(selection)
	{
		case 'month':
			setDays(element);
		case 'day':
			var yearSelected = parseInt( selectYear.options[ selectYear.selectedIndex ].text );
			var monthSelected = parseInt( selectMonth.options[ selectMonth.selectedIndex ].value );
			var daySelected = parseInt( selectDay.options[ selectDay.selectedIndex ].value );
			var warning = document.getElementById("warning_birthdate");
			if( yearSelected == year && monthSelected >= month && daySelected >= day+1 )
			{
				warning.innerHTML = "* Must be at least 18 years old!";
				warning.className = warning.className + " active_warning";
			}
			else
			{
				warning.innerHTML = "";
				warning.className = "warning";
			}
			break;
		default:
			setDays( selectMonth.options[ selectMonth.selectedIndex ] );
			break;
	}
}

function validate(element)
{
	// Checks if the input is alphanumeric only and less than 50 characters
	// Provide warning if input contains special characters or exceeds 50 characters

	var warning = document.getElementById("warning_" + element.id);
	// Warning labels have the id "warning_" + the input object's id

	if ( (! /^[a-zA-Z0-9]+$/.test(element.value)) || element.value.length >= 50 ) // Regular expression for alphanumeric
	{
		warning.innerHTML = "* Alphanumeric characters only and maximum of 50 characters allowed!";
		warning.className = warning.className + " active_warning";
	}
	else
	{
		warning.innerHTML = "";
		warning.className = "warning";
	}
}