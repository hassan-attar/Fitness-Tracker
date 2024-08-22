$(document).ready(function () {
  const loadReviews = (data) => {
    clearReviews();
    let reviews;
    // For Reviews

    if (data.length === 0) {
      reviews =
        "<p class='alert-message'>Be the first one who let us know how it tastes!</p>";
    } else {
      reviews = data
        .map(
          (review) => `<div class="review">
             <div class="header">
               <h5 class="user-name">${review.firstName}</h5>
               <span class="date">${timeAgo(review.date)}</span>
             </div>
             <div class="comment">
               ${review.comment}
             </div>
             <div class="footer">
               <span>
               ${[1, 2, 3, 4, 5]
                 .map((num) =>
                   review.rate >= num
                     ? `<ion-icon name="star" class="star"></ion-icon>`
                     : `<ion-icon name="star-outline" class="star"></ion-icon>`
                 )
                 .join("")}
               </span>
             </div>
           </div>`
        )
        .join("");
    }
    $("#modal .main .user-reviews").html(reviews);
  };

  const clearReviews = () => {
    $("#modal .main .user-reviews").empty();
    $("#modal #review").val("");
    $("#modal .main .review-form .star-group > input:checked").prop(
      "checked",
      false
    );

    Array.from(
      $("#modal .main .review-form .star-group label ion-icon")
    ).forEach((el) => {
      el.setAttribute("name", "star-outline");
    });
  };
  const splitToLi = (instruction) => {
    return instruction.split(/\d+\./g).filter((el) => el.length > 0);
  };
  const timeAgo = (date) => {
    const seconds = Math.floor((new Date() - new Date(date)) / 1000);

    let interval = Math.floor(seconds / 31536000);
    if (interval > 1) {
      return interval + " years ago";
    }

    interval = Math.floor(seconds / 2592000);
    if (interval > 1) {
      return interval + " months ago";
    }

    interval = Math.floor(seconds / 86400);
    if (interval > 1) {
      return interval + " days ago";
    }

    interval = Math.floor(seconds / 3600);
    if (interval > 1) {
      return interval + " hours ago";
    }

    interval = Math.floor(seconds / 60);
    if (interval > 1) {
      return interval + " minutes ago";
    }

    if ((interval = 1)) {
      return "a minute ago";
    }

    if (seconds < 10) return "just now";

    return Math.floor(seconds) + " seconds ago";
  };

  function closeModal(e) {
    $("#modal").fadeOut(200);
    $("#overlay").fadeOut(300);

    $("#modal .main .instruction-content").remove();
    $("#modal > .header .title").remove();
    clearReviews();
  }

  function openModal(e) {
    $("#modal").fadeIn(300);
    $("#overlay").fadeIn(200);
  }

  // MODAL
  //close
  $("#modal .close-modal").on("click", closeModal);
  $("#overlay").on("click", closeModal);
  //open

  $("#modal .close-modal").on("click", closeModal);
  $("#overlay").on("click", closeModal);
  //open

  $(".recipe-card .action").on("click", function (e) {
    openModal();
    const id = e.target.dataset.recipeId;
    $.ajax({
      url: "api/recipe.php?id=" + id,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      statusCode: {
        200: function (data) {
          // for Instruction
          const instructions = splitToLi(data.instruction)
            .map((instruction) => `<li>${instruction}</li>`)
            .join("");

          $("#modal > .header").prepend(
            `<h3 class="title" data-recipe-id=${id}>${data.title}</h3>`
          );
          $("#modal .main").prepend(`<div class="instruction-content">
         <h4>Cooking steps:</h4>
         <ol class="instruction">
           ${instructions}
         </ol>
       </div>`);
        },
      },
    });
    $.ajax({
      url: "api/review.php?id=" + id,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      statusCode: {
        200: loadReviews,
      },
    });
  });

  $("#modal .main .review-form").on("submit", function (e) {
    e.preventDefault();
    const id = $("#modal > .header .title")[0].dataset.recipeId;
    const formData = new FormData(this);
    const reqData = Object.fromEntries(formData.entries());
    console.log("HERE")
    console.log("reqData", reqData)
    $.post("api/review.php?id=" + id, reqData).done(function (data, err) {
      if(err){console.log(err)}
      loadReviews(data);
    });
  });
  
  $("main aside ul.selectedList").on("click", function (e) {
    const dataEl = document.getElementById("ingredients");
    dataEl.value = dataEl.value.split(`+${e.target.textContent},`).join("");

    $(e.target).remove();
  });
  $("main aside ul.unselectedList").on("click", function (e) {
    const dataEl = document.getElementById("ingredients");
    dataEl.value = dataEl.value.split(`-${e.target.textContent},`).join("");

    $(e.target).remove();
  });
  $("#ing-to-have").on("focus", function (e) {
    const dataEl = document.getElementById("ingredients");
    const ingredientsList = Array.from(
      $("main aside form .by-ing datalist")[0].options
    ).map((opt) => opt.value);
    $(this).on("keypress", function (e) {
      if (e.key === "Enter") {
        if (
          ingredientsList.includes(this.value) &&
          !dataEl.value.includes(this.value)
        ) {
          dataEl.value += `+${this.value},`;
          $("main aside ul.selectedList").append(`<li>${this.value}</li>`);
        }
        this.value = "";
      }
    });
  });
  $("#ing-to-have").on("blur", function (e) {
    $(this).off("keypress");
  });
  $("#ing-not-to-have").on("focus", function (e) {
    const ingredientsList = Array.from(
      $("main aside form .by-ing datalist")[0].options
    ).map((opt) => opt.value);
    const dataEl = document.getElementById("ingredients");
    $(this).on("keypress", function (e) {
      if (e.key === "Enter") {
        if (
          ingredientsList.includes(this.value) &&
          !dataEl.value.includes(this.value)
        ) {
          dataEl.value += `-${this.value},`;
          $("main aside ul.unselectedList").append(`<li>${this.value}</li>`);
        }

        this.value = "";
      }
    });
  });
  $("#ing-not-to-have").on("blur", function (e) {
    $(this).off("keypress");
  });

  $("main aside form").on("keypress", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      return;
    }
  });
  $("main aside form button[type='reset']").on("click", function (e) {
    const curUrl = window.location.href;
    console.log(curUrl);
    const newUrl = curUrl.split("?")[0];
    location.assign(newUrl);
  });

  $("main aside form .by-cal input").on("input", function (e) {
    const inputs = this.closest("div").querySelectorAll("input");
    let larger, smaller;
    if (+inputs[0].value >= +inputs[1].value) {
      larger = inputs[0];
      smaller = inputs[1];
    } else {
      larger = inputs[1];
      smaller = inputs[0];
    }
    larger.setAttribute("name", "maxCal");
    smaller.setAttribute("name", "minCal");
    $("main aside form .by-cal span.to").html(larger.value + " Cal");
    $("main aside form .by-cal span.from").html(smaller.value + " Cal");
  });
  const starGroupChangeHandler = function (e) {
    const curRateValue = e.target.value;
    Array.from(
      this.closest("div").querySelectorAll("label > ion-icon")
    ).forEach((el) => {
      if (curRateValue >= el.dataset.starVal) {
        el.setAttribute("name", "star");
      } else {
        el.setAttribute("name", "star-outline");
      }
    });
  };
  $("main > aside form .by-rating input").on("change", starGroupChangeHandler);
  if ($("main > aside form .by-rating").data("previousRate")) {
    const curRateValue = $("main > aside form .by-rating").data("previousRate");
    delete $("main > aside form .by-rating")[0].dataset.previousRate;
    Array.from($("main > aside form .by-rating label > ion-icon")).forEach(
      (el) => {
        if (curRateValue >= el.dataset.starVal) {
          el.setAttribute("name", "star");
        } else {
          el.setAttribute("name", "star-outline");
        }
      }
    );
  }
  $("#modal > .main form.review-form .star-group input").on(
    "change",
    starGroupChangeHandler
  );

  $("main > aside form .by-cook-time input").on("input", function (e) {
    $(this).parent().find("label > span").html(`${e.target.value} Minutes`);
  });
});
