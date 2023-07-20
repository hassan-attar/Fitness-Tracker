$(document).ready(function() {
    $("#addBtn").on("click", function() {
        let toDoText = $("#formInput").val();
        $("#formInput").val("");
        addItem(toDoText);
    })

    $("#formInput").keypress(function(event) {
        if(event.keyCode == 13) {
            let toDoText = $(this).val();
            $(this).val("");
            addItem(toDoText);
        }
    });

    function addItem(toDoText) {
        $("ul").append(`<li>${toDoText}<span><i class="fas fa-trash"></i></li>`);
    }

    $("body").on("click", "li", function() {
        $(this).toggleClass("done");
    });

    $("body").on("click", ".fa-trash", function() {
        $(this).parent().parent().fadeOut(500, function() {
            $(this).remove();
        });
    })
});