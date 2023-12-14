"use strict";
$(window).on("DOMContentLoaded", function () {
    var $window = $(window);
    $(".loader-bar").animate({ width: $window.width() }, 2000);
    setTimeout(function () {
        while ($(".loader-bar").width() == $window.width()) {
            removeloader();
            break;
        }
    }, 2500);

    //Welcome Message (not for login page)
    function notify(message, type) {
        $.growl(
            {
                message: message,
            },
            {
                type: type,
                allow_dismiss: false,
                label: "Cancel",
                className: "btn-xs btn-inverse",
                placement: {
                    from: "bottom",
                    align: "right",
                },
                delay: 2500,
                animate: {
                    enter: "animated fadeInRight",
                    exit: "animated fadeOutRight",
                },
                offset: {
                    x: 30,
                    y: 30,
                },
            }
        );
    }

    // notify('Welcome to Quantum Admin', 'inverse');
    $(".loader-bg").fadeOut("slow");
});
$(document).ready(function () {
    $(function () {
        var sidebar = $(".sidebar");
        var navbar = $(".navbar");

        $('[data-toggle-nav="sidebar"').on("click", () => {
            $("#backdrop").toggleClass("sidebar-backdrop show");
            $(".sidebar.fixed-top#sidebar").toggleClass("show");
            $("#x-toggle").toggleClass("fa-bars");
            $("#x-toggle").toggleClass("fa-times");
        });
        $("#backdrop").on("click", () => {
            $(".sidebar.sidebar-offcanvas").toggleClass("show");
            $("#backdrop").toggleClass("sidebar-backdrop show");
            $("#x-toggle").toggleClass("fa-bars");
            $("#x-toggle").toggleClass("fa-times");
        });
        var droppdown = $(".drop_down");
        $(droppdown).on("click", function () {
            $(".link.nav-link > #dd-icon").toggleClass("fa-plus");
            $(".link.nav-link > #dd-icon").toggleClass("fa-minus");
        });
        // if(current)
        var current = location.pathname;
        // location.ho 
        function addActiveClass(element) {
            // $(".drop_down").on("mousedown", function () {
            //     if (element.attr("href", "#").indexOf(current) !== 1) {
            //         $(document).find(".nav-link").removeClass("active");
            //         $(document).find(".nav-link.drop_down").addClass("active");
            //     }
            // });

            if (current === "") {
                // return null;
                $("ul>li>a.link.nav-link").first().addClass("active");
            } else {
                if (element.attr("href").indexOf(current) !== -1) {
                    element.addClass("active");
                }
            }
        }
        // $("ul>li>a").each(function () {
        //     var $this = $(this);
        //     addActiveClass($this);
        // });
        $("ul>li>a.link", sidebar).each(function () {
            var $this = $(this);
            addActiveClass($this);
        });
    });
});

// toggle full screen
function toggleFullScreen() {
    if (
        !document.fullscreenElement && // alternative standard method
        !document.mozFullScreenElement &&
        !document.webkitFullscreenElement
    ) {
        // current working methods
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen(
                Element.ALLOW_KEYBOARD_INPUT
            );
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }
}

$(window).scroll(() => {
    $(window).scrollTop() > 100
        ? $(".scrollTop").fadeIn()
        : $(".scrollTop").fadeOut();
});
$(".scrollTop").click((e) => {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, 0);
    return 0;
});
function customDate() {
    var _date = document.querySelectorAll(".date"),
        date = new Date(),
        y = date.getFullYear(),
        m = date.getMonth(),
        d = date.getDay(),
        f = date.getUTCDate();
    let months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];
    let days = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
    ];
    var CurrentDate = days[d] + ", " + f + " " + months[m] + ", " + y;
    _date.forEach((elem) => {
        elem.innerText = CurrentDate;
    });
}
customDate();
setInterval(() => {
    $(".time").text(new Date().toLocaleTimeString());
}, 1000);
