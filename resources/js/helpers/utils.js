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

export { resetObjectKeys };
