var $sidebarToggler = $('#sidebarToggler');
var $sidebar = $('#sidebar');
var $main = $('#main');

function toggleSidebar() {
	if ($sidebar.hasClass('show') && $main.hasClass('sidebar-opened')) {
		$main.removeClass('sidebar-opened');
		setTimeout(function () {
			$sidebar.removeClass('show');
			$main.removeClass('transform-animation');
		}, 233);
	} else {
		$sidebar.addClass('show');
		$main.addClass('transform-animation');
		$main.addClass('sidebar-opened');
	}
}

$sidebarToggler.on('click', function (e) {
	e.preventDefault();
	toggleSidebar();
});
var mediaQuery = window.matchMedia('(min-width: 768px)');
mediaQuery.addListener(widthChange);

function widthChange(mediaQuery) {
	if (mediaQuery.matches) {
		$main.removeClass('transform-animation');
	} else {
		$main.removeClass('sidebar-opened');
		$sidebar.removeClass('show');
		$main.removeClass('transform-animation');
	}
}

widthChange(mediaQuery);
