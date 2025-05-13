<template>
    <Navbar></Navbar>
    <SideBarExtract></SideBarExtract>
    
    <main id="main" class="main bg-color1">
        <!-- Where dynamic UI enter on the layout -->
        <slot />

    </main>
    <Footer></Footer>
</template>
<script setup>
import Navbar from "../Pages/Components/Navbar.vue";
import SideBarExtract from "../Pages/Components/SideBarExtract.vue";
import Footer from "../Pages/Components/Footer.vue";



</script>
