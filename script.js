// $(document).ready(function () {
//   $("#addBtn").on("click", function () {
//     let toDoText = $("#formInput").val();
//     $("#formInput").val("");
//     addItem(toDoText);
//   });

//   $("#formInput").keypress(function (event) {
//     if (event.keyCode == 13) {
//       let toDoText = $(this).val();
//       $(this).val("");
//       addItem(toDoText);
//     }
//   });

//   function addItem(toDoText) {
//     $("ul").append(`<li>${toDoText}<span><i class="fas fa-trash"></i></li>`);
//   }

//   $("body").on("click", "li", function () {
//     $(this).toggleClass("done");
//   });

//   $("body").on("click", ".fa-trash", function () {
//     $(this)
//       .parent()
//       .parent()
//       .fadeOut(500, function () {
//         $(this).remove();
//       });
//   });

//   function closeModal(e) {
//     $("#modal").fadeOut(200);
//     $("#overlay").fadeOut(300);
//   }
//   function openModal(e) {
//     $("#modal").fadeIn(300);
//     $("#overlay").fadeIn(200);
//   }

//   // MODAL
//   //close
//   $("#modal .close-modal").on("click", closeModal);
//   $("#overlay").on("click", closeModal);
//   //open

//   $(".recipe-card .action").on("click", function (e) {
//     openModal();
//     const id = e.target.dataset["recipeId"];
//     $.ajax({
//       url: "get-one-recipe.php?id=" + id,
//       method: "GET",
//       headers: {
//         "Content-Type": "application/json",
//       },
//       statusCode: {
//         200: function (data) {},
//       },
//     });
//   });
// });

// const splitToLi = (instruction) => {
//   return instruction.split(/\d+\./g).filter((el) => el.length > 0);
// };
// /*
// <div class="instruction-content">
//          <h4>Cooking steps:</h4>
//          <ol class="instruction">
//            <li>Season chicken breast with salt and pepper.</li>
//            <li>Grill until cooked through.</li>
//            <li>Slice chicken and arrange on a bed of mixed greens.</li>
//            <li>Add cherry tomatoes and cucumber slices.</li>
//            <li>Drizzle with your favorite vinaigrette dressing.</li>
//          </ol>
//        </div>

//  */
