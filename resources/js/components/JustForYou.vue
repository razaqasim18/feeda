<template>
    <div class="main-container bg-white py-12">
        <!-- Section Title or Skeleton -->
        <div v-if="!isLoading" class="text-slate-800 text-lg md:text-3xl font-bold leading-9">
            {{ $t('Just For You') }}
        </div>
        <SkeletonLoader v-else class="w-48 sm:w-60 md:w-72 lg:w-96 h-12 rounded-lg" />

        <!-- Product Grid -->
        <div v-if="!isLoading && products"
            class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 items-stretch">
            <div v-for="product in products" :key="product.id" class="w-full">
                <ProductCard :product="product" />
            </div>
        </div>

        <!-- Initial Skeleton Grid -->
        <div v-if="isLoading"
            class="mt-4 md:mt-8 grid grid-cols-2 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3 md:gap-6 items-start">
            <div v-for="i in 6" :key="i">
                <SkeletonLoader class="w-full h-[220px] sm:h-[330px]" />
            </div>
        </div>

        <!-- Infinite Scroll Loading Spinner -->
        <div v-if="loadMore" class="mt-6 flex justify-center w-full">
            <SkeletonLoader class="w-full h-[220px] sm:h-[330px]" />
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import ProductCard from './ProductCard.vue';
import SkeletonLoader from './SkeletonLoader.vue';
import { useAuth } from '../stores/AuthStore';
import axios from 'axios';

const authStore = useAuth();

const props = defineProps({
    justForYou: Object,
    isLoading: Boolean
});

const currentPage = ref(2);
const totalPages = ref(1);
const hasMoreProducts = ref(false);
const loadMore = ref(false);
const products = ref([]);

// Watch for prop change and initialize product data
watch(() => props.justForYou, () => {
    products.value = props.justForYou?.products ?? [];
    totalPages.value = Math.ceil(props.justForYou?.total / 12);
    hasMoreProducts.value = totalPages.value > 1;
});

// Load more products from API
const loadMoreProducts = () => {
    if (loadMore.value || !hasMoreProducts.value) return;

    loadMore.value = true;

    axios.get(`/home?page=${currentPage.value}&per_page=12`, {
        headers: {
            Authorization: authStore.token
        }
    }).then((response) => {
        const newProducts = response.data.data.just_for_you.products;
        products.value = products.value.concat(newProducts);
        currentPage.value++;

        if (currentPage.value > totalPages.value || newProducts.length === 0) {
            hasMoreProducts.value = false;
        }

        loadMore.value = false;
    }).catch((error) => {
        console.error(error);
        loadMore.value = false;
    });
};

// Infinite scroll trigger
const handleScroll = () => {
    const scrollTop = window.scrollY;
    const windowHeight = window.innerHeight;
    const fullHeight = document.documentElement.scrollHeight;

    // If near the bottom, trigger load
    if (scrollTop + windowHeight >= fullHeight - 200) {
        loadMoreProducts();
    }
};

// Setup and teardown scroll listener
onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>
