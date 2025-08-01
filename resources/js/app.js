import './bootstrap';

window.confirmDelete = function (id) {
    Swal.fire({
        title: " Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the delete action here
            document.getElementById('delete-form-' + id).submit();
        } else if (result.isDenied) {
            Swal.fire("Changes are not saved", "", "info");
        }
    });
};