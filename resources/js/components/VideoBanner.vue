<template>
    <div class="relative w-full overflow-hidden">
        <!-- Video Background -->
        <video autoplay muted loop playsinline class="w-full h-full  aspect-[16/9]" :src="videoUrl"></video>

        <!-- Overlay (optional for darkening effect) -->
        <div class="absolute inset-0 bg-black/30"></div>
        <!-- Centered Button -->
        <div class="absolute inset-0 flex items-center justify-center -translate-y-[30px]">
           <button
          class="px-4 py-2 text-sm border 
          border-primary-600 hover:bg-primary-700
          text-white font-semibold rounded-full 
          shadow-lg transition duration-300 lg:px-10 
          lg:py-3 lg:text-lg"
          @click="goToShop"
        >
          {{ $t('Shop Now') }}
        </button>
        </div>
    </div>
</template>

<script setup>
    import {
        useRouter
    } from 'vue-router'

    const router = useRouter()

    const props = defineProps({
        videoUrl: {
            type: String,
            required: true,
        },
        videoslink: {
            type: String,
            required: true,
        },
    })
    console.log(props.videoslink);
    const goToShop = () => {
        if (props.videoslink.startsWith('http')) {
            window.location.href = props.videoslink
        } else {
            router.push(props.videoslink)
        }
    }
</script>

<style scoped>
    video::-webkit-media-controls {
        display: none !important;
    }

    video {
        pointer-events: none;
    }
</style>
