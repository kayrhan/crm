function removeReference(parent, child) {
    confirmModal("Are you sure you want to remove this reference?", "Remove Reference", "Remove", "Close", "#0275d8", "#d9534f").then(function() {
        toggleLoader(true);
        $.ajax({
            url:"/tickets/reference/remove",
            type: "POST",
            data: {
                parent: parent,
                child: child,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.status === "Success") {
                    location.reload();
                    toastr.success("Reference deleted successfully!", "Success");
                }
                else {
                    toggleLoader(false);
                    toastr.error("Something went wrong!", "Error");
                }
            }
        });
    });
}

function addReferenceTicket(ID) {
    window.open("/add-ticket/" + ID, "_blank");
}

function copyTicket(ID) {
    window.open("/add-ticket/" + ID + "/copy", "_blank");
}