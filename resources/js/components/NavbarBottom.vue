<template>
  <div class="main-container hidden md:flex bg-primary-700 text-white border-b border-primary-800 flex-wrap justify-center items-center px-2 py-2 gap-x-4 gap-y-2 relative">

    <!-- Categories -->
    <Popover v-slot="{ open }" class="relative">
      <PopoverButton class="nav-link" :class="open ? 'text-white' : ''">
        {{ $t('Categories') }}
      </PopoverButton>

      <transition enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
        <PopoverPanel
          class="fixed left-1/2 top-[200px] -translate-x-1/2 z-50 bg-white shadow-md w-[90vw] max-w-7xl rounded-b-xl">
          <div
            class="w-full p-6 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 xl:grid-cols-10 gap-4 overflow-y-auto max-h-[500px]">
            <div v-for="category in master.categories" :key="category.id">
              <MenuCategory :category="category" @update:click="hiddenPopover" />
            </div>
          </div>
        </PopoverPanel>
      </transition>
    </Popover>

    <!-- Brands -->
    <Popover v-slot="{ open }" class="relative">
      <PopoverButton class="nav-link" :class="open ? 'text-white' : ''">
        {{ $t('Brands') }}
      </PopoverButton>

      <transition enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
        <PopoverPanel
          class="fixed left-1/2 top-[200px] -translate-x-1/2 z-50 bg-white shadow-md w-[90vw] max-w-7xl rounded-b-xl">
          <div
            class="w-full p-6 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 xl:grid-cols-10 gap-4 overflow-y-auto max-h-[500px]">
            <div v-for="brand in master.brands" :key="brand.id">
              <MenuBrand :brand="brand" @update:click="hiddenPopover" />
            </div>
          </div>
        </PopoverPanel>
      </transition>
    </Popover>

    <!-- Regular Links -->
    <router-link to="/" class="nav-link">{{ $t('Home') }}</router-link>
    <router-link to="/products" class="nav-link">{{ $t('Products') }}</router-link>
    <router-link v-if="master.getMultiVendor" to="/shops" class="nav-link">
      {{ $t('Shops') }}
    </router-link>
    <router-link to="/most-popular" class="nav-link">{{ $t('Most Popular') }}</router-link>
    <router-link to="/best-deal" class="nav-link">{{ $t('Best Deal') }}</router-link>
    <router-link to="/contact-us" class="nav-link">{{ $t('Contact') }}</router-link>
    <router-link to="/blogs" class="nav-link">{{ $t('Blogs') }}</router-link>

    <router-link v-if="seasonDiscount" to="/discount-products" class="nav-link">
      {{ $t(seasonDiscount) }}
    </router-link>
    
    <!-- Download Our App -->
    <div v-if="master.showDownloadApp" class="flex items-center">
      <Menu as="div" class="relative text-left" v-slot="{ open }">
        <MenuButton class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all"
          :class="open ? 'bg-gray-900 text-white' : 'text-gray-200 hover:bg-gray-800'">
          <DevicePhoneMobileIcon class="w-4 h-5" />
          <span class="text-sm font-normal">{{ $t('Download our app') }}</span>
          <ChevronDownIcon class="w-4 h-4 transition" :class="open ? 'rotate-180' : ''" />
        </MenuButton>

        <transition enter-active-class="transition ease-out duration-100"
          enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
          leave-active-class="transition ease-in duration-75"
          leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
          <MenuItems
            class="absolute right-0 z-10 mt-0 w-full origin-top-right p-3 bg-white rounded-xl shadow border border-primary-300 ring-1 ring-primary ring-opacity-5 focus:outline-none">
            <div class="flex flex-col gap-2">
              <MenuItem v-slot="{ active }">
                <button :class="active ? 'bg-gray-100 text-gray-900' : 'text-gray-700'" @click="playStore">
                  <img :src="'/assets/icons/playStore.png'" alt="Play Store" />
                </button>
              </MenuItem>
              <MenuItem v-slot="{ active }">
                <button :class="active ? 'bg-gray-100 text-gray-900' : 'text-gray-700'" @click="appStore">
                  <img :src="'/assets/icons/appleStore.png'" alt="App Store" />
                </button>
              </MenuItem>
            </div>
          </MenuItems>
        </transition>
      </Menu>
    </div>
  </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { DevicePhoneMobileIcon, ChevronDownIcon } from '@heroicons/vue/24/outline'
import MenuCategory from './MenuCategory.vue'
import MenuBrand from './MenuBrand.vue'
import { useMaster } from '../stores/MasterStore'

const master = useMaster()
const seasonDiscount = master.seasonDiscount;
const appStore = () => {
  if (master.appStoreLink) window.open(master.appStoreLink, '_blank')
}
const playStore = () => {
  if (master.playStoreLink) window.open(master.playStoreLink, '_blank')
}
const hiddenPopover = () => {
  open = false
}
</script>

<style scoped>
.nav-link {
  @apply px-3 py-2 text-sm md:text-base font-medium text-white hover:text-white hover:underline hover:underline-offset-4 transition-colors duration-150;
}
.router-link-active {
 @apply px-3 py-2 text-sm md:text-base font-medium text-white  underline underline-offset-4 ;
}

/* Small fade transition for smooth wrapping */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
