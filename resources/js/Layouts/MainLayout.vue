<template>
    <Navbar></Navbar>
    <SideBarExtract></SideBarExtract>
    
    <main id="main" class="main bg-color1">
        <div class="d-flex justify-content-between main-header">
            <div class="pagetitle">
                <!-- <h1>{{ $title }}</h1> -->
                 <h1>Title</h1>
                <nav>
                    <ol class="breadcrumb">
                        <!-- @foreach ($breadcrumb as $item)
                        <li class="breadcrumb-item active">{{ $item }}</li>
                        @endforeach -->
                        <li class="breadcrumb-item active">ItemBreadcrumb</li>
                    </ol>
                </nav>
            </div>
            <p id="clocktxt"></p>
        </div>

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
