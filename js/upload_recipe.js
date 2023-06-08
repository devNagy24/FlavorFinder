$(document).ready(function() {
    $('#uploadRecipeForm').on('submit', function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "/uploadRecipe",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // handle your response here
                console.log(response);
                $('#recipeModal').modal('hide');
            },
            error: function(jqXHR, textStatus, errorMessage) {
                // handle error
                console.log(errorMessage);
            }
        });
    });
});
