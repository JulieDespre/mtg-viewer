<script setup>
import { onMounted, ref } from 'vue';
import { fetchAllCards } from '../services/cardService';

const cards = ref([]);
const loadingCards = ref(true);
const currentPage = ref(1);

async function loadCards() {
    loadingCards.value = true;
    cards.value = await fetchAllCards(currentPage.value);
    loadingCards.value = false;
}

onMounted(() => {
    loadCards();
});

const nextPage = () => {
  // eslint-disable-next-line no-plusplus
    currentPage.value++;
    loadCards();
};
const prevPage = () => {
    if (currentPage.value > 1) {
     currentPage.value--;
      loadCards();
    }
};

</script>

<template>
    <div>
        <h1>Toutes les cartes</h1>
        <button @click="prevPage" :disabled="currentPage === 1">Page précédente</button>
        <button @click="nextPage">Page suivante</button>
    </div>
    <div class="card-list">
        <div v-if="loadingCards">Loading...</div>
        <div v-else>
            <div class="card-result" v-for="card in cards" :key="card.id">
                <router-link :to="{ name: 'get-card', params: { uuid: card.uuid } }">
                    {{ card.name }} <span>({{ card.uuid }})</span>
                </router-link>
            </div>
        </div>
    </div>
</template>
