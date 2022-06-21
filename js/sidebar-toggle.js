$(document).ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    if (localStorage.getItem("toggle") === "false") {
        $('#sidebar, #content').toggleClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=false]').attr('aria-expanded', 'true');
    }

    $('#sidebarCollapse').on('click', function () {
        if (localStorage.getItem("toggle") === "true") {
            localStorage.setItem("toggle", "false");
        } else {
            localStorage.setItem("toggle", "true");
        }
        $('#sidebar, #content').toggleClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $('[data-toggle="tooltip"]').tooltip({
        'delay': {
            show: 800,
            hide: 200
        }
    });
});

var close = document.getElementsByClassName("closebtn");
var i;

for (i = 0; i < close.length; i++) {
    close[i].onclick = function () {
        var div = this.parentElement;
        div.style.opacity = "0";
        setTimeout(function () {
            div.style.display = "none";
        }, 600);
    }
}

setTimeout(function () {
    var alerts = document.getElementsByClassName("alert");
    Array.prototype.forEach.call(alerts, function (el) {
        el.style.opacity = "0";
        setTimeout(function () {
            el.style.display = "none";
        }, 800);
    });
}, 2000);

(function ($) {
    $('#logout-link').click(function () {
        Confirm('Logout', 'You\'re about to log out. Please confirm.', 'confirm', 'cancel', '/php/deauthenticate.php');
    })
})(jQuery)

