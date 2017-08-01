for(var name in oldVals) {
	if(oldVals.hasOwnProperty(name)) {
		var value = oldVals[name];

		var elems = document.getElementsByName(name);

		if(elems.length) {
			if(elems[0].type !== 'radio')
				elems[0].value = value;
			else { //radio buttons
				for(var i = 0; i < elems.length; i++) {
					var radio = elems[i];

					if(radio.value == value[0]) {
						radio.checked = true;
						break;
					}
				}
			}
		} else { //either checkboxes or err message
			elems = document.getElementsByName(name + '[]');

			if(elems.length) { //they were checkboxes
				var labels = value.split(', ');

				for(var i = 0; i < elems.length; i++) {
					var checkbox = elems[i];

					for(var j = 0; j < labels.length; j++) {
						if(checkbox.value == labels[j]) {
							//are we really deep enough though?
							checkbox.checked = true;
						}
					}
				}
			}
		}
	}
}

//history.replaceState({}, undefined, location.pathname);