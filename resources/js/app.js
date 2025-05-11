import * as bootstrap from 'bootstrap'; // <-- import everything from Bootstrap
window.bootstrap = bootstrap; // <-- expose globally

import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import '../css/ckeditor_style.css';



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