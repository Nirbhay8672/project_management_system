import Swal from "sweetalert2";

function toastAlert(config) {
    let swalConfig = {
        title: config.message || "Are you sure?",
        icon: "success",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    };

    return Swal.fire({
        ...swalConfig,
        ...config,
    });
}

function confirmAlert(config) {
    let swalConfig = {
        title: "Are you sure?",
        html: config.message || "You won't be able to revert this!",
        icon: "warning",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonColor: "#d33",
        // didRender: () => Swal.getConfirmButton().focus(),
    };

    return Swal.fire({
        ...swalConfig,
        ...config,
    });
}

function alert(config) {
    let swalConfig = {
        position: "top-end",
        icon: "success",
        title: "Your work has been saved",
        html: "Successful.",
        showConfirmButton: false,
        timer: 2000,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    };

    return Swal.fire({
        ...swalConfig,
        ...config,
    });
}

function buttonAlert(config) {
    let swalConfig = {
        title: "Do you want to save the changes?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Save",
        denyButtonText: `Don't save`,
    };

    return Swal.fire({
        ...swalConfig,
        ...config,
    });
}

function normalAlert(config) {
    let swalConfig = {
        title: "Success!",
        text: "Successfully.",
        icon: "success",
    };

    return Swal.fire({
        ...swalConfig,
        ...config,
    });
}

export { toastAlert, confirmAlert, alert, buttonAlert, normalAlert };
