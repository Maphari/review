"use strict";

const modal = document.getElementById("modal") as HTMLElement;
const writeReviewButton = document.getElementById(
  "write-review-button"
) as HTMLButtonElement;
const closeIcon = document.getElementById("close-icon") as HTMLElement;
const form_submit = document.querySelector("#form-review") as HTMLFormElement;
const stars = document.querySelectorAll(".star") as HTMLElement | any;
const username = document.querySelector("#usernames") as HTMLInputElement;
const user_email = document.querySelector("#email") as HTMLInputElement;
const user_review = document.querySelector("#review") as HTMLTextAreaElement;
const error_message = document.querySelector(
  "#error_message"
) as HTMLParagraphElement;
const review_section = document.getElementById(
  "modal-section-1"
) as HTMLElement;
const thank_section = document.getElementById("modal-section-2") as HTMLElement;
const user = document.getElementById("user-thanks") as HTMLSpanElement;

const xhr = new XMLHttpRequest();

let userClicked = false; // Track whether the user has clicked a star
let clicked = 0;
let rating_count = 0;

interface IData_to_send {
  name: string;
  email: string;
  review: string;
  rating: number;
}

function resetStars(): void {
  stars.forEach((star: any) => {
    star.classList.remove("text-violet-500");
  });
}

const validate_user_email: (email: string) => boolean = (email: string) => {
  const email_reg: RegExp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  return email_reg.test(email);
};

function thank_user(username_thanks: string) {
  thank_section.classList.remove("hidden");
  review_section.classList.add("hidden");
  user.innerHTML = username_thanks;

  setTimeout(() => {
    thank_section.classList.add("hidden");
    modal.classList.add("hidden");
    review_section.classList.remove("hidden");
    username.value = "";
    user_email.value = "";
    user_review.value = "";
    resetStars();
  }, 1000);
}

function sendDataToPHP(user_data_object: object) {
  const xhr = new XMLHttpRequest();
  const url = "/review/review.php";

  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/json; charset=utf-8");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const data = JSON.parse(xhr.responseText);
        if (data.status === "success") {
          thank_user(username.value);
          window.location.reload();
        } else {
          error_message.innerHTML = data.message;
        }
      } else {
        console.error(
          "An error occurred while sending the data:",
          xhr.status,
          xhr.statusText
        );
        error_message.innerHTML = "An error occurred while sending the data.";
      }
    }
  };

  xhr.send(JSON.stringify(user_data_object));
}

// thank_user(username.value);
function sendData(rating_count: number) {
  const data_to_send: IData_to_send = {
    name: username.value,
    email: user_email.value,
    review: user_review.value,
    rating: rating_count,
  };

  error_message.innerHTML = "";

  if (!data_to_send.name && !data_to_send.email && !data_to_send.review) {
    error_message.innerHTML = "Please fill all the required Field";
  } else if (!validate_user_email(data_to_send.email)) {
    error_message.innerHTML = "Please enter a valid email";
  } else {
    sendDataToPHP(data_to_send);
  }
}

stars.forEach((star: any) => {
  star.addEventListener("mouseenter", function () {
    if (!userClicked) {
      // Only change the stars if the user hasn't clicked yet
      const rating: any = star.getAttribute("data-rating");
      resetStars();

      for (let count = 1; count <= rating; count++) {
        const icon_add_style = document.getElementById(
          `rating-star_${count}`
        ) as HTMLElement;
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

  star.addEventListener("click", function (event: Event) {
    userClicked = true; // Set the flag to true when the user clicks a star
    const rating = star.getAttribute("data-rating");
    rating_count = rating;
    const change_context = document.querySelector(
      ".text-2xl.text-violet-500"
    ) as HTMLElement;
    change_context.textContent = rating + " / 5.0";

    clicked++;

    if (clicked > 1) {
      resetStars();
      for (let count = 1; count <= rating; count++) {
        const icon_add_style = document.getElementById(
          `rating-star_${count}`
        ) as HTMLElement;
        icon_add_style.classList.add("text-violet-500");
      }
    }
  });
});

form_submit.addEventListener("submit", function (event: Event) {
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
