//touch nav for dropdowns
var submenus = document.getElementsByClassName('header-sub-nav-site');

//[...htmlcollection] is a godsend
var links = Array.prototype.slice.call(submenus);
links = links.map(function(e) { return e.previousSibling });

//hook into touch events
for(let i = 0; i < links.length; i++) {
	let link = links[i];
	link.submenu = submenus[i];

	link.addEventListener('touchend', function(e) {
		closeSubmenus();

		this.classList.toggle('active');

		e.preventDefault();
		return false;
	});
}

//business logic
var closeSubmenus = function() {
	var links = document.querySelectorAll('.header-link.active');

	for(var i = 0; i < links.length; i++)
		links[i].classList.remove('active');
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