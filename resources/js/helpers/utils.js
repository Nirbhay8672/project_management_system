function resetObjectKeys(input = {}, excludeKeys = []) {
    Object.keys(input).forEach((key) => {
        if (!excludeKeys.includes(key)) {
            if (typeof input[key] === "object") {
                input[key] = {};
            } else {
                input[key] = "";
            }
        }
    });
}

function round(number, decimal = 2) {
    return Math.round(number * Math.pow(10, decimal)) / Math.pow(10, decimal);
}

function getRupees(value) {
    return `₹ ${round(value).toLocaleString("en-IN")}`;
}

function hasPermissions(input_permissions) {
    let count = 0;

    input_permissions.forEach((permission) => {
        if (
            JSON.parse(sessionStorage.getItem("permissions")).includes(
                permission
            )
        ) {
            count++;
        }
    });

    return count > 0 ? true : false;
}

export { resetObjectKeys, hasPermissions, round, getRupees };
