<template>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <Link href="/" class="logo d-flex align-items-center me-2">
                <img :src="'/images/icon.png'" alt="" />
                <span class="d-none d-lg-block">QuizCraft</span>
            </Link>
            <span class="badge text-bg-primary">{{ $page.props.gradePortal }}</span>
            <i class="bi bi-list toggle-sidebar-btn mx-3" @click="toggleSidebar"></i>
        </div>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item pe-3">
                    <Link
                        href="/profile"
                        class="nav-link nav-profile d-flex align-items-center pe-0"
                    >
                        <span class="d-none d-md-block ps-2"
                            >Dr. Thiha (Biology)</span
                        >
                    </Link>
                </li>
            </ul>
        </nav>
    </header>
</template>
<script setup>
const toggleSidebar = () => {
    document.body.classList.toggle("toggle-sidebar");
};
</script>
