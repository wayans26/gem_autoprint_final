<template>
    <nav class="breadcrumb-wrapper" v-if="globalBreadcrumb !== ''" aria-label="Breadcrumb">
        <ol class="breadcrumb admin-breadcrumb">
            <li class="breadcrumb-item">
                <a href="javaScript:void(0);">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span>{{ globalBreadcrumb.menu_group }}</span>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ globalBreadcrumb.menu_sub_group }}</li>
        </ol>
    </nav>
</template>
