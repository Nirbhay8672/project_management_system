import { createApp, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";

import MainLayout from "./layout/MainLayout.vue";
import MainPage from "./layout/Page.vue";

import "animate.css";

createInertiaApp({
    id: "app",
    title: (title) => (title ? `${title} | PMS` : "PMS"),
    resolve: (name) => {
        const pages = import.meta.glob("./pages/**/*.vue", { eager: true });
        let page = pages[`./pages/${name}.vue`];

        page.default.layout = page.default.layout || MainLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("InertiaLink", Link)
            .component("InertiaHead", Head)
            .component("MainPage", MainPage)
            .mount(el);
    },
});
