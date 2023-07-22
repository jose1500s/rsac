<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Inicio
            </h2>
        </template>
        <div class="relative h-[85vh] flex flex-col-reverse py-16 lg:pt-0 lg:flex-col lg:pb-0">
            <div
                class="inset-y-0 top-0 right-0 z-0 w-full max-w-xl px-4  md:px-0 lg:pr-0 lg:mb-0 lg:mx-0 lg:w-7/12 lg:max-w-full lg:absolute xl:px-0">
                <svg class="absolute left-0 hidden h-full text-white transform -translate-x-1/2 lg:block"
                    viewBox="0 0 100 100" fill="currentColor" preserveAspectRatio="none slice">
                    <path d="M50 0H100L50 100H0L50 0Z"></path>
                </svg>
                <img class="object-cover w-full h-56 rounded shadow-lg lg:rounded-none lg:shadow-none md:h-96 lg:h-full"
                    src="images/backgroundLoginRegister.jpg"
                    alt="" />
            </div>
            <div class="relative flex flex-col items-start w-full max-w-xl px-4 md:px-0 lg:px-8 lg:max-w-screen-xl">
                <div class="mb-16 lg:my-40 lg:max-w-lg lg:pr-5">
                    <p
                        class="inline-block px-3 py-px mb-4 text-xs font-semibold tracking-wider text-teal-900 uppercase rounded-full bg-teal-accent-400">
                        RSAC
                    </p>
                    <h2 class="mb-5 font-sans text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-none">
                        Bienvenido a el <br class="hidden md:block" />
                        Sistema
                        <span class="inline-block text-deep-purple-accent-400">Reportes SAC</span>
                    </h2>
                    <p class="pr-5 mb-5 text-base text-gray-700 md:text-lg">
                        Aqui podras encontrar todo lo relacionado con los reportes SAC, desde la creacion de reportes, ingresar información y ver graficas de los reportes.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>



