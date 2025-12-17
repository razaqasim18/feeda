<template>
    <div class="main-container pt-8 pb-12">
        <div v-if="!isLoading" class="text-slate-800 text-lg lg:text-3xl font-bold">
            {{ $t(seasonDiscount) }}
        </div>
        <SkeletonLoader v-else class="w-48 sm:w-60 md:w-72 lg:w-96 h-12 rounded-lg" />

        <!-- Products -->
         <!-- grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-6 items-start mt-6 -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 items-stretch mt-6">
            <div v-for="product in products" :key="product.id" class="w-full">
                <ProductCard :product="product" />
            </div>
            
            <!-- Loading more indicator -->
            <div v-if="isLoadingMore" class="col-span-full flex justify-center py-4">
                  <SkeletonLoader class="w-full h-[220px] sm:h-[330px]" />
            </div>
            
            <!-- End of results message -->
            <div v-if="hasReachedEnd && products.length > 0" class="col-span-full text-center py-4 text-slate-800">
                <!-- {{ $t('You have reached the end') }} -->
            </div>
            
            <!-- No products found message -->
            <div v-if="products.length === 0 && !isLoading" class="col-span-full text-center py-4 text-slate-800">
                {{ $t('No Products Found') }}
            </div>
        </div>

        <!-- Initial loading skeleton -->
        <div v-if="isLoading" class="mt-4 md:mt-8 grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3 md:gap-6 items-start">
            <div v-for="i in 6" :key="i">
                <SkeletonLoader class="w-full h-[220px] sm:h-[330px]" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useMaster } from '../stores/MasterStore';
import ProductCard from '../components/ProductCard.vue';
import SkeletonLoader from '../components/SkeletonLoader.vue';
import { useAuth } from '../stores/AuthStore';

const authStore = useAuth();
const master = useMaster();
   const seasonDiscount = master.seasonDiscount;
// State variables
const currentPage = ref(1);
const perPage = ref(12);
const products = ref([]);
const isLoading = ref(true);
const isLoadingMore = ref(false);
const hasReachedEnd = ref(false);

// Fetch initial products
onMounted(() => {
    fetchProducts();
    window.addEventListener('scroll', handleScroll);
});

// Clean up scroll listener
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

// Scroll handler with debounce
let isScrolling = false;
const handleScroll = () => {
    if (isScrolling || isLoadingMore.value || hasReachedEnd.value) return;
    
    const scrollPosition = window.innerHeight + window.scrollY;
    const documentHeight = document.documentElement.offsetHeight;
    const threshold = 200; // pixels from bottom
    
    if (scrollPosition > documentHeight - threshold) {
        isScrolling = true;
        loadMoreProducts();
        setTimeout(() => { isScrolling = false }, 500); // simple debounce
    }
};

// Load next page of products
const loadMoreProducts = () => {
    currentPage.value += 1;
    fetchProducts(true); // Pass true to indicate it's a "load more" request
};

// Main fetch function
const fetchProducts = async (loadMore = false) => {
    try {
        // Set correct loading state
        if (loadMore) {
            isLoadingMore.value = true;
        } else {
            isLoading.value = true;
        }
        
        const response = await axios.get('/discount-products', {
            params: {
                page: currentPage.value,
                per_page: perPage.value,
                sort_type: 'popular_product'
            },
            headers: {
                'Accept-Language': master.locale || 'en',
                Authorization: authStore.token
            }
        });

        // Append new products for infinite scroll
        if (loadMore) {
            products.value = [...products.value, ...response.data.data.products];
        } else {
            products.value = response.data.data.products;
        }

        // Check if we've reached the end
        if (response.data.data.products.length < perPage.value) {
            hasReachedEnd.value = true;
        }
        
    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        isLoading.value = false;
        isLoadingMore.value = false;
    }
};
</script>