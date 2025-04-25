<template>
    <Navbar></Navbar>
    <SideBarExtract></SideBarExtract>

    <main id="main" class="main bg-color1">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <Footer></Footer>
</template>
<script setup>
import Navbar from "../Pages/Components/Navbar.vue";
import SideBarExtract from "../Pages/Components/SideBarExtract.vue";
import Footer from "../Pages/Components/Footer.vue";
</script>
