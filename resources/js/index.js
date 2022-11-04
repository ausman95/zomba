window.addEventListener('load', function () {
    const loader = document.querySelector('#loader');
    loader.className += " hidden";
});

// check if window is small screen, hide the sidebar initially if so
let window_size = window.innerWidth;
let init_sidebar_hide = window_size < 1078;

if (init_sidebar_hide) {
    let sidebar = document.querySelector("#sidebar");

    if (sidebar) {
        sidebar.className += " collapsed";
    }
}


$(document).ready(function () {
    $("#sidebar-toggler").on('click', function () {
        let sidebar = $("#sidebar");
        let mainContent = $("#main-content");

        if (sidebar.hasClass('collapsed')) {
            sidebar.removeClass('collapsed');
            mainContent.removeClass('expanded');

        } else {
            sidebar.addClass('collapsed');
            mainContent.addClass('expanded');
        }
    });

    $("#sidebar-closer").on('click', function () {
        let sidebar = $("#sidebar");
        let mainContent = $("#main-content");

        sidebar.addClass('collapsed');
        mainContent.addClass('expanded');
    });
});
