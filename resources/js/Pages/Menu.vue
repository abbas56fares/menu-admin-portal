<template>
  <div
    class="min-h-screen flex flex-col items-center text-white"
    :style="{ backgroundImage: 'transparent', backgroundSize: 'cover', backgroundPosition: 'center', backgroundAttachment: 'fixed' }"
  >
    <!-- Scroll to top button -->
    <div class="fixed bottom-5 right-5 opacity-60 flex justify-end p-4 z-10">
      <button @click="scrollToTop" class="bg-yellow-500 text-black px-4 py-2 rounded hover:bg-yellow-600 transition">
        <span class="font-bold text-lg">↑</span>
      </button>
    </div>

    <!-- Cart toggle -->
    <div class="fixed bottom-5 left-5 z-20">
      <button @click="cartOpen = !cartOpen" class="bg-yellow-500 text-black px-4 py-2 rounded shadow hover:bg-yellow-600 transition">
        Cart ({{ cartCount }})
      </button>
    </div>

    <!-- Cart panel -->
    <div v-if="cartOpen" class="fixed bottom-20 left-5 z-20 w-80 max-h-[70vh] overflow-y-auto bg-black/80 border border-white/20 rounded-lg p-4">
      <h3 class="text-lg font-semibold mb-3">Your Order</h3>

      <div v-if="cartItems.length === 0" class="text-white/70">Cart is empty.</div>

      <div v-else class="space-y-3">
        <div v-for="ci in cartItems" :key="ci.id" class="flex items-center gap-3 border-b border-white/10 pb-2">
          <img :src="getImagePath(ci.image)" :alt="ci.name" class="w-10 h-10 rounded object-cover" />
          <div class="flex-1">
            <div class="text-sm font-semibold">{{ ci.name }}</div>
            <div class="text-xs text-white/70">{{ ci.price }} {{ ci.currency }}</div>
            <div class="flex items-center gap-2 mt-1">
              <button class="px-2 py-0.5 bg-white/10 rounded" @click="updateQty(ci.id, ci.qty - 1)">-</button>
              <span class="text-sm">{{ ci.qty }}</span>
              <button class="px-2 py-0.5 bg-white/10 rounded" @click="updateQty(ci.id, ci.qty + 1)">+</button>
            </div>
          </div>
          <button class="text-red-400 text-sm" @click="removeFromCart(ci.id)">Remove</button>
        </div>

        <div class="pt-2 border-t border-white/10">
          <div class="flex justify-between text-sm">
            <span>Total</span>
            <span class="font-semibold">{{ cartTotal }} {{ cartCurrency }}</span>
          </div>
        </div>

        <div class="pt-2">
          <input v-model="orderForm.customer_name" placeholder="Your name (optional)" class="w-full mb-2 bg-transparent border border-white/20 rounded px-3 py-2 text-white placeholder-white/60" />
          <input v-model="orderForm.phone" placeholder="Phone (optional)" class="w-full mb-2 bg-transparent border border-white/20 rounded px-3 py-2 text-white placeholder-white/60" />
          <textarea v-model="orderForm.notes" rows="2" placeholder="Notes (optional)" class="w-full bg-transparent border border-white/20 rounded px-3 py-2 text-white placeholder-white/60"></textarea>
          <div v-if="orderForm.errors.items" class="text-red-400 text-sm mt-1">{{ orderForm.errors.items }}</div>
          <button
            :disabled="orderForm.processing || cartItems.length === 0"
            @click="submitOrder"
            class="mt-3 w-full px-4 py-2 bg-yellow-500 text-black rounded hover:bg-yellow-600 disabled:opacity-50"
          >
            Place Order
          </button>
        </div>
      </div>
    </div>

    <div class="w-full bg-transparent">
      <div class="mx-0 px-0 py-6">
        <div v-if="!props.category" class="text-center text-white/80 px-6">
          Menu is not available yet. Please check back soon.
        </div>

        <template v-else>
          <div class="flex justify-center mb-4 px-4">
            <input
              v-model="searchTerm"
              type="text"
              placeholder="Search menu..."
              class="w-full max-w-xl bg-black/40 border border-white/20 rounded px-4 py-2 text-white placeholder-white/60"
            />
          </div>

          <!-- Logo -->
          <div class="flex justify-center mb-4">
            <img src="/images/logos/Hillside hotel logo.png" :alt="(props.category?.name || 'Menu') + ' Logo'" class="h-60 md:h-80" />
          </div>

          <!-- Brand buttons -->
          <div class="flex items-center px-3 justify-center">
            <div class="bg-black/40 rounded-md flex items-center justify-center gap-3 flex-wrap">
              <Link preserve-scroll="true"
                v-for="cat in props.allCategories"
                :key="cat.id"
                :href="`/menu/${cat.slug}`"
                class="type-btn menu-item text-upper text-center rounded text-white font-semibold px-4 py-2 transition"
                :class="{ 'active border-b-2 border-yellow-500': (props.category?.slug || '') === cat.slug }"
              >
                {{ cat.name.toUpperCase() }}
              </Link>
            </div>
          </div>

          <!-- Types and Subcategories -->
          <div class="mt-4 bg-black/30 rounded-md px-0 py-2 flex items-center justify-center flex-wrap menu-bar">
            <button
              v-for="(typeName, index) in typeNames"
              :key="index"
              @click="activeTypeIndex = index; showSubcategories(index)"
              :class="{ active: activeTypeIndex === index }"
              class="mx-3 flex flex-col items-center px-3 py-2 text-center rounded menu-item1 type-toggle transition"
            >
              <div class="h-6 w-6 mb-1 text-yellow-500">
                <i v-if="typeName.toLowerCase() === 'food'" class="fa-solid fa-utensils"></i>
                <i v-else-if="typeName.toLowerCase() === 'beverage'" class="fa-solid fa-wine-glass-empty"></i>
                <i v-else-if="typeName.toLowerCase() === 'dessert'" class="fa-solid fa-stroopwafel"></i>
                <i v-else-if="typeName.toLowerCase() === 'pizza'" class="fa-solid fa-pizza-slice"></i>
              </div>
              <div class="text-sm text-white font-medium">{{ typeName.toUpperCase() }}</div>
            </button>

            <!-- Subcategories -->
            <div class="w-full flex justify-center mt-3 flex-wrap">
              <button
                v-for="sub in visibleSubcategories"
                :key="sub.id"
                @click="scrollToSubcategory(sub.id)"
                class="menu-item1 px-1 py-2 text-white font-medium menu-sep mx-1 sm:px-3 transition"
              >
                {{ sub.name.toUpperCase() }}
              </button>
            </div>
          </div>
        </template>
      </div>
    </div>

    <!-- Items -->
    <div v-if="props.category" class="w-full max-w-6xl mx-auto px-6 py-6">
      <div v-if="searchTerm && !typeNames.length" class="text-center text-white/70 py-6">
        No items match your search.
      </div>
      <template v-for="(typeName, typeIndex) in typeNames" :key="typeIndex">
        <div v-show="activeTypeIndex === typeIndex">
          <template v-for="subcategory in displayTypeSubcategories[typeName]" :key="subcategory.id">
            <h3 :id="`sub-${subcategory.id}`" class="text-xl text-yellow-500 font-semibold mb-3 mt-6">
              {{ subcategory.name }}
            </h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
              <div
                v-for="item in subcategory.items"
                :key="item.id"
                @click="toggleItemSelection(item, $event)"
                class="bg-transparent p-3 md:p-4 rounded-md flex items-center menu-item hover:shadow-sm cursor-pointer transition hover:border-yellow-500 border border-white/10"
              >
                <img
                  :src="getImagePath(item.image)"
                  :alt="item.name"
                  loading="lazy"
                  class="w-20 h-20 md:w-24 md:h-24 object-cover rounded mr-4 flex-shrink-0"
                />
                <div class="flex-1">
                  <h4 class="font-semibold text-lg">{{ item.name }}</h4>
                  <p class="text-sm text-gray-300 line-clamp-2">{{ item.description }}</p>
                  <div class="mt-2 text-yellow-500 font-bold">
                    {{ item.price }} {{ item.currency }}
                  </div>
                  <div class="mt-2">
                    <button
                      @click.stop="addToCart(item)"
                      class="px-3 py-1 bg-yellow-500 text-black rounded hover:bg-yellow-600 text-sm"
                    >
                      Add to Cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </div>
      </template>
    </div>

    <div v-if="selectedItem" class="fixed inset-0 z-40 flex items-center justify-center bg-black/70 p-4" @click="closeItemModal">
      <div class="bg-black/80 border border-white/20 rounded-lg max-w-3xl w-full p-4 relative" @click.stop>
        <button
          class="absolute top-3 right-3 text-white/80 hover:text-white"
          @click="closeItemModal"
          aria-label="Close"
        >
          ✕
        </button>
        <div class="flex flex-col items-center gap-3">
          <img
            :src="getImagePath(selectedItem.image)"
            :alt="selectedItem.name"
            class="max-h-[70vh] w-auto rounded"
          />
          <div class="text-center">
            <h4 class="text-xl font-semibold">{{ selectedItem.name }}</h4>
            <p v-if="selectedItem.description" class="text-white/70 mt-1">
              {{ selectedItem.description }}
            </p>
            <div class="mt-3">
              <button
                class="px-4 py-2 bg-yellow-500 text-black rounded hover:bg-yellow-600"
                @click="addToCart(selectedItem)"
              >
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({
  layout: null,
})

const props = defineProps({
  brand: String,
  category: Object,
  allCategories: Array,
  types: Array,
})

const activeTypeIndex = ref(0)
const selectedItem = ref(null)
const cartOpen = ref(false)
const cartItems = ref([])
const searchTerm = ref('')

const orderForm = useForm({
  customer_name: '',
  phone: '',
  notes: '',
  items: [],
})

const typeSubcategories = computed(() => {
  const grouped = {}
  if (props.types?.length) {
    props.types.forEach((type) => {
      grouped[type.name] = []
    })
  }
  if (props.category?.subcategories) {
    props.category.subcategories.forEach((sub) => {
      const typeName = sub.type?.name || 'Other'
      if (!grouped[typeName]) {
        grouped[typeName] = []
      }
      grouped[typeName].push(sub)
    })
  }
  return grouped
})

const displayTypeSubcategories = computed(() => {
  if (!searchTerm.value) {
    return typeSubcategories.value
  }

  const term = searchTerm.value.toLowerCase().trim()
  const filtered = {}

  Object.entries(typeSubcategories.value).forEach(([typeName, subcats]) => {
    const nextSubcats = subcats
      .map((sub) => {
        const items = (sub.items || []).filter((item) => {
          const name = String(item.name || '').toLowerCase()
          const desc = String(item.description || '').toLowerCase()
          return name.includes(term) || desc.includes(term)
        })
        return { ...sub, items }
      })
      .filter((sub) => sub.items.length > 0)

    if (nextSubcats.length > 0) {
      filtered[typeName] = nextSubcats
    }
  })

  return filtered
})

const typeNames = computed(() => Object.keys(displayTypeSubcategories.value))

const visibleSubcategories = computed(() => {
  const typeName = typeNames.value[activeTypeIndex.value]
  return displayTypeSubcategories.value[typeName] || []
})

const getImagePath = (imagePath) => {
  if (!imagePath) return '/images/placeholder.png'
  let path = String(imagePath).trim().replace(/^\/*/, '')
  path = path.replace(/^(storage\/|public\/)/, '')
  if (!path.startsWith('images/')) {
    path = 'images/' + path.replace(/^\/*/, '')
  }
  return encodeURI('/' + path)
}

const addToCart = (item) => {
  if (!item) return
  const existing = cartItems.value.find((ci) => ci.id === item.id)
  if (existing) {
    existing.qty += 1
    return
  }

  cartItems.value.push({
    id: item.id,
    name: item.name,
    price: Number(item.price || 0),
    currency: item.currency || 'USD',
    image: item.image,
    qty: 1,
  })
}

const removeFromCart = (id) => {
  cartItems.value = cartItems.value.filter((ci) => ci.id !== id)
}

const updateQty = (id, qty) => {
  const item = cartItems.value.find((ci) => ci.id === id)
  if (!item) return
  if (qty <= 0) {
    removeFromCart(id)
    return
  }
  item.qty = qty
}

const cartCount = computed(() =>
  cartItems.value.reduce((sum, ci) => sum + ci.qty, 0)
)

const cartTotal = computed(() => {
  const total = cartItems.value.reduce((sum, ci) => sum + ci.price * ci.qty, 0)
  return total.toFixed(2)
})

const cartCurrency = computed(() => {
  return cartItems.value[0]?.currency || 'USD'
})

const toggleItemSelection = (item) => {
  selectedItem.value = item
}

const closeItemModal = () => {
  selectedItem.value = null
}

const showSubcategories = (index) => {
  activeTypeIndex.value = index
}

watch(searchTerm, () => {
  activeTypeIndex.value = 0
})

const scrollToSubcategory = (subId) => {
  const element = document.getElementById(`sub-${subId}`)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' })
  }
}

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const submitOrder = () => {
  if (cartItems.value.length === 0) return

  orderForm.items = cartItems.value.map((ci) => ({
    id: ci.id,
    qty: ci.qty,
  }))

  orderForm.post('/orders', {
    preserveScroll: true,
    onSuccess: () => {
      cartItems.value = []
      orderForm.reset('notes')
      cartOpen.value = false
    },
  })
}
</script>

<style scoped>
.menu-item1.type-toggle.active {
  text-decoration: underline 2px solid white;
  text-underline-offset: 4px;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
