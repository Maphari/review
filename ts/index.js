"use strict";
var modal = document.getElementById("modal");
var writeReviewButton = document.getElementById("write-review-button");
var closeIcon = document.getElementById("close-icon");
var form_submit = document.querySelector("#form-review");
var stars = document.querySelectorAll(".star");
var username = document.querySelector("#usernames");
var user_email = document.querySelector("#email");
var user_review = document.querySelector("#review");
var error_message = document.querySelector("#error_message");
var review_section = document.getElementById("modal-section-1");
var thank_section = document.getElementById("modal-section-2");
var user = document.getElementById("user-thanks");
var xhr = new XMLHttpRequest();
var userClicked = false; // Track whether the user has clicked a star
var clicked = 0;
var rating_count = 0;
function resetStars() {
    stars.forEach(function (star) {
        star.classList.remove("text-violet-500");
    });
}
var validate_user_email = function (email) {
    var email_reg = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return email_reg.test(email);
};
function thank_user(username_thanks) {
    thank_section.classList.remove("hidden");
    review_section.classList.add("hidden");
    user.innerHTML = username_thanks;
    setTimeout(function () {
        thank_section.classList.add("hidden");
        modal.classList.add("hidden");
        review_section.classList.remove("hidden");
        username.value = "";
        user_email.value = "";
        user_review.value = "";
        resetStars();
    }, 1000);
}
function sendDataToPHP(user_data_object) {
    var xhr = new XMLHttpRequest();
    var url = "/review/review.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json; charset=utf-8");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                if (data.status === "success") {
                    thank_user(username.value);
                    window.location.reload();
                }
                else {
                    error_message.innerHTML = data.message;
                }
            }
            else {
                console.error("An error occurred while sending the data:", xhr.status, xhr.statusText);
                error_message.innerHTML = "An error occurred while sending the data.";
            }
        }
    };
    xhr.send(JSON.stringify(user_data_object));
}
// thank_user(username.value);
function sendData(rating_count) {
    var data_to_send = {
        name: username.value,
        email: user_email.value,
        review: user_review.value,
        rating: rating_count,
    };
    error_message.innerHTML = "";
    if (!data_to_send.name && !data_to_send.email && !data_to_send.review) {
        error_message.innerHTML = "Please fill all the required Field";
    }
    else if (!validate_user_email(data_to_send.email)) {
        error_message.innerHTML = "Please enter a valid email";
    }
    else {
        sendDataToPHP(data_to_send);
    }
}
stars.forEach(function (star) {
    star.addEventListener("mouseenter", function () {
        if (!userClicked) {
            // Only change the stars if the user hasn't clicked yet
            var rating = star.getAttribute("data-rating");
            resetStars();
            for (var count = 1; count <= rating; count++) {
                var icon_add_style = document.getElementById("rating-star_".concat(count));
                icon_add_style.classList.add("text-violet-500");
            }
        }
    });
    star.addEventListener("mouseleave", function () {
        if (!userClicked) {
            // Only change the stars if the user hasn't clicked yet
            resetStars();
        }
    });
    star.addEventListener("click", function (event) {
        userClicked = true; // Set the flag to true when the user clicks a star
        var rating = star.getAttribute("data-rating");
        rating_count = rating;
        var change_context = document.querySelector(".text-2xl.text-violet-500");
        clicked++;
        if (clicked > 1) {
            resetStars();
            for (var count = 1; count <= rating; count++) {
                var icon_add_style = document.getElementById("rating-star_".concat(count));
                icon_add_style.classList.add("text-violet-500");
            }
        }
    });
});
form_submit.addEventListener("submit", function (event) {
    event.preventDefault();
    sendData(rating_count);
});
writeReviewButton.addEventListener("click", function () {
    modal.classList.remove("hidden");
});
closeIcon.addEventListener("click", function () {
    modal.classList.add("hidden");
});
modal.addEventListener("mouseleave", function () {
    if (!userClicked) {
        // Reset stars if the user didn't click
        resetStars();
    }
});
