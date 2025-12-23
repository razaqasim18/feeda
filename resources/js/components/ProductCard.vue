<template>
  <div
  class="bg-white shadow-md overflow-hidden group relative w-[100%] transition-transform duration-500 hover:scale-105 mt-3"
>
    <!-- SALE / SOLD OUT / DISCOUNT Tags -->
<div class="absolute top-3 right-3 flex flex-col gap-1 pointer-events-none z-[5]">
  <span
    v-if="props.product?.discount_percentage > 0"
    class="bg-primary-500 text-white text-xs px-2 py-1 font-semibold uppercase"
  >
    {{ props.product?.discount_percentage }}% OFF
  </span>

  <span
    v-if="props.product?.quantity === 0"
    class="bg-gray-500 text-white text-xs px-2 py-1 font-semibold uppercase"
  >
    Sold Out
  </span>
</div>


    <!-- FAVORITE ICON -->
<button
  v-if="props.product?.is_favorite"
  class="absolute top-3 left-3 w-9 h-9 flex justify-center items-center bg-white cursor-pointer shadow z-[5]"
  @click="favoriteAddOrRemove"
>
  <HeartIcon class="w-6 h-6 text-primary-500" />
</button>

<button
  v-else
  class="absolute top-3 left-3 w-9 h-9 flex justify-center items-center bg-white cursor-pointer transition-all duration-300 group-hover:opacity-100 shadow z-[5]"
  @click="favoriteAddOrRemove"
>
  <HeartIconOutline class="w-6 h-6 text-slate-600" />
</button>


    <!-- PRODUCT IMAGE -->
    <!-- <div
      class="flex items-center justify-center bg-white cursor-pointer h-72 sm:h[50px] relative overflow-hidden w-full"
      @click="showProductDetails"
      :class="props.product?.quantity > 0 ? '' : 'opacity-30'"
    >
      <img
        :src="props.product?.thumbnail"
        alt="Product"
        class="object-contain w-full h-full transition-transform duration-500 group-hover:scale-110"
        loading="lazy"
      />
    </div> -->

    <div
      class="flex items-center justify-center bg-white cursor-pointer h-48 sm:h-55 md:h-72 relative overflow-hidden w-full"
      @click="showProductDetails"
      :class="props.product?.quantity > 0 ? '' : 'opacity-30'"
    >
      <img
        :src="props.product?.thumbnail"
        alt="Product"
        class="object-contain w-full h-full transition-transform duration-500 group-hover:scale-110"
        loading="lazy"
      />
    </div>

    <!-- PRODUCT INFO -->
    <div class="p-3 text-center">
      <h3
        class="text-gray-900 font-semibold text-base truncate"
        :class="props.product?.quantity > 0 ? '' : 'opacity-30'"
      >
        {{ props.product?.name }}
      </h3>

      <!-- PRICE -->
      <div class="flex items-center justify-center gap-2 mt-2">
        <span class="text-primary-500 font-bold text-base">
          {{
            masterStore.showCurrency(
              props.product?.discount_price > 0
                ? props.product?.discount_price
                : props.product?.price
            )
          }}
        </span>
        <span
          v-if="props.product?.discount_price > 0"
          class="text-gray-400 line-through text-sm"
        >
          {{ masterStore.showCurrency(props.product?.price) }}
        </span>
      </div>

      <!-- ACTION BUTTON -->
      <div class="mt-3 w-full">
        <div v-if="props.product?.quantity > 0" class="flex items-center gap-3">
          <button
  class="w-full py-2 border border-primary text-primary font-medium bg-white transition-all duration-300 
         shadow-[3px_3px_0px_0px_rgb(254,229,232)] 
         group-hover:bg-primary-600 group-hover:text-white 
         group-hover:shadow-[3px_3px_0px_0px_rgb(254,229,232)]"
  @click="addToBasket(props.product)"
>
            Add to Cart
          </button>
        </div>

        <!-- Out of Stock -->
        <button
          v-else
          disabled
          class="w-full py-2 border border-primary-300 text-primary-300 font-medium bg-white cursor-not-allowed"
        >
           Add to Cart
        </button>
      </div>
    </div>
  </div>
</template>


<script setup>
import { HeartIcon as HeartIconOutline } from '@heroicons/vue/24/outline';
import { HeartIcon, StarIcon } from '@heroicons/vue/24/solid';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import BagIcon from '../icons/Bag.vue';
import { useAuth } from '../stores/AuthStore';
import { useBasketStore } from '../stores/BasketStore';
import { useMaster } from '../stores/MasterStore';

const router = useRouter();

const masterStore = useMaster();

const basketStore = useBasketStore();
const authStore = useAuth();

const toast = useToast();

const props = defineProps({
    product: Object
});

const orderData = {
    is_buy_now: false,
    product_id: props.product?.id,
    quantity: 1,
    size: null,
    color: null,
    unit: null
};

const addToBasket = (product) => {
    // add product to basket
    basketStore.addToCart(orderData, product);
};

const buyNow = async () => {
    if (authStore.token === null) {
        return authStore.loginModal = true;
    }

  await basketStore.addToCart({
        product_id: props.product?.id,
        is_buy_now: true,
        quantity: 1,
        size: null,
        color: null,
        unit: null
    }, props.product);

    basketStore.buyNowShopId = props.product?.shop.id;
    router.push({ name: 'buynow' })
};

const isFavorite = ref(props.product?.is_favorite);

const favoriteAddOrRemove = () => {
    if (authStore.token === null) {
        return authStore.loginModal = true;
    }
    axios.post('/favorite-add-or-remove', {
        product_id: props.product.id
    }, {
        headers: {
            Authorization: authStore.token
        }
    }).then((response) => {
        props.product.is_favorite = !props.product.is_favorite
        isFavorite.value = response.data.data.product.is_favorite
        if (isFavorite.value === false) {
            toast.warning('Product removed from favorite', {
               position: masterStore.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
            });
        } else {
            toast.success('Product added to favorite', {
               position: masterStore.langDirection === 'rtl' ? "bottom-right" : "bottom-left",
            });
        }
        authStore.favoriteRemove = true
        authStore.fetchFavoriteProducts();
    });
}

const showProductDetails = () => {
    // if (props.product.quantity > 0) {
        router.push({ name: 'productDetails', params: { id: props.product.id } })
    // }
}

</script>