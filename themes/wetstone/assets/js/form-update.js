function country_update() {
	var country = document.getElementById("countryID").value;
	var inUSStates = '<i class="req">*</i>State/Province<br /><select name="state" class="form-input form-input-select" required style="width:150px"><option value="" selected="">Select state</option><option value="AL">AL</option><option value="AK">AK</option><option value="AZ">AZ</option><option value="AR">AR</option><option value="CA">CA</option><option value="CO">CO</option><option value="CT">CT</option><option value="DE">DE</option><option value="DC">DC</option><option value="FL">FL</option><option value="GA">GA</option><option value="HI">HI</option><option value="ID">ID</option><option value="IL">IL</option><option value="IN">IN</option><option value="IA">IA</option><option value="KS">KS</option><option value="KY">KY</option><option value="LA">LA</option><option value="ME">ME</option><option value="MD">MD</option><option value="MA">MA</option><option value="MI">MI</option><option value="MN">MN</option><option value="MS">MS</option><option value="MO">MO</option><option value="MT">MT</option><option value="NE">NE</option><option value="NV">NV</option><option value="NH">NH</option><option value="NJ">NJ</option><option value="NM">NM</option><option value="NY">NY</option><option value="NC">NC</option><option value="ND">ND</option><option value="OH">OH</option><option value="OK">OK</option><option value="OR">OR</option><option value="PA">PA</option><option value="RI">RI</option><option value="SC">SC</option><option value="SD">SD</option><option value="TN">TN</option><option value="TX">TX</option><option value="UT">UT</option><option value="VT">VT</option><option value="VA">VA</option><option value="WA">WA</option><option value="WV">WV</option><option value="WI">WI</option><option value="WY">WY</option></select><i class="select-symbol" style="right:180px">&dtrif;</i>';
	var notUSStates = '<i class="req">*</i> State/Province:<br /><input type="text" name="state" placeholder="MD" class="form-input" size="7" required>';		
			
	var inUSPhone = '<i class="req">*</i> Phone:<input id="phoneVal" type="tel" name="phone" placeholder="(555) 867-5309" class="form-input" required onfocusout="validateUSPhone();">';
	var notUSPhone = '<i class="req">*</i> Phone:<input id="phoneVal" type="tel" name="phone" placeholder="010-56557755" class="form-input" required onfocusout="validateINTPhone();">';
	
	if (country == "United States") {
		document.getElementById("stateID").innerHTML = inUSStates;
		document.getElementById("stateID").innerHTML = inUSPhone;
	} else {
		document.getElementById("phoneID").innerHTML = notUSStates;
		document.getElementById("phoneID").innerHTML = notUSPhone;
	}

}	

function validateUSPhone() {
	var phoneNum = document.getElementById("phoneVal").value;
	var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
		  if(phoneNum.match(phoneno)){
				return true;
			} else {
				alert("Please enter a valid 10 digit phone number.");
				return false;
			}
}	