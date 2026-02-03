<template>
    <div
        class="p-2 md:px-4 md:py-3 bg-white rounded-lg border border-slate-100 flex gap-2 lg:gap-4 flex-col lg:flex-row items-center justify-between">

        <div class="w-full lg:w-[50%] flex flex-col overflow-hidden">
            <div class="flex gap-1 justify-between">
                <div class="text-sm sm:text-base font-normal shrink-0">
                    <span class="text-slate-500">
                        {{ $t('Coupon Code') }}:
                    </span>
                    <span class="text-blue-500">
                        {{ coupon.code }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grow flex w-full gap-2 items-center justify-between">
            <div class="text-sm sm:text-base font-normal xl:w-36">
                <span class="text-slate-500">
                    {{ $t('Amount') }}:
                </span>
                <span class="text-slate-950">
                    {{ master.showCurrency(props.coupon?.discount) }}
                </span>
            </div>

            <div class="hidden lg:block xl:w-28">
                <div class="text-sm font-normal px-1.5 py-0.5 rounded-[10px] inline-block" :class="props.coupon.is_used == 1 ||
                    new Date(props.coupon.expired_at) <= new Date()
                    ? 'bg-primary-300 text-white'
                    : 'bg-primary text-white'">
                    {{ props.coupon.is_used == 1 ||
                        new Date(props.coupon.expired_at) <= new Date() ? 'Is Used' : 'Is Valid' }} </div>

                </div>


                <div class="hidden lg:block xl:w-28">
                    <span class="text-slate-500">
                        {{ $t('Validated till') }}:
                    </span>
                    <div class="text-sm font-normal px-1.5 py-0.5 rounded-[10px] inline-block">
                        {{ props.coupon.expired_at.split('T')[0] }}
                    </div>
                </div>


            </div>
        </div>
</template>

<script setup>
const props = defineProps({
    coupon: Object
});

import { useMaster } from "../stores/MasterStore";

const master = useMaster();

</script>

<style scoped>
.Pending {
    @apply bg-yellow-500 text-white;
}

.Confirm {
    @apply bg-blue-500 text-white;
}

.Processing,
.On,
.Pickup {
    @apply bg-primary text-white;
}

.Delivered {
    @apply bg-green-500 text-white;
}

.Cancelled {
    @apply bg-red-500 text-white;
}
</style>
