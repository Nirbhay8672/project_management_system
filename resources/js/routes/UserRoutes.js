const baseUrl = document.querySelector('meta[name="url"]').content;

let userRoutes = {
    datatable: `${baseUrl}/users/datatable`,
    updateProfile: (user_id) => {
        return `${baseUrl}/users/update-profile/${user_id}`;
    },
    createOrUpdate: (user_id) => {
        if (user_id > 0) {
            return `${baseUrl}/users/create-or-update/${user_id}`;
        }
        return `${baseUrl}/users/create-or-update`;
    },
    deleteUser: (user_id) => {
        return `${baseUrl}/users/delete/${user_id}`;
    },
};

export { userRoutes };
