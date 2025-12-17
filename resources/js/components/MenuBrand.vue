<template>
  <div class="group relative">
    <button
      type="button"
      class="p-4 group bg-white rounded-xl border justify-start items-center gap-2.5 inline-flex flex-col hover:border-primary transition-all duration-300 w-full border-b-2"
      :class="(props.brand?.id == route.params?.brandId) ? 'border-primary' : 'border-slate-100'"
      @click="onClick"
    >
      <img :src="props.brand?.thumbnail"
                class="w-16 h-16 object-cover transition duration-500 group-hover:scale-110 rounded-lg" loading="lazy" />
          
      <div class="text-slate-600 text-sm font-medium leading-tight flex gap-1 items-center">
        {{ props.brand?.name }}
      </div>
    </button>
  </div>
</template>

<script setup>
import { useRouter, useRoute } from 'vue-router';

const props = defineProps({
  brand: Object
});

const emit = defineEmits(['update:click']);

const router = useRouter();
const route = useRoute();

const onClick = () => {
  emit('update:click', true);
  router.push('/brands/' + props.brand?.id);
};
</script>
