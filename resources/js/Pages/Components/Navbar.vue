<template>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <Link href="/" class="logo d-flex align-items-center me-5">
                <img :src="'/images/icon.png'" alt="" />
                <span class="d-none d-lg-block">QuizCraft</span>
            </Link>
            <i class="bi bi-list toggle-sidebar-btn" @click="toggleSidebar"></i>
        </div>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item pe-3">
                    <a
                        class="nav-link nav-profile d-flex align-items-center pe-0"
                        href="#"
                    >
                        <span class="d-none d-md-block ps-2"
                            >Dr. Thiha (Biology)</span
                        >
                    </a>
                </li>
            </ul>
        </nav>
    </header>
</template>
<script setup>
const toggleSidebar = ()=>{
    document.body.classList.toggle("toggle-sidebar");
}
</script>