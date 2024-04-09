const baseUrl = document.querySelector('meta[name="url"]').content;

let userRoutes = {
    updateProfile: (user_id) => {
        return `${baseUrl}/users/update-profile/${user_id}`;
    },
};

export { userRoutes };
