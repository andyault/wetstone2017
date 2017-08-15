//touch nav for dropdowns
//wouldn't [...collection] and lambdas be nice rn?
var links = document.getElementsByClassName('header-sub-nav-site');
links = Array.prototype.slice.call(links);
links = links.map(function(e) { return e.previousElementSibling });

//hook into touch events
for(var i = 0; i < links.length; i++) {
	var link = links[i];

	link.addEventListener('touchend', function(e) {
		e.preventDefault();

		//if we click on an open menu, just close it 
		if(this.classList.contains('open-nav')) {
			this.classList.remove('open-nav');

			return false
		}

		//otherwise, open this menu up
		closeSubmenus();

		this.classList.add('open-nav');

		return false;
	});
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

/*

//header sub menu toggles
var links = document.querySelectorAll('.site-header-sub');

//toggle class when button clicked
for(var i = 0; i < links.length; i++) {
	links[i].onclick = function(e) {
		this.classList.toggle('active');

		return false;
	}
}

//remove classes when clicked anywhere else
document.body.addEventListener('click', function(e) {
	var classList = e.target.classList;

	//weird step to prevent removing and toggling in the same event
	if(!(classList.contains('site-header-sub') && classList.contains('active'))) {
		var activeMenus = document.querySelectorAll('.site-header-sub.active');

		for(var i = 0; i < activeMenus.length; i++)
			activeMenus[i].classList.remove('active');
	}
}, true);

*/