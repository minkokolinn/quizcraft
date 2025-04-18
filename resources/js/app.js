import 'bootstrap';

import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";

createInertiaApp({
    title:(title)=>`QuizCraft ${title}`,
    resolve: name=>{
        const pages = import.meta.glob('./Pages/**/*.vue',{eager:true});
        let page = pages[`./Pages/${name}.vue`];
        return page;
    },
    setup({el,App,props,plugin}){
        createApp({render:()=>h(App,props)})
        .use(plugin)
        .component("Head",Head)
        .component("Link",Link)
        .mount(el)
    }
});