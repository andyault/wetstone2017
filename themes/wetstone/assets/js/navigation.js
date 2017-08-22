//touch nav for dropdowns
//wouldn't [...collection] and lambdas be nice rn?
var links = document.getElementsByClassName('header-sub-nav-site');
links = Array.prototype.slice.call(links);
links = links.map(function(e) { return e.previousElementSibling });

//hook into touch events
for(var i = 0; i < links.length; i++) {
	var link = links[i];

	var handleTouch = function(e) {
		e.preventDefault();
		e.stopPropagation();

		//if we click on an open menu, just close it 
		if(this.classList.contains('open-nav')) {
			this.classList.remove('open-nav');

			return false
		}

		//otherwise, open this menu up
		closeSubmenus();

		this.classList.add('open-nav');

		return false;
	}

	//hooking
	link.addEventListener('touchend', handleTouch);

	//not too happy with this but oh well - always support dropdown for touch, only for click if it's mobile menu
	if(link.parentElement.classList.contains('header-site-dropdown'))
		link.addEventListener('click', handleTouch);
}

//business logic
var closeSubmenus = function(e) {
	if(e && e.target && e.target.classList.contains('header-link') && e.target.classList.contains('open-nav'))
		return;

	var links = document.querySelectorAll('.header-link.open-nav');

	for(var i = 0; i < links.length; i++)
		links[i].classList.remove('open-nav');
}

//mousedown because touchstart messes up scrolling
document.body.addEventListener('mousedown', closeSubmenus);

closeSubmenus();