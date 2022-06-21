function Confirm(title, msg, $true, $false, $link) {
    var $content = "<div class='dialog-ovelay'>" +
        "<div class='dialog'><header>" +
        " <h3> " + title + " </h3> " +
        "<i class='fa fa-close'></i>" +
        "</header>" +
        "<div class='dialog-msg'>" +
        " <p> " + msg + " </p> " +
        "</div>" +
        "<footer>" +
        "<div class='controls'>" +
        " <button id='true-btn' class='btn btn-sm btn-primary float-right doAction'>" + $true + "</button> " +
        " <button class='btn btn-sm btn-danger cancelAction'>" + $false + "</button> " +
        "</div>" +
        "</footer>" +
        "</div>" +
        "</div>";
    $('body').prepend($content);
    $('.doAction').click(function () {
        if ($link != "")
            window.location.href = $link;
        $(this).parents('.dialog-ovelay').fadeOut(500, function () {
            $(this).remove();
        });
    });
    $('.cancelAction, .fa-close').click(function () {
        $(this).parents('.dialog-ovelay').fadeOut(500, function () {
            $(this).remove();
        });
    });

}

function ConfirmDeleteAccount(title, msg, $true, $false,_user_id) {
    var $content = "<div class='dialog-ovelay'>" +
        "<div class='dialog'><header>" +
        " <h3> " + title + " </h3> " +
        "<i class='fa fa-close'></i>" +
        "</header>" +
        "<div class='dialog-msg'>" +
        " <p> " + msg + " </p> " +
        "</div>" +
        "<footer>" +
        "<div class='controls'>" +
        " <button id='true-btn' class='btn btn-sm btn-primary float-right doAction'>" + $true + "</button> " +
        " <button class='btn btn-sm btn-danger cancelAction'>" + $false + "</button> " +
        "</div>" +
        "</footer>" +
        "</div>" +
        "</div>";
    $('body').prepend($content);
    $('.doAction').click(function () {
        RemoveProfile(_user_id);
        $(this).parents('.dialog-ovelay').fadeOut(500, function () {
            $(this).remove();
        });
    });
    $('.cancelAction, .fa-close').click(function () {
        $(this).parents('.dialog-ovelay').fadeOut(500, function () {
            $(this).remove();
        });
    });

}

function RemoveProfile(user_id) {
    $.ajax({
        type: "POST",
        url: "user_crud.php",
        dataType: 'json',
        data: {
            delete: user_id
        },
        success: function(data) {
            window.location.replace("/php/deauthenticate.php");
        }
    });
}

function Notification(msg, type) {
    document.getElementById('alertmessagebox').innerHTML = "<div class='alert " + type + "' id='alertmessagecontent'><span class='closebtn' onclick='CloseNotification();'>&times;</span>" + msg + "</div>";
    setTimeout(function() {
        $('#alertmessagecontent').fadeOut(500, function() {
            $(this).remove();
        });
    }, 2000);
}

function CloseNotification() {
    $('#alertmessagecontent').fadeOut(500, function() {
        $(this).remove();
    });
}