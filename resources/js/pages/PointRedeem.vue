<template>
    <div class="flex flex-col h-full">
        <AuthPageHeader title="Point Redeem" />

        <div class="p-3 md:p-4 xl:p-6 h-full flex flex-col gap-4">

            <!-- Redeem options -->
            <div class="space-y-4">
                <div v-for="pointreedem in authStore.pointreedems" :key="pointreedem.id"
                    class="p-4 flex rounded-lg border bg-white relative">

                    <div class="w-full lg:w-[20%] flex flex-col overflow-hidden">
                        <div class="flex gap-1 justify-between">
                            <div class="text-sm sm:text-base font-normal shrink-0">
                                <span class="text-slate-500">
                                    {{ $t('At Point') }}:
                                </span>
                                <span class="text-blue-500">
                                    {{ pointreedem.points }}
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="w-full lg:w-[70%] flex flex-col overflow-hidden">
                        <div class="flex gap-1 justify-between">
                            <div class="text-sm sm:text-base font-normal shrink-0">
                                <span class="text-slate-500">
                                    {{ $t('You will Receive Amount Discount') }}:
                                </span>
                                <span class="text-blue-500">
                                    {{ master.showCurrency(pointreedem.amount) }}
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="w-full lg:w-[20%] flex flex-col overflow-hidden">
                        <div v-if="pointreedem.points <= authStore.userpoint" class="flex gap-1 justify-between">
                            <div class="text-sm sm:text-base font-normal shrink-0">
                                <span class="text-blue-500">
                                    <button class="absolute top-3 right-3 px-3 py-1 border border-primary rounded
                       transition-all
                       disabled:opacity-50 disabled:cursor-not-allowed
                       disabled:hover:bg-primary disabled:hover:text-white" :class="redeemLoading === pointreedem.id
                        ? 'bg-primary text-white'
                        : 'text-primary hover:bg-primary hover:text-white'"
                                        :disabled="redeemLoading === pointreedem.id"
                                        @click="redeemPoints(pointreedem.id)">

                                        <!-- Spinner -->
                                        <span v-if="redeemLoading === pointreedem.id" class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                                </path>
                                            </svg>
                                            {{ $t('Processing') }}
                                        </span>

                                        <!-- Normal text -->
                                        <span v-else>
                                            {{ $t('Redeem Coupon') }}
                                        </span>

                                    </button>
                                </span>
                            </div>
                        </div>

                    </div>



                </div>

                <div v-if="authStore.pointreedems.length === 0">
                    {{ $t('No Package available') }}
                </div>
            </div>

            <!-- Coupons list -->
            <div class="bg-white p-4 rounded-xl">
                <div
                    class="py-3 px-2 text-slate-800 text-lg md:text-2xl font-medium font-['Roboto'] tracking-tight md:leading-loose bg-white">
                    List Of Coupon
                </div>
                <div v-for="coupon in authStore.coupons" :key="coupon.id">
                    <PointCouponItem :coupon="coupon" />
                </div>

                <div v-if="authStore.coupons.length === 0">
                    {{ $t('No Coupons Found') }}
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="totalItems > perPage" class="bg-white p-3 rounded-xl flex justify-between items-center">
                <div>
                    {{ $t('Showing') }}
                    {{ startItem }}
                    {{ $t('to') }}
                    {{ endItem }}
                    {{ $t('of') }}
                    {{ totalItems }}
                </div>

                <vue-awesome-paginate :total-items="totalItems" :items-per-page="perPage" v-model="currentPage"
                    :max-pages-shown="3" :hide-prev-next-when-ends="true" @update:modelValue="onPageChange" />
            </div>

        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { useAuth } from '../stores/AuthStore';
import { useMaster } from '../stores/MasterStore';
import AuthPageHeader from '../components/AuthPageHeader.vue';
import PointCouponItem from '../components/PointCouponItem.vue';

const authStore = useAuth();
const master = useMaster();

const currentPage = ref(1);
const perPage = ref(10);
const redeemLoading = ref(null);

const totalItems = computed(() => authStore.totalItems);

const startItem = computed(() =>
    perPage.value * (currentPage.value - 1) + 1
);

const endItem = computed(() =>
    Math.min(currentPage.value * perPage.value, totalItems.value)
);

onMounted(() => {
    authStore.fetchPointRedeemlist();
    authStore.fetchPointRedeemcouponList(
        currentPage.value,
        perPage.value
    );
});

const onPageChange = (page) => {
    currentPage.value = page;
    authStore.fetchPointRedeemcouponList(page, perPage.value);
};

const fetchCoupons = () => {
    authStore.fetchPointRedeemcouponList(currentPage.value, perPage.value);
};

const redeemPoints = async (id) => {
    try {
        redeemLoading.value = id;

        await authStore.redeemPoints(id);

        // refresh both lists correctly
        await authStore.fetchPointRedeemlist();
        await authStore.fetchPointRedeemcouponList(
            currentPage.value,
            perPage.value
        );

    } catch (error) {
        console.error(error);
    } finally {
        redeemLoading.value = null;
    }
};

</script>
