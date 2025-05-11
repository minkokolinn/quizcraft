<template>
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <Link class="nav-link" href="/">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </Link>
            </li>
            <li class="nav-heading">Management</li>
            <li class="nav-item">
                <Link class="nav-link collapsed" href="/type">
                    <i class="bi bi-list-columns"></i>
                    <span>Type</span>
                </Link>
            </li>
            <li class="nav-item">
                <Link
                    class="nav-link collapsed"
                    data-bs-target="#components-nav"
                    data-bs-toggle="collapse"
                    href="#"
                >
                    <i class="bi bi-question-octagon-fill"></i><span>Question</span
                    ><i class="bi bi-chevron-down ms-auto"></i>
                </Link>
                <ul
                    id="components-nav"
                    class="nav-content collapse"
                    data-bs-parent="#sidebar-nav"
                >
                    <li>
                        <Link href="/question">
                            <i class="bi bi-circle"></i><span>ALL</span>
                        </Link>
                    </li>
                    <li v-for="type in $page.props.types" :key="type.id">
                        <Link href="/question" :data="{type: type.id}">
                            <i class="bi bi-circle"></i
                            ><span>{{ type.name }}</span>
                        </Link>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link collapsed"
                    data-bs-target="#components-nav2"
                    data-bs-toggle="collapse"
                    href="#"
                >
                    <i class="bi bi-envelope-paper-fill"></i><span>Paper</span
                    ><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul
                    id="components-nav2"
                    class="nav-content collapse"
                    data-bs-parent="#sidebar-nav"
                >
                    <li>
                        <Link href="/paper/create/step/1">
                            <i class="bi bi-circle"></i><span>New paper</span>
                        </Link>
                    </li>
                    <!-- <li>
                        <a href="/quiz">
                            <i class="bi bi-circle"></i><span>Open paper</span>
                        </a>
                    </li> -->
                </ul>
            </li>
            <li class="nav-heading">Utility</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="/setting">
                    <i class="bi bi-gear-fill"></i>
                    <span>Setting</span>
                </a>
            </li>
        </ul>
    </aside>
</template>